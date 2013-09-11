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
     * Update a single entry in the database.
     */
    public function updateOneAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $apiKey = $requestData['api_key'];

        unset($requestData['api_key']);
        unset($requestData['reset_token']);
        unset($requestData['reset_initiated_at']);
        unset($requestData['seed']);

        $account = $this->get('account.service')->updateOne($apiKey, $requestData);

        if ($account) {
            return $this->get('json.service')->sucessResponse($account->toSafeArray());
        }

        return $this->get('json.service')->errorResponse();
    }
}