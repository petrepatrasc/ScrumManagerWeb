<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseIntegrationTestCase extends WebTestCase{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Method to execute before running a test.
     */
    public function setUp() {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $this->generateSchema();

        parent::setUp();
    }

    /**
     * Method to execute after running a test.
     */
    protected function tearDown() {
        parent::tearDown();
        unset($this->em);
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

    /**
     * Generate the schema required for the database testing.
     */
    protected function generateSchema()
    {
        $metadatas = $this->getMetadatas();

        if (!empty($metadatas)) {
            $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        }
    }

    /**
     * Read metadata of database.
     */
    protected function getMetadatas()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }
}