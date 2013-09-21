<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use ScrumManager\ApiBundle\Entity\Account;
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
            'password' => hash('sha256', $this->generateRandomString(60)),
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

    /**
     * Register a new account and make sure that its data is valid.
     * @param AccountService $service The service that should be called.
     * @return Account Account entity that was created.
     */
    protected function createNewAccountAndAssertIt(AccountService $service) {
        $account = $service->register($this->data);

        $this->assertNotNull($account->getId());
        $this->assertEquals($this->data['username'], $account->getUsername());
        $this->assertNotEquals($this->data['password'], $account->getPassword());
        $this->assertEquals($this->data['first_name'], $account->getFirstName());
        $this->assertEquals($this->data['last_name'], $account->getLastName());
        $this->assertEquals($this->data['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());

        return $account;
    }

    /**
     * Test the login method - first we will create a new user, and then attempt to log in using their credentials.
     */
    public function testLogin_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->data['username'], $this->data['password']);

        $this->assertNotNull($accountLogin);
        $this->assertNotNull($accountLogin->getId());
        $this->assertEquals($this->data['username'], $accountLogin->getUsername());
        $this->assertNotEquals($this->data['password'], $accountLogin->getPassword());
        $this->assertEquals($this->data['first_name'], $accountLogin->getFirstName());
        $this->assertEquals($this->data['last_name'], $accountLogin->getLastName());
        $this->assertEquals($this->data['email'], $accountLogin->getEmail());
        $this->assertNull($accountLogin->getResetToken());
        $this->assertNull($accountLogin->getResetInitiatedAt());
    }

    /**
     * Test the login method when the username is invalid. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidUsername() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->data['username'] . 'invalid', $this->data['password']);

        $this->assertNull($accountLogin);
    }

    /**
     * Test the login method when the password is invalid. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidPassword() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->data['username'], $this->data['password'] . 'invalid');

        $this->assertNull($accountLogin);
    }

    /**
     * Test the login method when the account has been deactivated. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account->getApiKey());

        $accountLogin = $accountService->login($this->data['username'], $this->data['password']);

        $this->assertNull($accountLogin);
    }

    /**
     * Test the update method, by first creating a valid user, asserting its data and then updating that data
     * via the service, and asserting the results.
     */
    public function testUpdateOne_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
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

        $account = $accountService->updateOne($account->getApiKey(), $updateData);

        $this->assertNotNull($account);
        $this->assertNotNull($account->getId());
        $this->assertEquals($updateData['username'], $account->getUsername());
        $this->assertNotEquals(hash('sha512', $account->getSeed() . $updateData['password']), $account->getPassword());
        $this->assertEquals($updateData['first_name'], $account->getFirstName());
        $this->assertEquals($updateData['last_name'], $account->getLastName());
        $this->assertEquals($updateData['email'], $account->getEmail());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
    }

    /**
     * Test the update method, by providing an account that has been deactivated.
     */
    public function testUpdateOne_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
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

        $account = $accountService->deactivateAccount($account->getApiKey());
        $account = $accountService->updateOne($account->getApiKey(), $updateData);

        $this->assertNull($account);
    }

    /**
     * Test the update method, by providing an invalid API key.
     */
    public function testUpdateOnce_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
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

        $account = $accountService->updateOne($this->generateRandomString(20), $updateData);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism with valid data.
     */
    public function testChangePassword_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = $this->generateRandomString(100);
        $account = $accountService->changePassword($account->getApiKey(), $this->data['password'], $newPass);

        $this->assertNotNull($account);
        $this->assertNotEquals($account->getPassword(), hash('sha512', $account->getSeed() . $this->data['password']));
        $this->assertEquals($account->getPassword(), hash('sha512', $account->getSeed() . $newPass));
    }

    /**
     * Test the change password mechanism when providing an invalid API key.
     */
    public function testChangePassword_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = $this->generateRandomString(100);
        $account = $accountService->changePassword($account->getApiKey() . 'invalid', $this->data['password'], $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism when providing the account has been disabled.
     */
    public function testChangePassword_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account->getApiKey());

        $newPass = $this->generateRandomString(100);
        $account = $accountService->changePassword($account->getApiKey(), $this->data['password'], $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism when providing an invalid old password.
     */
    public function testChangePassword_InvalidOldPassword() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = $this->generateRandomString(100);
        $account = $accountService->changePassword($account->getApiKey(), $this->data['password'] . 'invalid', $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the mechanism for retrieving a single account when data is valid.
     */
    public function testRetrieveOne_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->retrieveOne($account->getUsername());

        $this->assertNotNull($retrievedAccount);
        $this->assertNotNull($retrievedAccount->getId());
        $this->assertEquals($this->data['username'], $retrievedAccount->getUsername());
        $this->assertEquals($this->data['first_name'], $retrievedAccount->getFirstName());
        $this->assertEquals($this->data['last_name'], $retrievedAccount->getLastName());
        $this->assertEquals($this->data['email'], $retrievedAccount->getEmail());
        $this->assertNull($retrievedAccount->getResetToken());
        $this->assertNull($retrievedAccount->getResetInitiatedAt());
    }

    /**
     * Test the mechanism for retriving a single account when the username does not exist.
     */
    public function testRetrieveOne_InvalidUsername() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->retrieveOne($account->getUsername() . 'invalid');

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the mechanism for retriving a single account when the account has already been deactivated.
     */
    public function testRetrieveOne_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account->getApiKey());

        $retrievedAccount = $accountService->retrieveOne($account->getUsername());

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the deactivation mechanism for a single account and when the data is valid.
     */
    public function testDeactivateAccount_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account->getApiKey());

        $this->assertNotNull($retrievedAccount);
        $this->assertFalse($retrievedAccount->getActive());
    }

    /**
     * Test the deactivation mechanism for a single account and when the API key is invalid.
     */
    public function testDeactivateAccount_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account->getApiKey() . 'invalid');

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the deactivation mechanism for a single account and when the account has already been disabled.
     */
    public function testDeactivateAccount_InvalidAccountAlreadyDisabled() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account->getApiKey());

        $this->assertNotNull($retrievedAccount);
        $this->assertFalse($retrievedAccount->getActive());

        $retrievedAccount = $accountService->deactivateAccount($account->getApiKey());
        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the password reset mechanism when the data is valid.
     */
    public function testResetPasswordForAccount_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey());

        $this->assertNotNull($account);
        $this->assertNotNull($account->getResetToken());
        $this->assertNotNull($account->getResetInitiatedAt());
    }

    /**
     * Test the password reset mechanism when the API key is invalid.
     */
    public function testResetPasswordForAccount_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey() . 'invalid');

        $this->assertNull($account);
    }

    /**
     * Test the password reset mechanism when the account has already been previously deactivated.
     */
    public function testResetPasswordForAccount_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account->getApiKey());
        $account = $accountService->resetPassword($account->getApiKey());

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the data is valid, in the happy flow.
     */
    public function testNewPassword_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey());
        $this->assertNotNull($account);

        $oldPassword = $account->getPassword();
        $newPassword = $this->generateRandomString(10);
        $account = $accountService->newPassword($account->getApiKey(), $account->getResetToken(), $newPassword);

        $this->assertNotNull($account);
        $this->assertNull($account->getResetInitiatedAt());
        $this->assertNull($account->getResetToken());
        $this->assertNotEquals($oldPassword, $account->getPassword());
    }

    /**
     * Test adding a new password when the API key is invalid.
     */
    public function testNewPassword_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey());
        $this->assertNotNull($account);

        $newPassword = $this->generateRandomString(10);
        $account = $accountService->newPassword($account->getApiKey() . 'invalid', $account->getResetToken(), $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the reset token is invalid.
     */
    public function testNewPassword_InvalidResetToken() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey());
        $this->assertNotNull($account);

        $newPassword = $this->generateRandomString(10);
        $account = $accountService->newPassword($account->getApiKey(), $account->getResetToken() . 'invalid', $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the account is deactivated.
     */
    public function testNewPassword_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account->getApiKey());
        $this->assertNotNull($account);

        $account = $accountService->deactivateAccount($account->getApiKey());
        $newPassword = $this->generateRandomString(10);
        $account = $accountService->newPassword($account->getApiKey(), $account->getResetToken(), $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the request for a reset was never made.
     */
    public function testNewPassword_InvalidResetRequestNeverMade() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPassword = $this->generateRandomString(10);
        $account = $accountService->newPassword($account->getApiKey(), $account->getResetToken(), $newPassword);

        $this->assertNull($account);
    }
}