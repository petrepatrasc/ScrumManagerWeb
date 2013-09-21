<?php

namespace ScrumManager\ApiBundle\ResponseCode;

class BaseResponseCode {

    public static $message;
    public static $code;

    public function getMessage() {
        return static::$message;
    }

    public function getCode() {
        return static::$code;
    }
}