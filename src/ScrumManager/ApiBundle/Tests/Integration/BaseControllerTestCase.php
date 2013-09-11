<?php

namespace ScrumManager\ApiBundle\Tests\Integration;

use Symfony\Component\HttpKernel\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class BaseControllerTestCase extends WebTestCase {

    /**
     * Assert successful response from a controller action.
     * @param Client $client The client that we are getting the response from.
     */
    public function assertSuccessfulResponse(Client $client) {
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $response = static::$kernel->getContainer()->get('json.service')->decode($client->getResponse()->getContent());

        $this->assertEquals(100, $response['status']);
    }

    /**
     * Assert unsuccessful response from a controller action.
     * @param Client $client The client that we are getting the response from.
     */
    public function assertErrorResponse(Client $client) {
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $response = static::$kernel->getContainer()->get('json.service')->decode($client->getResponse()->getContent());

        $this->assertNotEquals(100, $response['status']);
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