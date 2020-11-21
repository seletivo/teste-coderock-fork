<?php

namespace Entity;

use Entity\ValidateEntity;

use Doctrine\ORM\Mapping as ORM;

use Helper\ValidateEntityException;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/** 
 * @ORM\MappedSuperclass 
 */
abstract class AbstractEntity extends ValidateEntity {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Type("integer")
     */
    private $id;                   

    public function __construct($className) {
        parent::__construct($className);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }   
    
    public function initializeErrorMessage() {           
        $this->addErrorMessage(ValidateEntityException::class, 'Campo [<field_name>] obrigatório!');                
    }
}