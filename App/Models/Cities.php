<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * City Model:
 */
class Cities extends Model
{

    public static function search($str)
    {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT ville_id FROM villes_france WHERE ville_nom_reel LIKE :query');

        $query = $str . '%';

        $stmt->bindParam(':query', $query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    }


    public static function getAll()
    {
        $db = static::getDB();

        $query = 'SELECT * FROM `videgrenierenligne`.`villes_france` LIMIT 1000';


        $stmt = $db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getOne($id)
    {
        $db = static::getDB();

        $stmt =
            $db->prepare('SELECT * FROM villes_france WHERE villes_france.ville_id = ? LIMIT 1');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
