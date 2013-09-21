<?php

namespace ScrumManager\ApiBundle\Tests\EndToEnd;

use Doctrine\ORM\EntityManager;
use ScrumManager\ApiBundle\ResponseCode\System\ResponseSystemSuccess;
use Symfony\Component\HttpKernel\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class BaseFunctionalTestCase extends WebTestCase {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setUp() {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        parent::setUp();
    }

    protected function tearDown() {
        parent::tearDown();
        $this->em->close();
    }

    /**
     * Assert successful response from a controller action.
     * @param Client $client The client that we are getting the response from.
     * @return array The response data from the server.
     */
    public function assertSuccessfulResponse(Client $client) {
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $response = static::$kernel->getContainer()->get('json.service')->decode($client->getResponse()->getContent());

        $this->assertEquals(ResponseSystemSuccess::$code, $response['status']);

        return $response;
    }

    /**
     * Assert unsuccessful response from a controller action.
     * @param Client $client The client that we are getting the response from.
     * @return array The response data from the server.
     */
    public function assertErrorResponse(Client $client) {
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $response = static::$kernel->getContainer()->get('json.service')->decode($client->getResponse()->getContent());

        $this->assertNotEquals(ResponseSystemSuccess::$code, $response['status']);

        return $response;
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