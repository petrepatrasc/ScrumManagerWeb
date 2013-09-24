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
}