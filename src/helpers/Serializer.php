<?php

namespace choate\epluspay\helpers;


class Serializer
{
    public static function serialize($data) {
        return urlencode(base64_encode(json_encode($data)));
    }

    public static function unSerialize($data) {
        return json_decode(base64_decode(urldecode($data)), true);
    }
}