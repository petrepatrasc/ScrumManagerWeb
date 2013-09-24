<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailCreateFailure extends BaseResponseCode {


    public static $message = 'Creating a new email failed';
    public static $code = 301;
}