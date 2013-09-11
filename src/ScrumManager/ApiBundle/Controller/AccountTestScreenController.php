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

    public function registerAction() {
        if ($this->getRequest()->getMethod() == 'POST') {
            $requestParameters = $this->get('json.service')->encode($this->getRequest()->request->all());

            return $this->forward('ScrumManagerApiBundle:Account:register', array(
                'json_data' => $requestParameters
            ));
        }

        return $this->render('@ScrumManagerApi/AccountTestScreen/register.html.twig');
    }
}