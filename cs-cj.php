<?php
/*
  Plugin Name: Commission Junction Product Search
  Plugin URI: http://www.cybersprocket.com/products/wpcjproductsearch/
  Description: Our wpCJProductSearch plugin allows you to quickly and easily display products from your Commission Junction affiliate partners. Install the plugin and you can add products to your existing blog posts or pages just be entering a shortcode. If you are a Commission Junction Affiliate Partner, this plugin is for you!
  Version: 1.0.7
  Author: Cyber Sprocket Labs
  Author URI: http://www.cybersprocket.com
  License: GPL3
*/

/*	Copyright 2010  Cyber Sprocket Labs (info@cybersprocket.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('CJPLUGINDIR', plugin_dir_path(__FILE__));
define('CJPLUGINURL', plugins_url('',__FILE__));
define('CJCACHEPATH', CJPLUGINDIR . 'cache');

include_once('include/config.php');

if ( is_admin() ) {
  add_action('admin_menu', 'wpCJ_admin_menu');
  add_action('admin_init', 'wpCJ_Register_Settings');
  add_action('admin_notices', 'wpCJ_admin_notices');
  add_action('admin_menu', 'wpCJ_Handle_Admin_Menu');
  add_action('admin_head', 'wpCJ_header');
  add_filter('admin_print_scripts', 'wpCJ_Admin_Head');
} else {
  // non-admin enqueues, actions, and filters
  add_action('wp_head', 'wpCJ_header_user');
}

// For consistency, I'm adding cj_show_items, but it's also a lot
// safer to have the other variation as well
add_shortcode('cj_show-items', 'wpCJ_show_items');
add_shortcode('cj_show_items', 'wpCJ_show_items');

function wpCJ_Register_Settings() { // whitelist options
  /* Product Settings */
  wpCSL_initialize_license_options('cscj');

  /* Primary Settings */
  register_setting('cscj-settings', 'cj_key');
  register_setting('cscj-settings', 'cj_webid');

  /* API Settings */
  register_setting('cscj-settings', 'api_advertiser-ids');
  register_setting('cscj-settings', 'api_keywords');
  register_setting('cscj-settings', 'api_serviceable-area');
  register_setting('cscj-settings', 'api_isbn');
  register_setting('cscj-settings', 'api_upc');
  register_setting('cscj-settings', 'api_manufacturer-name');
  register_setting('cscj-settings', 'api_manufacturer-sku');
  register_setting('cscj-settings', 'api_advertiser-sku');
  register_setting('cscj-settings', 'api_low-price');
  register_setting('cscj-settings', 'api_high-price');
  register_setting('cscj-settings', 'api_low-sale-price');
  register_setting('cscj-settings', 'api_high-sale-price');
  register_setting('cscj-settings', 'api_currency');
  register_setting('cscj-settings', 'api_sort-by');
  register_setting('cscj-settings', 'api_sort-order');
  register_setting('cscj-settings', 'api_page-number');
  register_setting('cscj-settings', 'api_records-per-page');

  /* Cache Settings */
  register_setting('cscj-settings', 'cache_enable');
  register_setting('cscj-settings', 'cache_retain-time');
}

function wpCJ_show_items($atts, $content = NULL) {
  global $current_user;
  get_currentuserinfo();

  if ( ($current_user->wp_capabilities['administrator']) || ($current_user->user_level == '10') || get_option('cscj-purchased')) {

    $xml = wpCJ_GetProducts(wpCJ_process_atts($atts));

    $cj_content = NULL;
    if ($xml) {
      if (isset($xml->{'products'})) {
        // Keywords system is not being used at the moment
        /* $auto_keywords = wpCJ_GenerateKeywords($xml); */
        $attributes = ($xml) ? $xml->products->attributes() : false;
        if ( $attributes && ($attributes['total-matched'] > 0) ) {
          $cj_content = wpCJ_product_listing($xml);
        } else {
          $cj_content = "No products found";
        }
      } else {
        $cj_content =  $xml->{'error-message'};
      }
    } else {
      $cj_content =  "There was an error in retrieving your products.";
    }
  }

  return $cj_content;
}

function wpCJ_product_listing($xml) {

  foreach ($xml->{'products'}->{'product'} as $CurrentProduct) {
    $return_content .= "<div class=\"cscj-product\">\n";
    $return_content .= "<h3>{$CurrentProduct->{'name'}}</h3>\n";
    $return_content .= "<div>\n";
    $return_content .= "<a href=\"{$CurrentProduct->{'buy-url'}}\" target=\"newinfo\">\n";
    $return_content .= "<img src=\"{$CurrentProduct->{'image-url'}}\" alt=\"{$CurrentProduct->{'name'}}\" title=\"{$CurrentProduct->{'name'}}\" />\n";
    $return_content .= "</a>\n";
    $return_content .= "<span>\n";
    $return_content .= "<p>{$CurrentProduct->{'description'}}</p>\n";
    $return_content .= "<p>\n";
    $return_content .= $CurrentProduct->{'currency'} ."\n";
    $return_content .= "$<a href=\"{$CurrentProduct->{'buy-url'}}\" target=\"newinfo\">" . money_format('%i', (float)$CurrentProduct->{'price'}) ."</a>\n";
    $return_content .= "</p>\n";
    $return_content .= "</span>\n</div>\n</div>\n";
  }

  return $return_content;
}

