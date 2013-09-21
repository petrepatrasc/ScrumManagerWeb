<?php

namespace ScrumManager\ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller exposing the API for the Account entity.
 * @package ScrumManager\ApiBundle\Controller
 */
class AccountController extends Controller {

    /**
     * Register a new account on the platform.
     */
    public function registerAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $account = $this->get('account.service')->register($requestData);

        if ($account) {
            return $this->get('json.service')->sucessResponse();
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Check the login credentials for a user.
     */
    public function loginAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $username = $requestData['username'];
        $password = $requestData['password'];

        $account = $this->get('account.service')->login($username, $password);

        if ($account) {
            return $this->get('json.service')->sucessResponse($account->toSafeArray());
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Update a single entry in the application.
     */
    public function updateOneAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $apiKey = $requestData['api_key'];

        unset($requestData['api_key']);
        unset($requestData['reset_token']);
        unset($requestData['reset_initiated_at']);
        unset($requestData['seed']);
        unset($requestData['password']);

        $account = $this->get('account.service')->updateOne($apiKey, $requestData);

        if ($account) {
            return $this->get('json.service')->sucessResponse($account->toSafeArray());
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Change the password of a single entry in the application.
     */
    public function changePasswordAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $apiKey = $requestData['api_key'];
        $oldPassword = $requestData['old_password'];
        $newPassword = $requestData['new_password'];

        $account = $this->get('account.service')->changePassword($apiKey, $oldPassword, $newPassword);

        if ($account) {
            return $this->get('json.service')->sucessResponse();
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Retrieve a single account from the application.
     */
    public function retrieveOneAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));
        $username = $requestData['username'];

        $account = $this->get('account.service')->retrieveOne($username);

        if ($account) {
            return $this->get('json.service')->sucessResponse($account->toSafeArray());
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Deactivate an account from the application.
     */
    public function deactivateAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));
        $apiKey = $requestData['api_key'];

        $account = $this->get('account.service')->deactivateAccount($apiKey);

        if ($account) {
            return $this->get('json.service')->sucessResponse();
        }

        return $this->get('json.service')->errorResponse();
    }

    /**
     * Reset the password for an account in the application.
     */
    public function resetPasswordAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));
        $apiKey = $requestData['api_key'];

        $account = $this->get('account.service')->resetPasswordForAccount($apiKey);

        if ($account) {
            return $this->get('json.service')->sucessResponse();
        }

        return $this->get('json.service')->errorResponse();
    }
}