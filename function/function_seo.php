<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

function generate_seo_url($string, $wordLimit = 0) {
    $separator = '-';
    
    if($wordLimit != 0){
        $wordArr = explode(' ', $string);
        $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
    }

    $quoteSeparator = preg_quote($separator, '#');

    $trans = array(
        '&.+?;'                    => '',
        '[^\w\d _-]'            => '',
        '\s+'                    => $separator,
        '('.$quoteSeparator.')+'=> $separator
    );

    $string = strip_tags($string);
    foreach ($trans as $key => $val){
        //$string = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $string);
        $string = preg_replace('#'.$key.'#i'.'u', $val, $string);
    }

    $string = strtolower($string);

    return trim(trim($string, $separator));
}