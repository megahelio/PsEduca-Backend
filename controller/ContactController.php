<?php

require_once(__DIR__ . "/BaseController.php");
require_once (__DIR__ . "/../core/ResponseCodes.php");
require_once(__DIR__ . "/../service/MailService.php");

use exception\ValidationException;

class ContactController extends BaseController {

    private MailService $mailService;

    public function __construct() {

        $this->mailService = new MailService();
    }

    public function send($data): void
    {

        try{
            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $subject = $data['subject'] ?? null;
            $message = $data['message'] ?? null;

            $this->mailService->sendContactMail($name, $email, $subject, $message);

            parent::generateHttpResponse(200, array(ResponseCodes::RECORDSET_EMPTY));

        } catch (ValidationException $e) {
            parent::generateHttpResponse(400, $e->getErrors());
        } catch (MailException) {
            parent::generateHttpResponse(503, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        } catch (Throwable $e) {
            echo $e;
            parent::generateHttpResponse(500, array(ResponseCodes::INTERNAL_SERVER_ERROR_KO));
        }
    }

}