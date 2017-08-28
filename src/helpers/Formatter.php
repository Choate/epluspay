<?php

namespace choate\epluspay\helpers;


class Formatter
{
    public static function encode($data) {
        return urlencode(base64_encode(json_encode($data)));
    }

    public static function decode($data) {
        return json_decode(base64_decode(urldecode($data)), true);
    }
}