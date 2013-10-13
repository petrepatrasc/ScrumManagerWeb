<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailDeleteFailure extends BaseResponseCode {


    public static $message = 'Deleting an email failed';
    public static $code = 304;
}