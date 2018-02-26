<?php
/**
 * Created by PhpStorm.
 * User: Charles
 * Date: 11/01/2018
 * Time: 6:54 PM
 */

namespace app\controllers;


use app\models\UserService;
use core\Controller;
use core\View;

class User extends Controller
{
    private $service;
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
        $this->service = new UserService();
    }

    public function registerAction()
    {
        $user = new \app\models\User();
        $user->username = $_POST['username'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->photo = $_POST['photo'];
        $user->phone = $_POST['phone'];
        $user->paypal = $_POST['paypal'];
        $hasphoto = $_POST['hasphoto'] == "true" ? true : false;
        $result = $this->service->insert_user($user, $hasphoto);
        echo json_encode($result);
    }

    /**
     * @throws \Exception
     */
    public function registerPageAction()
    {
        echo View::render("user/register.html.twig", array("user"=>isset($_SESSION['user'])?$_SESSION['user']:null));
    }

    // check whether the username has been used
    public function validateUsernameAction()
    {
        $username = $_GET['username'];
        $result = $this->service->validateUsername($username);
        echo $result ? "true" : "false";
    }

    // check whether the email has been used
    public function validateEmailAction()
    {
        $email = $_GET['email'];
        $result = $this->service->validateEmail($email);
        echo $result ? "true" : "false";
    }

    // get the verification code
    public function getVerificationCodeAction()
    {
        $email = $_GET['email'];
        if (isset($_SESSION['verification_code']) && time() - $_SESSION['last_verification_code_time'] < 60){
            $code = $_SESSION['verification_code'];
        }else{
            $code = rand(1000, 9999);
            $_SESSION['verification_code'] = $code;
            $_SESSION['last_verification_code_time'] = time();
        }
        $to = $email; // note the comma

// Subject
        $subject = 'Cooking World- Email Verification';

// Message
        $message = "<html>
                        <head>
                          <title>Email Verification</title>
                        </head>
                        <body>
                            <h3>Welcome to Cooking World!</h3><br>
                          <p>Your verification code is <strong>" . $code . "</strong></p>
                        </body>
                    </html>";

// To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Additional headers
        $headers[] = 'To: <'. $email .'>';
        $headers[] = 'From: Cooking World <charles@cookingworld.com>';

// Mail it
        echo mail($to, $subject, $message, implode("\r\n", $headers)) ? "true" : "false";
        //TODO gmail smtp service
//        $mail = new PHPMailer(); // create a new object
//        $mail->IsSMTP(); // enable SMTP
//        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
//        $mail->SMTPAuth = true; // authentication enabled
//        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
//        $mail->Host = "smtp.gmail.com";
//        $mail->Port = 465; // or 587
//        $mail->IsHTML(true);
//        $mail->Username = "email@gmail.com";
//        $mail->Password = "password";
//        $mail->SetFrom("example@gmail.com");
//        $mail->Subject = "Test";
//        $mail->Body = "hello";
//        $mail->AddAddress("email@gmail.com");
//
//        if(!$mail->Send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
//        } else {
//            echo "Message has been sent";
//        }
        // Multiple recipients
    }

    // verify the email
    public function verifyEmailAction()
    {

    }

    public function loginAction()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $isRem = $_POST['isRem']=="true" ? true : false;
        $result = $this->service->log_in($username, $password, $isRem);
        echo json_encode($result);
    }

    public function signoutAction()
    {
        unset($_SESSION['user']);
    }


}