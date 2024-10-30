<?php

function wpCJ_BuildURL($params) {
  global $cj_defaults;

  foreach ($cj_defaults as $key=>$value) {
    $final_list[$key] = $params[$key] or
      $final_list[$key] = get_option('api_'.$key) or
      $final_list[$key] = $value or
      $final_list[$key] = null;
  }

  return (isset($final_list['advertiser-ids'])) ? http_build_query($final_list) : false;
}

function wpCJ_GetProducts ($params) {
  if ($url =  wpCJ_BuildURL($params) ) {
    if (!CJCACHABLE || !($data = wpCJ_LoadCache(md5($url)))) {
      $ch = curl_init();
      $cj_product_search_url = 'https://product-search.api.cj.com/v2/product-search?';
      curl_setopt($ch,  CURLOPT_URL, $cj_product_search_url . $url);
      curl_setopt($ch, CURLOPT_POST, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.get_option('cj_key')));
      $response = curl_exec($ch);

      $data = ($response) ? new SimpleXMLElement($response) : false;

      if (CJCACHABLE) wpCJ_SaveCache(md5($url), serialize($response));
    }

    return $data;
  } else return false;
}

function wpCJ_LoadCache($filename) {
  if (!CJCACHABLE) return false;
  $cache_file = CJCACHEPATH . '/' . $filename;

  // 0 is an acceptable param, so we can't just do a boolean check
  if ( !($retain_time = get_option('cache_retain-time')) && ($retain_time != 0) ) {
    $retain_time = 3600;
  }

  if (file_exists($cache_file)) {

    if ( (time() - filemtime($cache_file)) <= $retain_time) {
      $contents = file_get_contents( $cache_file );
      return new SimpleXMLElement(unserialize($contents));
    }
  }

  return false;
}

function wpCJ_SaveCache($filename, $data) {
  if (!CJCACHABLE) return false;
  $cache_file = CJCACHEPATH . '/' . $filename;

  return file_put_contents($cache_file, $data);
}

function wpCJ_GenerateKeywords($xml) {
  if (!isset($xml->{'products'})) return false;

  $length_threshold = 4;

  foreach ($xml->{'products'}->{'product'} as $CurrentProduct) {
    foreach ( array_map("strtolower",preg_split("/[^a-zA-Z]+/", $CurrentProduct->name)) as $word) {
      if (strlen($word) > $length_threshold) {
        $word_list[$word]++;
      }
    }

    foreach ( array_map("strtolower",preg_split("/[^a-zA-Z]+/", $CurrentProduct->description)) as $word) {
      if (strlen($word) > $length_threshold) {
        $word_list[$word]++;
      }
    }

  }
  $word_list = array_filter($word_list, "wpCJ_threshold_filter");
  array_multisort($word_list, SORT_DESC);

  return $word_list;
}

function wpCJ_threshold_filter($var) {
  $match_threshold = 8;
  return ($var > $match_threshold) ? true : false;
}

?>