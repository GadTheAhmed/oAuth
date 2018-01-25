<?php

function base64_image($image_string, $path) //Take Encoded Image String
{
    try {
        $path = $path .'/'. str_random(60).'.jpg' ;
        ## TODO :: TEST WITHOUT THIS HEADER
        // header('Content-Type: bitmap; charset=utf-8');
        $file = fopen('uploads/'.$path, 'wb') ;
        fwrite($file, base64_decode($image_string));
        fclose($file);
        return $path ;
    }
    catch (Exception $exception){
        return "" ;
    }
}