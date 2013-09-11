<?php
/**
 * Created by JetBrains PhpStorm.
 * User: petre
 * Date: 9/13/13
 * Time: 7:31 AM
 * To change this template use File | Settings | File Templates.
 */

namespace ScrumManager\ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountTestScreenController extends Controller {

    /**
     * Test screen for registration.
     */
    public function registerAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters['password'] = hash('sha256', $requestParameters['password']);
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Account:register', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/AccountTestScreen/register.html.twig');
    }

    /**
     * Test screen for authentication.
     */
    public function loginAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters['password'] = hash('sha256', $requestParameters['password']);
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Account:login', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/AccountTestScreen/login.html.twig');
    }
}