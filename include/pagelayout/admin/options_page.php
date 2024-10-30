<div class="wrap">
  <h2>CSL CJ Product Search</h2>

  <form method="post" action="options.php">
   <?php settings_fields('cscj-settings'); ?>

   <div id="poststuff" class="metabox-holder">
     <div class="meta-box-sortables">
       <script type="text/javascript">
         jQuery(document).ready(function($) {
             $('.postbox').children('h3, .handlediv').click(function(){
                 $(this).siblings('.inside').toggle();
             });
         });
       </script>

       <div class="postbox">
         <div class="handlediv" title="Click to toggle"><br/></div>
         <h3 class="hndle">
           <span>Product Settings</span>
         </h3>
         <div class="inside">
           <p>
             To obtain a key, please purchase this plugin from
             <a href="http://www.cybersprocket.com/products/wpcjproductsearch/" target="_new">http://www.cybersprocket.com/products/wpcjproductsearch/</a>
           </p>

           <table class="form-table" style="margin-top: 0pt;">
             <tr valign="top">
               <th scope="row">License Key *</th>
               <td>
                 <input type="text" <?= (!get_option('cscj-purchased')) ? 'name="cscj-license_key"' : '' ?> value="<?= get_option('cscj-license_key'); ?>" <?= (get_option('cscj-purchased')) ? 'disabled' : ''?> />
                 <? if (get_option('cscj-purchased')) { ?>
                 <input type="hidden" name="cscj-license_key" value="<?= get_option('cscj-license_key')?>"/>
                 <span><font color="green">Valid license key.  Thanks for your purchase!</font></span>
                 <? } ?>
                 <?= (get_option('cscj-license_key') == '') ? '<span><font color="red">Without a license key, this plugin will only function for Admins</font></span>' : '' ?>
                 <?= ( !(get_option('cscj-license_key') == '') && !get_option('cscj-purchased') ) ? '<span><font color="red">Your license key could not be verified</font></span>' : '' ?>

                 <? if (!get_option('cscj-purchased')) { ?>
                 <div>
                   <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WXZH2ATCDAJ4Y" target="_new">
                     <img alt="PayPal - The safer, easier way to pay online!" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" />
                   </a>
                 </div>
                 <? }?>

                 <div>
                   <p>Your license key is simply your PayPal transaction key</p>
                 </div>
               </td>
             </tr>
           </table>
         </div>
       </div>

       <div class="postbox">
         <div class="handlediv" title="Click to toggle"><br/></div>
         <h3 class="hndle">
           <span>Primary Settings</span>
         </h3>
         <div class="inside">
           <p>You will need to sign up for an account at <a href="http://webservices.cj.com" target="_new">Commission Junction</a> to fill in these fields.</p>

           <table class="form-table" style="margin-top: 0pt">
             <tr valign="top">
               <th scope="row">Commision Junction Key *</th>
               <td>
                 <textarea name="cj_key" cols="50" rows="5"><?=get_option('cj_key'); ?></textarea>
                 <?= (get_option('cj_key') == '') ? '<span><font color="red">This field is required</font></span>' : '' ?>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Commision Junction Web ID *</th>
               <td>
                 <input type="text" name="cj_webid" value="<?= get_option('cj_webid'); ?>" />
                 <?= (get_option('cj_webid') == '') ? '<span><font color="red">This field is required</font></span>' : '' ?>
               </td>
             </tr>
           </table>
         </div>
       </div>

       <div class="postbox">
         <div class="handlediv" title="Click to toggle"><br/></div>
         <h3 class="hndle">
           <span>API Settings</span>
         </h3>
         <div class="inside">
           <p>These are your personal defaults, they can be individually overridden when you insert your shortcodes.</p>
           <p>Example:</p>
           <p><code>[cj_show_items keywords="flower" records_per_page="15"]</code></p>

           <table class="form-table" style="margin-top: 0pt">
             <tr valign="top">
               <th scope="row">Advertiser IDs <code>advertiser_ids</code></th>
               <td>
                 <input type="text" name="api_advertiser-ids" value="<?= get_option('api_advertiser-ids'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('advertiser_ids_desc');">Description</a>
                 <div style="display: none;" id="advertiser_ids_desc">
                   <p>Limits the results to a set of particular advertisers (CIDs) using one of the following four values:</p>
                   <ul style="list-style: inside;">
                     <li>
                       <b>CIDs:</b> You may provide a list of one or more
                       advertiser CIDs, separated by commas, to limit the results to a
                       specific sub-set of merchants.
                     </li>
                     <li>
                       <b>Empty String:</b> You may provide an empty string to
                       remove any advertiser-specific restrictions on the search.
                     </li>
                     <li>
                       <b>joined:</b> This special value (<code>joined</code>) restricts the search to avertisers with wich you have a relationship.
                     </li>
                     <li>
                       <b>notjoined:</b> this special value (<code>not-joined</code>) restricts the search to advertisers with which you do not have a relationship.
                     </li>
                   </ul>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Keywords <code>keywords</code></th>
               <td>
                 <input type="text" name="api_keywords" value="<?= get_option('api_keywords'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('keywords_desc');">Description</a>
                 <div style="display: none;" id="keywords_desc">
                   <p>This value restricts the search results based on keywords
                   found in the advertiser's name, the product name, or the product
                   description. This parameter may be left blank if other paramaters
                   (such as <code>upc</code>, <code>isbn</code>) are provided. You may
                   use simple Boolean logic operators (’r;+’, ’r;-’r;) to obtain more
                   relevant search results. By default, the system assumes basic OR
                   logic.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Serviceable Area <code>serviceable_area</code></th>
               <td>
                 <input type="text" name="api_serviceable-area" value="<?= get_option('api_serviceable-area'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('serviceable_area_desc');">Description</a>
                 <div style="display: none;" id="serviceable_area_desc">
                   <p>Limits the results to a specific set of advertisers' targeted areas.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">ISBN <code>isbn</code></th>
               <td>
                 <input type="text" name="api_isbn" value="<?= get_option('api_isbn'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('isbn_desc');">Description</a>
                 <div style="display: none;" id="isbn_desc">
                   <p>Limits the results to a specific product from multiple
                   merchants identified by the appropriate unique identifier, ISBN.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">UPC <code>upc</code></th>
               <td>
                 <input type="text" name="api_upc" value="<?= get_option('api_upc'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('upc_desc');">Description</a>
                 <div style="display: none;" id="upc_desc">
                   <p>Limits the results to a specific product from multiple
                   merchants identified by the appropriate unique identifier, UPC.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Manufacturer's Name <code>manufacturer_name</code></th>
               <td>
                 <input type="text" name="api_manufacturer-name" value="<?= get_option('api_manufacturer-name'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('manufacturer_name_desc');">Description</a>
                 <div style="display: none;" id="manufacturer_name_desc">
                   <p>Limits the results to a particular manufacturer's name.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Manufacturer's SKU <code>manufacturer_sku</code></th>
               <td>
                 <input type="text" name="api_manufacturer-sku" value="<?= get_option('api_manufacturer-sku'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('manufacturer_sku_desc');">Description</a>
                 <div style="display: none;" id="manufacturer_sku_desc">
                   <p>Limits the results to a particular manufacturer's SKU number.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Advertiser SKU <code>advertiser_sku</code></th>
               <td>
                 <input type="text" name="api_advertiser-sku" value="<?= get_option('api_advertiser-sku'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('advertiser_sku_desc');">Description</a>
                 <div style="display: none;" id="advertiser_sku_desc">
                   <p>Limits the results to a particular advertiser SKU.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Low Price <code>low_price</code></th>
               <td>
                 <input type="text" name="api_low-price" value="<?= get_option('api_low-price'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('low_price_desc');">Description</a>
                 <div style="display: none;" id="low_price_desc">
                   <p>Limits the results to products with a price greater than or equal to the <code>low_price</code>.</p>
                   <p><b>Tip:</b> Use in conjunction with the <code>high_price</code> to specify a range of prices.</p>
                   <p><b>Note:</b> Only whole numbers are supported for this
                   request parameter. the <code>low_price</code> parameter is inclusive,
                   whereas the <code>high_price</code> parameter is exclusive. For
                   example, using a low price of 10 and a high price of 20 will return
                   everything from 10 to 19.99.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">High Price <code>high_price</code></th>
               <td>
                 <input type="text" name="api_high-price" value="<?= get_option('api_high-price'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('high_price_desc');">Description</a>
                 <div style="display: none;" id="high_price_desc">
                   <p>Limits the results to products less than or equal to the <code>high_price</code>.</p>
                   <p><b>Tip:</b> Use in conjunction with the <code>low_price</code> to specify a range of prices.</p>
                   <p><b>Note:</b> Only whole numbers are supported for this
                   request parameter. the <code>low_price</code> parameter is inclusive,
                   whereas the <code>high_price</code> parameter is exclusive. For
                   example, using a low price of 10 and a high price of 20 will return
                   everything from 10 to 19.99.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Low Sale Price <code>low_sale_price</code></th>
               <td>
                 <input type="text" name="api_low-sale-price" value="<?= get_option('api_low-sale-price'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('low_sale_price_desc');">Description</a>
                 <div style="display: none;" id="low_sale_price_desc">
                   <p>Limits the results to products with a price greater than
                   or equal to the Advertiser offered <code>low_sale_price</code>.</p>
                   <p><b>Note:</b> Only whole numbers are supported for this
                   request parameter. The <code>low_sale_price</code> parameter is
                   inclusive, whereas the <code>high_sale_price</code> parameter is
                   exclusive. For example, using a low sale price of 10 and a high sale
                   price of 20 will return everything from 10 to 19.99.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">High Sale Price <code>high_sale_price</code></th>
               <td>
                 <input type="text" name="api_high-sale-price" value="<?= get_option('api_high-sale-price'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('high_sale_price_desc');">Description</a>
                 <div style="display: none;" id="high_sale_price_desc">
                   <p>Limits the results to products with a price less than or
                   equal to the Advertiser offered <code>high_sale_price</code>.</p>
                   <p><b>Note:</b> Only whole numbers are supported for this
                   request parameter. The <code>low_sale_price</code> parameter is
                   inclusive, whereas the <code>high_sale_price</code> parameter is
                   exclusive. For example, using a low sale price of 10 and a high sale
                   price of 20 will return everything from 10 to 19.99.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Currency <code>currency</code></th>
               <td>
                 <input type="text" name="api_currency" value="<?= get_option('api_currency'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('currency_desc');">Description</a>
                 <div style="display: none;" id="currency_desc">
                   <p>Limits the results to one of the following currencies:</p>
                   <ul style="list-style: inside;">
                     <li>USD</li>
                     <li>EUR</li>
                     <li>GBP</li>
                   </ul>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Sort By <code>sort_by</code></th>
               <td>
                 <input type="text" name="api_sort-by" value="<?= get_option('api_sort-by'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('sort_by_desc');">Description</a>
                 <div style="display: none;" id="sort_by_desc">
                   <p>Sort the results in the response by one of the following values.</p>
                   <ul style="list-style: inside;">
                     <li>Name</li>
                     <li>Advertiser ID</li>
                     <li>Advertiser Name</li>
                     <li>Currency</li>
                     <li>Price</li>
                     <li>salePrice</li>
                     <li>Manufacturer</li>
                     <li>SKU</li>
                     <li>UPC</li>
                   </ul>
                   <p><b>Note:</b> Only the results returned in the particular
                   request are sorted by the value of this parameter. The system
                   automatically sorts all matching results in the index (not just the
                   results in the specific request) by relevance to keyword value sent in
                   the request.</p>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Sort Order <code>sort_order</code></th>
               <td>
                 <input type="text" name="api_sort-order" value="<?= get_option('api_sort-order'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('sort_order_desc');">Description</a>
                 <div style="display: none;" id="sort_order_desc">
                   <p>Specifies the order in which the results are sorted; the following case-insensitive values are acceptable:</p>
                   <ul style="list-style: inside;">
                     <li><b>asc:</b> ascending (default value)</li>
                     <li><b>dec:</b> descending</li>
                   </ul>
                 </div>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Items Per Page <code>records_per_page</code></th>
               <td>
                 <input type="text" name="api_records-per-page" value="<?= get_option('api_records-per-page'); ?>" />
                 <a href="javascript:;" onclick="swapVisibility('records_per_page_desc');">Description</a>
                 <div style="display: none;" id="records_per_page_desc">
                   <p>Specifies the number of records to return in the
                   request. Leaving this parameter blank assigns a default value of
                   50.</p>
                   <p><b>Note:</b> 1000 results is the system limit for results
                   per request. If you request a value greater than 1000, the system only
                   returns 1000.</p>
                 </div>
               </td>
             </tr>
           </table>
         </div>
       </div>


       <div class="postbox">
         <div class="handlediv" title="Click to toggle"><br/></div>
         <h3 class="hndle">
           <span>Cache Settings</span>
         </h3>
         <div class="inside">
           <p>Your cache directory can be found at: <code><?= CJCACHEPATH ?></code></p>

           <table class="form-table" style="margin-top: 0pt">
             <tr valign="top">
               <th scope="row">Enable Caching</th>
               <td>
                 <input type="checkbox" name="cache_enable" <?= (get_option('cache_enable')) ? 'checked' : ''; ?>>
               </td>
             </tr>
             <tr valign="top">
               <th scope="row">Retain Time</th>
               <td>
                 <select name="cache_retain-time">
                   <option value="0" <?= (get_option('cache_retain-time') == 0) ? 'selected' : ''; ?> >None</option>
                   <option value="3600" <?= (get_option('cache_retain-time') == 3600) ? 'selected' : ''; ?> >1 Hour</option>
                   <option value="18000" <?= (get_option('cache_retain-time') == 18000) ? 'selected' : ''; ?> >5 Hours</option>
                   <option value="86400" <?= (get_option('cache_retain-time') == 86400) ? 'selected' : ''; ?> >1 Day</option>
                   <option value="604800" <?= (get_option('cache_retain-time') == 604800) ? 'selected' : ''; ?> >1 Week</option>
                 </select>
               </td>
             </tr>
           </table>
         </div>
       </div>

       <div class="postbox">
         <div class="handlediv" title="Click to toggle"><br/></div>
         <h3 class="hndle">
           <span>Product Info</span>
         </h3>
         <div class="inside" style="min-height: 150px;">
           <img src="<?= CJPLUGINURL ?>/images/CSL_Logo_Only.jpg" style="float: left; padding: 5px;"/>
           <h4>This plugin was written and created by Cyber Sprocket Labs</h4>
           <p>
             Cyber Sprocket Labs provides technical consulting
             services for small-to-medium sized businesses.  If you’ve got an
             online business concept, a new piece of cool software you want
             written, or just need some help getting your technical team organized
             and pointed in the right direction, we can help.
           </p>
           <p>
             For more information, please visit our website at <a href="http://www.cybersprocket.com" target="_new">www.cybersprocket.com</a>
           </p>
           <p>
             Visit the product page for this plugin <a href="http://www.cybersprocket.com/products/wpcjproductsearch/" target="_new">here</a>.
           </p>
         </div>
       </div>

     </div>
   </div>



   <p class="submit">
     <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
   </p>
  </form>

</div>

<script type="text/javascript">
    function swapVisibility(id) {
        var item = document.getElementById(id);
        item.style.display = (item.style.display == 'block') ? 'none' : 'block';
    }
</script>
