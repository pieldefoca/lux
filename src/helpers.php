<?php

if(!function_exists('clean_trans')) {
    function clean_trans($key) {
        return str(trans($key))->replace('<p>', '')->replace('</p>', '')->toString();
    }
}