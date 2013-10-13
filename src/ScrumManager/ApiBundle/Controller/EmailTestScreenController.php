<?php

namespace ScrumManager\ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailTestScreenController extends Controller {

    /**
     * Test screen for creating a new email from the "system" user.
     */
    public function createNewFromSystemAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Email:createOneFromSystem', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/EmailTestScreen/create_new_from_system.html.twig');
    }

    public function createOneFromAccountAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Email:createOneFromAccount', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/EmailTestScreen/create_one_from_account.html.twig');
    }

    public function retrieveOneAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Email:retrieveOne', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/EmailTestScreen/retrieve_one.html.twig');
    }

    public function markOneAsReadAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Email:markOneAsRead', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/EmailTestScreen/mark_one_as_read.html.twig');
    }

    public function deleteOneAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->getRequest()->request->all();
            $requestParameters = $this->get('json.service')->encode($requestParameters);

            return $this->forward('ScrumManagerApiBundle:Email:deleteOne', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/EmailTestScreen/delete_one.html.twig');
    }
}