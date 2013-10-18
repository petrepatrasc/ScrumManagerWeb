<?php
/**
 * Created by JetBrains PhpStorm.
 * User: petre
 * Date: 9/13/13
 * Time: 7:49 AM
 * To change this template use File | Settings | File Templates.
 */

namespace ScrumManager\ApiBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


class BaseService {

    protected $serializer;

    /**
     * Base constructor that gets called for all of the service classes.
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;

        $this->serializer = GeneralHelperService::getDefaultSerializer();
    }

    /**
     * Generate a random string, with a specific length.
     * @param int $length The length of the random string.
     * @return string The string that was returned from the method call.
     */
    protected function generateRandomString($length = 10) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = '';

        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }
}