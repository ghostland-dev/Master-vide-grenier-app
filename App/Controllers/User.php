<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Flash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {

            $f = $_POST;

            if ($this->login($f)) {
                if ($this->isAdminAlready($f['email'])) {
                    header('Location: /dashboard');
                } else {
                    header('Location: /account');
                }
                exit(); // Make sure to exit after the redirect
            } else {
                View::renderTemplate('User/login.html');
            }

    }

    private function login($data)
    {
        try {
            if (!isset($data['email'])) {
                throw new Exception('TODO');
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }

            if (isset($data['remember_me']) && $data['remember_me'] == true) {
                // User has selected the "remember me" option
                $cookieName = 'remember_me';
                $cookieValue = 'example_cookie_value';
                $cookieExpiration = time() + (60 * 60 * 24 * 30); // Expiry time, e.g., 30 days

                setcookie($cookieName, $cookieValue, $cookieExpiration, '/');
            }
            // to remained logged in on the login form.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L86

            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );

            return true;
        } catch (Exception $ex) {

            Flash::danger($ex->getMessage());
        }
    }


    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;

            if ($f['password'] !== $f['password-check']) {
                // Passwords do not match
                $error = "Les mots de passe ne correspondent pas.";
            } elseif (empty($f['username']) || empty($f['password'])) {
                // Required fields are empty
                $error = "Veuillez remplir tous les champs obligatoires.";
            } elseif (strlen($f['password']) < 8) {
                // Password is too short
                $error = "Le mot de passe doit contenir au moins 8 caractères.";
            } elseif (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) {
                // Invalid email address
                $error = "Adresse e-mail invalide.";
            } elseif ($this->isEmailAlreadyUsed($f['email'])) {
                // Email is already used
                $error = "L'adresse e-mail est déjà utilisée.";
            } else {
                // All validations passed, proceed with registration
                $this->register($f);
                $this->login($f);

                // Check if user is an admin

                // Redirect to account page if not an admin
                header("Location: /account");
                exit(); // Stop further execution after redirecting

            }
        }

        // Render the registration template with appropriate error handling
        View::renderTemplate('User/register.html', ['message' => $error ?? null]);
    }

    /*
    * Fonction privée pour vérifier si l'email est déjà utilisé
    */
    private function isEmailAlreadyUsed($email)
    {
        // Check if the email exists in the database
        $user = \App\Models\User::getByLogin($email);
        return ($user !== false);
    }

    private function isAdminAlready($email)
    {
        // Check if the email exists in the database
        $user = \App\Models\User::getByRole($email);
        return ($user !== false);
    }
    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;
        } catch (Exception $ex) {

            Flash::danger($ex->getMessage());
        }
    }

    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }


    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction()
    {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /");

        return true;
    }
}
