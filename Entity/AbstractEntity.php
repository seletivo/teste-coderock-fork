<?php

namespace Entity;

use Entity\EntityValidar;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;

/** 
 * @ORM\MappedSuperclass 
 */
abstract class AbstractEntity extends EntityValidar {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Type("integer")
     */
    private $id;                   

    public function __construct($classe) {
        parent::__construct($classe);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }            
}