// Not being used currently, will require heavy reworking to interface with Wordpress properly
function wpCJ_keyword_list($keywords) {
  foreach ($keywords as $keyword=>$count) {
    $keyword_path = ((isset($search_keywords)) ? str_replace(' ', '/', $search_keywords) : '') . "/";
    // Not sure whether or not the links should be addative at this point...
    // echo "<a href='" . ROOT_POSTFIX . "/$keyword_path$keyword'>$keyword</a> \n";
    echo "<a href='" . ROOT_POSTFIX . "/$keyword'>$keyword</a> \n";
  }
}

// When wordpress processes shortcode attributes it will produce
// erroneous results if any of the attribute names contain
// dashes. However, the params that get sent to CJ are very specific
// and contain dashes, so this means that these attributes need to be
// written with underscores in the shortcodes and then converted to
// dashes for CJ.
function wpCJ_process_atts($atts) {
  foreach ($atts as $key=>$value) {
    $return_atts[str_replace('_', '-', $key)] = $value;
  }
  return $return_atts;
}

function wpCJ_admin_menu() {
  add_options_page('wpCJProductSearch Options', 'CJ Product Search', 'administrator', 'CSCJ-options', 'wpCJ_options_page');
}

function wpCJ_options_page() {
  include('include/pagelayout/admin/options_page.php');
}

function wpCJ_check_required_options() {
  // Make sure the user has entered in the required CJ info
  foreach (array('cj_key', 'cj_webid') as $option) {
    if (get_option($option) == '') {
      $notices['options'][] = $option;
    }
  }

  return (isset($notices)) ? $notices : false;
}

function wpCJ_check_cache() {
  if ( defined('CJCACHABLE')) return;
  $is_cachable = false;
  if (get_option('cache_enable')) {
    $cache_file = CJCACHEPATH . '/' . $filename;

    if (!file_exists(CJCACHEPATH)) {
      $notices['cache'] =
        "You do not have a cache directory<br>
         If you would like to implement caching, please create the cache directory: <code>" . CJCACHEPATH . "</code>";
    } else if (!is_writable(CJCACHEPATH)) {
      $notices['cache'] =
        "Your cache directory is not writable<br>
         If you would like to implement caching, please change the permissions on the cache directory: <code>" . CJCACHEPATH . "</code>";
    } else $is_cachable = true; // looks like we can cache stuff
  }

  define('CJCACHABLE', $is_cachable);

  return (isset($notices)) ? $notices : false;
}

function wpCJ_admin_notices() {
  $notices[] = wpCJ_check_required_options();
  $notices[] = wpCJ_check_cache();
  $notices[] = wpCSL_check_product_key('cscj');

  // Generate the warning message

  foreach ($notices as $notice) {
    if ($notice) {
      $notice_output = "<div id='cscj_warning' class='updated fade' style='background-color: rgb(255, 102, 102);'>";
      $notice_output .= sprintf(__('<p><strong><a href="%1$s">CSL CJ Product Search</a> needs attention: </strong>'),"options-general.php?page=CSCJ-options");

      if (isset($notice['options'])) {
        $notice_output .= 'Please provide the following on the settings page: ';
        $notice_output .= join(',', $notice['options']);
      }

      foreach( array('cache', 'product') as $item) {
        if (isset($notice[$item])) {
          $notice_output .= $notice[$item];
        }
      }

      $notice_output .= "</p></div>";

      $notices_output[] = $notice_output;
    }
  }

  if ($notices_output) {
    foreach ($notices_output as $output) echo $output;
  }
}

function wpCJ_header_user() {
  wpCJ_check_cache();
  wpCJ_header();
}

function wpCJ_header() {
  echo '<link type="text/css" rel="stylesheet" href="' . plugins_url('css/cscj.css', __FILE__) . '"/>' . "\n";
}

function wpCJ_Handle_Admin_Menu() {
  if (!wpCJ_check_required_options()) {
    add_meta_box('wpcjStoreMB', 'CSL Quick Commission Junction Entry', 'wpCJ_StoreInsertForm', 'post', 'normal');
    add_meta_box('wpcjStoreMB', 'CSL Quick Commission Junction Entry', 'wpCJ_StoreInsertForm', 'page', 'normal');
  }
}

function wpCJ_Admin_Head () {
  if ($GLOBALS['editing']) {
    wp_enqueue_script('wpCJStoreAdmin', plugins_url('js/cjstore.js', __FILE__), array('jquery'), '1.0.0');
  }
}

function wpCJ_StoreInsertForm() {
?>
<table class="form-table">
  <tr valign="top">
    <th align="right" scope="row"><label for="wpCJ_keywords"><?php _e('Keywords:')?></label></th>
    <td>
      <input type="text" size="40" style="width:95%;" name="wpCJ_keywords" id="wpCJ_keywords" />
    </td>
  </tr>
  <tr valign="top">
    <th align="right" scope="row"><label for="wpCJ_itemcount"><?php _e('Number of Items:')?></label></th>
    <td>
      <select name="wpCJ_itemcount" id="wpCJ_itemcount">
        <option>1</option>
        <option>5</option>
        <option>10</option>
        <option>20</option>
        <option>50</option>
      </select>
    </td>
  </tr>
</table>
<p class="submit">
  <input type="button" onclick="return this_wpCJAdmin.sendToEditor(this.form);" value="<?php _e('Create Commission Junction Shortcode &raquo;'); ?>" />
</p>
<?php
}

?>
