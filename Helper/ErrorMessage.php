<?php

namespace Helper;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exceptions\ValidateEntityException;
use Helper\RegisterErrorMessage;

class ErrorMessage {
    public static function get($message, RegisterErrorMessage $customMessage = null) {
        return ($message instanceof \Exception) 
            ? self::ex($message, $customMessage) 
            : '';
    }

    private static function ex($message, RegisterErrorMessage $customMessage = null) {
        if(!empty($customMessage)) {
            foreach ($customMessage->get() as $item) {
                if($message instanceof $item->ex) {                    
                    if($message  instanceof UniqueConstraintViolationException) {
                        $erro = $item->message;                    

                        return $item->message;
                    }
                    
                    $erro = $message->getErro();                                                                

                    return str_replace('<field_name>', key($erro[0]), $item->message);
                }
            }
        }

        if($message  instanceof ValidateEntityException) {
            return $message->getErro();
        }

        if($message  instanceof \Exception) {
            return '';
        }
    }
}