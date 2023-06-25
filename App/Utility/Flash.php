<?php

namespace App\Utility;

class Flash
{
    public static function danger($message)
    {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => $message
        ];
    }


    public static function success($message)
    {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => $message
        ];
    }

 
}
