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
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
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
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
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

    /**
     * Test the login method with valid data.
     */
    public function testLogin_Valid() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
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
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'], $data['password'], $data['seed']);

        $this->assertNotNull($accountWithLogin);
        $this->assertNotNull($accountWithLogin->getId());
        $this->assertEquals($data['username'], $accountWithLogin->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $accountWithLogin->getPassword());
        $this->assertEquals($data['seed'], $accountWithLogin->getSeed());
        $this->assertEquals($data['first_name'], $accountWithLogin->getFirstName());
        $this->assertEquals($data['last_name'], $accountWithLogin->getLastName());
        $this->assertEquals($data['email'], $accountWithLogin->getEmail());
        $this->assertEquals($data['api_key'], $accountWithLogin->getApiKey());
        $this->assertEquals($data['reset_token'], $accountWithLogin->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $accountWithLogin->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $accountWithLogin->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $accountWithLogin->getUpdatedAt());
    }

    /**
     * Test the login method with invalid username.
     */
    public function testLogin_InvalidUsername() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
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
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'] . 'random', $data['password'], $data['seed']);

        $this->assertNull($accountWithLogin);
    }

    /**
     * Test the login method with invalid password.
     */
    public function testLogin_InvalidPassword() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
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
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'], $data['password'] . 'random', $data['seed']);

        $this->assertNull($accountWithLogin);
    }
}