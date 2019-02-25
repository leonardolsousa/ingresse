<?php
namespace App\Models\Entity;
/**
 * @Entity @Table(name="users")
 **/
class Users {
    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id;
    /**
     * @var string
     * @Column(type="string") 
     */
    public $name;
    /**
     * @var string
     * @Column(type="string") 
     */
    /**
     * @return int id
     */
    public function getId(){
        return $this->id;
    }
    /**
     * @return string name
     */
    public function getName(){
        return $this->name;
    }
    /**
     * @return App\Models\Entity\Users
     */
    public function setName($name){
        if (!$name && !is_string($name)) {
            throw new \InvalidArgumentException("Name is required", 400);
        }
        $this->name = $name;
        return $this;  
    }
}