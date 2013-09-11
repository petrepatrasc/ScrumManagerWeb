<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use ScrumManager\ApiBundle\Service\AccountService;
use Symfony\Component\Validator\Validator;
use \DateTime;

class AccountServiceTest extends BaseIntegrationTestCase {

    /**
     * @var AccountRepository
     */
    protected $repo;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $data;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $this->validator = static::$kernel->getContainer()->get('validator');

        $this->data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(60),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(30) . '@dreamlabs.ro',
            'reset_token' => null,
            'reset_initiated_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
        unset($this->validator);
        unset($this->data);
    }

    /**
     * Test the registration method, with valid parameters, and make sure that we receive an entity
     * after going through all of the
     */
    public function testRegister_Valid() {
        $accountService = new AccountService($this->validator, $this->em);

        $account = $accountService->register($this->data);

        $this->assertNotNull($account->getId());
        $this->assertEquals($this->data['username'], $account->getUsername());
        $this->assertNotEquals($this->data['password'], $account->getPassword());
        $this->assertEquals($this->data['first_name'], $account->getFirstName());
        $this->assertEquals($this->data['last_name'], $account->getLastName());
        $this->assertEquals($this->data['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
    }

    /**
     * Test that a single unique username can be registered into the system.
     */
    public function testRegister_UsernameUnique() {
        $accountService = new AccountService($this->validator, $this->em);

        $firstAccount = $accountService->register($this->data);
        $secondAccount = $accountService->register($this->data);

        $this->assertNotNull($firstAccount->getId());
        $this->assertEquals($this->data['username'], $firstAccount->getUsername());
        $this->assertNotEquals($this->data['password'], $firstAccount->getPassword());
        $this->assertEquals($this->data['first_name'], $firstAccount->getFirstName());
        $this->assertEquals($this->data['last_name'], $firstAccount->getLastName());
        $this->assertEquals($this->data['email'], $firstAccount->getEmail());
        $this->assertNull($firstAccount->getResetToken());
        $this->assertNull($firstAccount->getResetInitiatedAt());

        $this->assertNull($secondAccount);
    }

    /**
     * Test the register action when the username is null.
     */
    public function testRegister_UsernameNull() {
        $this->data['username'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is blank.
     */
    public function testRegister_UsernameBlank() {
        $this->data['username'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is integer.
     */
    public function testRegister_UsernameIsInteger() {
        $this->data['username'] = 1;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is too small.
     */
    public function testRegister_UsernameIsTooSmall() {
        $this->data['username'] = 'x';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is too long..
     */
    public function testRegister_UsernameIsTooLarge() {
        $this->data['username'] = $this->generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is unset..
     */
    public function testRegister_UsernameUnset() {
        unset($this->data['username']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the password is not set.
     */
    public function testRegister_PasswordIsNotSet() {
        unset($this->data['password']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNotNull($account->getId());
        $this->assertEquals($this->data['username'], $account->getUsername());
        $this->assertNotNull($account->getPassword());
        $this->assertNotEquals($account->getPassword(), hash('sha512', $account->getSeed()));
        $this->assertEquals($this->data['first_name'], $account->getFirstName());
        $this->assertEquals($this->data['last_name'], $account->getLastName());
        $this->assertEquals($this->data['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
    }

    /**
     * Test the register action when the email is not in a valid format.
     */
    public function testRegister_EmailIsNotValid() {
        $this->data['email'] = 'invalid.com';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is not in unset.
     */
    public function testRegister_EmailIsUnset() {
        unset($this->data['email']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is blank.
     */
    public function testRegister_EmailIsBlank() {
        $this->data['email'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is null.
     */
    public function testRegister_EmailIsNull() {
        $this->data['email'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is an integer.
     */
    public function testRegister_EmailIsInteger() {
        $this->data['email'] = 3;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is too short.
     */
    public function testRegister_EmailIsTooShort() {
        $this->data['email'] = 't@a.com';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is too long.
     */
    public function testRegister_EmailIsTooLong() {
        $this->data['email'] = $this->generateRandomString(168) . '@dreamlabs.ro';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is blank.
     */
    public function testRegister_FirstNameIsBlank() {
        $this->data['first_name'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is null.
     */
    public function testRegister_FirstNameIsNull() {
        $this->data['first_name'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is not of a valid type.
     */
    public function testRegister_FirstNameIsInteger() {
        $this->data['first_name'] = 100;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is unset.
     */
    public function testRegister_FirstNameIsUnset() {
        unset($this->data['first_name']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is too long.
     */
    public function testRegister_FirstNameIsTooLong() {
        $this->data['first_name'] = $this->generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is blank.
     */
    public function testRegister_LastNameIsBlank() {
        $this->data['last_name'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is null.
     */
    public function testRegister_LastNameIsNull() {
        $this->data['last_name'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is not of a valid type.
     */
    public function testRegister_LastNameIsInteger() {
        $this->data['last_name'] = 100;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is unset.
     */
    public function testRegister_LastNameIsUnset() {
        unset($this->data['last_name']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is too long.
     */
    public function testRegister_LastNameIsTooLong() {
        $this->data['last_name'] = $this->generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the created_at field is missing.
     */
    public function testRegister_CreatedAtIsNotSet() {
        unset($this->data['created_at']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNotNull($account->getId());
        $this->assertEquals($this->data['username'], $account->getUsername());
        $this->assertNotEquals($this->data['password'], $account->getPassword());
        $this->assertEquals($this->data['first_name'], $account->getFirstName());
        $this->assertEquals($this->data['last_name'], $account->getLastName());
        $this->assertEquals($this->data['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
        $this->assertNotNull($account->getCreatedAt());
    }

    /**
     * Test the register action when the updated_at field is missing.
     */
    public function testRegister_UpdatedAtIsNotSet() {
        unset($this->data['updated_at']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->data);

        $this->assertNotNull($account->getId());
        $this->assertEquals($this->data['username'], $account->getUsername());
        $this->assertNotEquals($this->data['password'], $account->getPassword());
        $this->assertEquals($this->data['first_name'], $account->getFirstName());
        $this->assertEquals($this->data['last_name'], $account->getLastName());
        $this->assertEquals($this->data['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
        $this->assertNotNull($account->getUpdatedAt());
    }
}