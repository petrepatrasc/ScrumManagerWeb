<?php

namespace ScrumManager\ApiBundle\Tests\EndToEnd;

use ScrumManager\ApiBundle\ResponseCode\Account\ResponseAccountInvalidCredentials;
use ScrumManager\ApiBundle\ResponseCode\Account\ResponseAccountNotFound;
use ScrumManager\ApiBundle\ResponseCode\Account\ResponseAccountRegistrationFailure;
use ScrumManager\ApiBundle\Service\GeneralHelperService;

class AccountControllerTest extends BaseFunctionalTestCase {

    /**
     * Test the account registration action by going through the test screen for it.
     */
    public function testRegister_Valid() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);
    }

    protected function registerAccount($formData) {
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $form['username'] = $formData['username'];
        $form['password'] = $formData['password'];
        $form['email'] = $formData['email'];
        $form['firstName'] = $formData['firstName'];
        $form['lastName'] = $formData['lastName'];

        $crawler = $this->client->submit($form);
    }

    /**
     * Test the account registration action by sending an invalid email as a request.
     */
    public function testRegister_InvalidEmail() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10);
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountRegistrationFailure::$code, $responseData['status']);
    }

    /**
     * Test the account registration action by sending an invalid email as a request.
     */
    public function testRegister_InvalidUsername() {
        $register['username'] = GeneralHelperService::generateRandomString(1);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountRegistrationFailure::$code, $responseData['status']);
    }

    /**
     * Test the authentication mechanism when dealing with valid data.
     */
    public function testLogin_ValidData() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);

        $login['username'] = $register['username'];
        $login['password'] = $register['password'];

        $this->loginAccount($login);
        $this->assertSuccessfulResponse($this->client);
    }

    protected function loginAccount($formData) {
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');

        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $formData['username'];
        $form['password'] = $formData['password'];

        $crawler = $this->client->submit($form);
    }

    /**
     * Test the authentication mechanism when dealing with an invalid username.
     */
    public function testLogin_InvalidUsername() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);

        $login['username'] = $register['username'] . GeneralHelperService::generateRandomString(10);
        $login['password'] = $register['password'];

        $this->loginAccount($login);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountInvalidCredentials::$code, $responseData['status']);
    }

    /**
     * Test the authentication mechanism when dealing with an invalid password.
     */
    public function testLogin_InvalidPassword() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);

        $login['username'] = $register['username'];
        $login['password'] = $register['password'] . GeneralHelperService::generateRandomString(10);

        $this->loginAccount($login);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountInvalidCredentials::$code, $responseData['status']);
    }

    /**
     * Test the updating mechanism for a single entry.
     */
    public function testUpdateOne_Valid() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);

        $login['username'] = $register['username'];
        $login['password'] = $register['password'];

        $this->loginAccount($login);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $update['username'] = GeneralHelperService::generateRandomString(10);
        $update['password'] = GeneralHelperService::generateRandomString(10);
        $update['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $update['firstName'] = GeneralHelperService::generateRandomString(10);
        $update['lastName'] = GeneralHelperService::generateRandomString(10);
        $update['apiKey'] = $apiKey;


        $this->updateOneAccount($update);
        $this->assertSuccessfulResponse($this->client);
    }

    protected function updateOneAccount($formData) {
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/UpdateOne');

        $form = $crawler->selectButton('Update')->form();
        $form['username'] = $formData['username'];
        $form['password'] = $formData['password'];
        $form['email'] = $formData['email'];
        $form['firstName'] = $formData['firstName'];
        $form['lastName'] = $formData['lastName'];
        $form['api_key'] = $formData['apiKey'];

        $crawler = $this->client->submit($form);
    }

    /**
     * Test the updating mechanism for a single entry, but with an invalid API key.
     */
    public function testUpdateOne_InvalidApiKey() {
        $register['username'] = GeneralHelperService::generateRandomString(10);
        $register['password'] = GeneralHelperService::generateRandomString(10);
        $register['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $register['firstName'] = GeneralHelperService::generateRandomString(10);
        $register['lastName'] = GeneralHelperService::generateRandomString(10);

        $this->registerAccount($register);
        $this->assertSuccessfulResponse($this->client);

        $login['username'] = $register['username'];
        $login['password'] = $register['password'];

        $this->loginAccount($login);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'] . GeneralHelperService::generateRandomString(10);

        $update['username'] = GeneralHelperService::generateRandomString(10);
        $update['password'] = GeneralHelperService::generateRandomString(10);
        $update['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $update['firstName'] = GeneralHelperService::generateRandomString(10);
        $update['lastName'] = GeneralHelperService::generateRandomString(10);
        $update['apiKey'] = $apiKey;

        $this->updateOneAccount($update);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /*
     * Test the change password mechanism when providing valid data.
     */
    public function testChangePassword_Valid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);

        $apiKey = $responseData['apiKey'];
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ChangePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password;
        $newPassword = GeneralHelperService::generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $newPassword;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
    }

    /*
     * Test the change password mechanism when providing invalid old password.
     */
    public function testChangePassword_InvalidOldPassword() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);

        $apiKey = $responseData['apiKey'];
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ChangePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password . 'invalid';
        $newPassword = GeneralHelperService::generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /*
     * Test the change password mechanism when providing invalid API key.
     */
    public function testChangePassword_InvalidApiKey() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);

        $apiKey = GeneralHelperService::generateRandomString(20);
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ChangePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password;
        $newPassword = GeneralHelperService::generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /*
     * Test the retrieve method for a single account, when the data is valid.
     */
    public function testRetrieveOne_Valid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/RetrieveOne');
        $form = $crawler->selectButton('Retrieve One')->form();
        $form['username'] = $username;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);
    }

    /*
     * Test the retrieve method for a single account, when the username is invalid.
     */
    public function testRetrieveOne_InvalidUsername() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/RetrieveOne');
        $form = $crawler->selectButton('Retrieve One')->form();
        $form['username'] = $username . 'invalid';

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /*
     * Test the deactivate mechanism, when data is valid.
     */
    public function testDeactivate_Valid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Deactivate');
        $form = $crawler->selectButton('Deactivate')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $this->assertErrorResponse($this->client);
    }

    /*
     * Test the deactivate mechanism, when data is valid.
     */
    public function testDeactivate_Invalid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'] . 'invalid';

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Deactivate');
        $form = $crawler->selectButton('Deactivate')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertErrorResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
    }

    /**
     * Test the reset mechanism when data is valid.
     */
    public function testResetPassword_Valid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);
    }

    /**
     * Test the reset mechanism when data is invalid.
     */
    public function testResetPassword_InvalidApiKey() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'] . 'invalid';

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /**
     * Test the new password mechanism when data is valid.
     */
    public function testNewPassword_Valid() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $accountEntity = $repo->findOneBy(array('apiKey' => $apiKey));
        $this->assertNotNull($accountEntity);

        $resetToken = $accountEntity->getResetToken();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/NewPassword');
        $form = $crawler->selectButton('New Password')->form();
        $form['api_key'] = $apiKey;
        $form['reset_token'] = $resetToken;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
    }

    /**
     * Test the new password mechanism when the API key is invalid.
     */
    public function testNewPassword_InvalidApiKey() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $accountEntity = $repo->findOneBy(array('apiKey' => $apiKey));
        $this->assertNotNull($accountEntity);

        $resetToken = $accountEntity->getResetToken();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/NewPassword');
        $form = $crawler->selectButton('New Password')->form();
        $form['api_key'] = $apiKey . 'invalid';
        $form['reset_token'] = $resetToken;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /**
     * Test the new password mechanism when the request token is invalid.
     */
    public function testNewPassword_InvalidRequestToken() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $accountEntity = $repo->findOneBy(array('apiKey' => $apiKey));
        $this->assertNotNull($accountEntity);

        $resetToken = $accountEntity->getResetToken();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/NewPassword');
        $form = $crawler->selectButton('New Password')->form();
        $form['api_key'] = $apiKey;
        $form['reset_token'] = $resetToken . 'invalid';
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /**
     * Test the new password mechanism when the account has been deactivated.
     */
    public function testNewPassword_InvalidAccountDeactivated() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/ResetPassword');
        $form = $crawler->selectButton('Reset Password')->form();
        $form['api_key'] = $apiKey;

        $crawler = $this->client->submit($form);
        $this->assertSuccessfulResponse($this->client);

        $repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $accountEntity = $repo->findOneBy(array('apiKey' => $apiKey));
        $this->assertNotNull($accountEntity);

        $accountEntity->setActive(false);
        $repo->updateOne($accountEntity);

        $resetToken = $accountEntity->getResetToken();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/NewPassword');
        $form = $crawler->selectButton('New Password')->form();
        $form['api_key'] = $apiKey;
        $form['reset_token'] = $resetToken;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

    /**
     * Test the new password mechanism when the reset password request never comes in.
     */
    public function testNewPassword_InvalidRequestForNewPasswordNeverMade() {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Register');

        $form = $crawler->selectButton('Register')->form();

        $username = GeneralHelperService::generateRandomString(10);
        $password = GeneralHelperService::generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = GeneralHelperService::generateRandomString(10) . '@dreamlabs.ro';
        $form['firstName'] = GeneralHelperService::generateRandomString(10);
        $form['lastName'] = GeneralHelperService::generateRandomString(10);

        $crawler = $this->client->submit($form);

        $this->assertSuccessfulResponse($this->client);

        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/Login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertSuccessfulResponse($this->client);
        $apiKey = $responseData['apiKey'];

        $this->assertSuccessfulResponse($this->client);

        $repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $accountEntity = $repo->findOneBy(array('apiKey' => $apiKey));
        $this->assertNotNull($accountEntity);

        $resetToken = $accountEntity->getResetToken();
        $crawler = $this->client->request('GET', '/en/api/testscreen/Account/NewPassword');
        $form = $crawler->selectButton('New Password')->form();
        $form['api_key'] = $apiKey;
        $form['reset_token'] = $resetToken;
        $form['password'] = $password;

        $crawler = $this->client->submit($form);
        $responseData = $this->assertErrorResponse($this->client);
        $this->assertEquals(ResponseAccountNotFound::$code, $responseData['status']);
    }

}