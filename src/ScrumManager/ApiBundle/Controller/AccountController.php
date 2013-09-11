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
}