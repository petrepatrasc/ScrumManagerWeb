<?php

namespace ScrumManager\ApiBundle\Controller;


use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailCreateFailure;
use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailRetrieveFailure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailController extends Controller {

    /**
     * Create a new email from the "system" user.
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createOneFromSystemAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $receiver = $requestData['receiver'];
        $subject = $requestData['subject'];
        $content = $requestData['content'];

        $email = $this->get('email.service')->createOne("system", $receiver, $subject, $content);

        if ($email) {
            return $this->get('json.service')->sucessResponse();
        }

        return $this->get('json.service')->errorResponse(new ResponseEmailCreateFailure());
    }

    /**
     * Retrieve an email from the system.
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function retrieveOneAction() {
        $requestData = $this->get('json.service')->decode($this->getRequest()->get('json_data'));

        $id = $requestData['id'];
        $email = $this->get('email.service')->retrieveOne($id);

        if ($email) {
            return $this->get('json.service')->sucessResponse($email->toSafeArray());
        }

        return $this->get('json.service')->errorResponse(new ResponseEmailRetrieveFailure());
    }
}