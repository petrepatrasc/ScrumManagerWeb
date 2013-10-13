<?php

namespace ScrumManager\ApiBundle\ResponseCode\System;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseSystemError extends BaseResponseCode {

    public static $message = 'system.general.error';
    public static $code = 103;
}