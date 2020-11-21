<?php

namespace Entity;

use Exceptions\ValidateEntityException;

use Doctrine\ORM\Mapping as ORM;

use Helper\ErrorMessage;
use Helper\RegisterErrorMessage;
use Helper\Validate;

use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class ValidateEntity {     
    /**     
     * @Serializer\Exclude()
     */
    private $errorMessage;

    private $className;
    
    abstract public function initializeErrorMessage();

    public function __construct($className) {
        $this->errorMessage = new RegisterErrorMessage();
        $this->className = $className;

        $this->initializeErrorMessage();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate()
     * @throws
     */
    public function validate() {
        $validate = new Validate($this);

        if(!$validate->validate()) {            
            $ex = new ValidateEntityException('');
            $ex->setError($validate->getError());
            throw $ex;
        }
    }

    /**
     * Get the value of mensagemErro
     */ 
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function addErrorMessage($classeException, $message) {
        $this->errorMessage->add($classeException, $message);
    }

    public function getError($ex) {        
        return ErrorMessage::get($ex, $this->getErrorMessage());
    }

    public function getClassName() {        
        return get_class($this->className);
    }
}