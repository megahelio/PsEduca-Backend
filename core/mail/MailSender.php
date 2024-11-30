<?php

require_once __DIR__ . "/PHPMailer.php";
//require_once __DIR__ . "/OAuth.php";

class MailSender
{
    private PHPMailer $phpMailer;

    /**
     * MailSender constructor.
     */
    public function __construct()
    {
        // No podemos utilizar autenticación basic. Outlook exige OAUTH2
        // https://support.microsoft.com/es-es/office/ahora-se-necesitan-m%C3%A9todos-de-autenticaci%C3%B3n-modernos-para-seguir-sincronizando-el-correo-electr%C3%B3nico-de-outlook-en-aplicaciones-de-correo-electr%C3%B3nico-que-no-son-de-microsoft-c5d65390-9676-4763-b41f-d7986499a90d
        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host       = MAIL_HOST;                     //Set the SMTP server to send through
        $this->phpMailer->Port       = MAIL_PORT;
        $this->phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//Enable implicit TLS encryption
        $this->phpMailer->SMTPAuth   = true;                          //Enable SMTP authentication

        $this->phpMailer->Username   = MAIL_USER_NAME;                //SMTP username
        $this->phpMailer->Password   = MAIL_PASSWORD;                 //SMTP password

//        $this->phpMailer->AuthType = 'XOAUTH2';
//
//        $provider = new Microsoft([
//            'clientId' => MAIL_OAUTH2_CLIENT_ID,
//            'clientSecret' => MAIL_OAUTH2_CLIENT_SECRET,
//            'redirectUri' => 'https://login.microsoftonline.com/common/oauth2/nativeclient',
//        ]);
//
//        $this->phpMailer->setOAuth(
//            new \PHPMailer\PHPMailer\OAuth([
//                    'provider' => $provider,
//                    'clientId' => MAIL_OAUTH2_CLIENT_ID,
//                    'clientSecret' => MAIL_OAUTH2_CLIENT_SECRET,
//                    'refreshToken' => MAIL_OAUTH2_REFRESH_TOKEN,
//                    'userName' => MAIL_USER_NAME,
//                ]
//            )
//        );

        // Enable verbose debug output
//        $this->phpMailer->SMTPDebug  = 2;                             // Set to 2 for detailed debug output
//        $this->phpMailer->Debugoutput = 'html';
    }

    /**
     * @throws MailException
     */
    public function send(Mail $mail) : void {

//        // Prepara el correo electrónico
//        $headers = [
//            'From' => MAIL_USER_NAME, // Dirección desde donde se envía
//            'Reply-To' => $mail->getSenderEmail(), // Dirección para recibir respuestas
////            'X-Mailer' => 'PHP/' . phpversion() // Opcional: información sobre el cliente de correo
//        ];
//
//        $result = mail(
//            to: CONTACT_DESTINATION_DIR,
//            subject: $mail->getSubject(),
//            message: $mail->getMessage(),
//            additional_headers: $headers
//        );

        try {
            $this->phpMailer->setFrom(MAIL_USER_NAME, 'PsEduca Contact Form');

            $this->phpMailer->addAddress(CONTACT_DESTINATION_DIR);
            $this->phpMailer->addReplyTo($mail->getSenderEmail(), $mail->getSenderName());
    //        $this->phpMailer->addCC('cc@example.com');
    //        $this->phpMailer->addBCC('bcc@example.com');

            $this->phpMailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->phpMailer->isHTML(false);

            $this->phpMailer->Subject = $mail->getSubject();
            $this->phpMailer->Body = $mail->getMessage();
            $this->phpMailer->AltBody = $mail->getMessage();

            $this->phpMailer->send();

        } catch (Exception $e) {
            throw new MailException($e->getMessage());
        }

    }
}