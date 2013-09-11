<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use ScrumManager\ApiBundle\Tests\Integration\BaseIntegrationTestCase;
use \DateTime;

class AccountRepositoryTest extends BaseIntegrationTestCase {

    /**
     * @var AccountRepository
     */
    protected $repo;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
    }

    /**
     * Test the create method with valid data.
     */
    public function testCreate_Valid() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(128),
            'seed' => $this->generateRandomString(16),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'reset_token' => $this->generateRandomString(128),
            'reset_initiated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $account = new Account();
        $account->setUsername($data['username']);
        $account->setPassword($data['password']);
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals($data['password'], $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());
    }
}