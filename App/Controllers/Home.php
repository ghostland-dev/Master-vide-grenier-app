<?php

namespace App\Controllers;


use \Core\View;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Articles;
use App\Models\Cities;
use App\Models\User;
use Symfony\Component\Yaml\Dumper;

// require 'vendor/autoload.php';

/**
 * Home controller
 */
class Home extends \Core\Controller
{

    /**
     * Affiche la page d'accueil
     *
     * @return void
     * @throws \Exception
     */
    public function indexAction()
    {

        View::renderTemplate('Home/index.html', []);
    }

    public function dashboardAction()
    {

        if (isset($_SESSION['user']['id'])) {
            $currentUser = $_SESSION['user']['id'];
            $user = User::getById($currentUser);

            if ($user['is_admin']) {
                $users = count(User::getAll());
                $cities = count(Cities::getAll());
                $articles = count(Articles::getAll(''));

                View::renderTemplate('Home/dashboard.html', ["articles" => $articles, "users" => $users, "cities" => $cities]);
            } else {
                header('Location: /account');
            }
        } else {
            header('Location: /account');
        }
    }



    private function isAdminAlready($email)
    {
        // Check if the email exists in the database
        $user = \App\Models\User::getByRole($email);
        return ($user !== false);
    }



    public function contactAction()
    {



        View::renderTemplate('Home/contact.html', []);
    }


    public function sendAction()
    {


        $email = $_POST['email'];
        $message = $_POST['message'];

        try {


            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '***REMOVED***';
            $mail->Password = '***REMOVED***';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;


            $mail->setFrom($email, 'From Name');
            $mail->addAddress('to@example.com', 'To Name');
            $mail->Subject = 'Test Email';
            $mail->Body = "$message";


            if ($mail->send()) {

                View::renderTemplate('Home/contact.html', ['message' => 'Message envoyé avec succès']);
            } else {


                View::renderTemplate('Home/contact.html', ['message' => "une erreur est servenu"]);
            }
        } catch (Exception $e) {

            View::renderTemplate('Home/contact.html', ['message' => "une erreur est servenu : $e"]);
        }
    }
}
