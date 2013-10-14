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