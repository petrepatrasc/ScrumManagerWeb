<?php
/**
 * Created by JetBrains PhpStorm.
 * User: petre
 * Date: 10/14/13
 * Time: 5:56 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ScrumManager\ApiBundle\Service;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class GeneralHelperService {

    /**
     * Generate a random string, with a specific length.
     * @param int $length The length of the random string.
     * @return string The string that was returned from the method call.
     */
    public static function generateRandomString($length = 10) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = '';

        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    public static function getDefaultSerializer() {
        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();

        return new Serializer(array($normalizer), array ($encoder));
    }
}