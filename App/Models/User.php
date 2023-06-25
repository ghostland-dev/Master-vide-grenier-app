<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model
{

    /**
     * Crée un utilisateur
     */

    public static function getAll()
    {
        $db = static::getDB();

        $query = 'SELECT * FROM users ';

        $stmt = $db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function createUser($data)
    {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password,:salt)');

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT * FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public static function getByRole($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
        SELECT * FROM users WHERE (users.email = :email) LIMIT 1
    ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && $user['is_admin'] == 1) {
            // User is an admin
            $user['is_admin'] = true;
        } else {
            // User is not an admin
            $user['is_admin'] = false;
        }

        return $user;
    }


    public static function getById($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
        SELECT * FROM users WHERE (users.id = :id) LIMIT 1
    ");

        $stmt->bindParam(':id', $login);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && $user['is_admin'] == 1) {
            // User is an admin
            $user['is_admin'] = true;
        } else {
            // User is not an admin
            $user['is_admin'] = false;
        }

        return $user;
    }



    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function login($id)
    {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM articles WHERE articles.id = :id LIMIT 1');

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
