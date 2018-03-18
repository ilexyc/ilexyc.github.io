<?php

use \Phalcon\Mvc\Model;

class Users extends Model {

    public $id;

    public $name;

    public $photo;

    public $sex;

    public $admin;

    public $date;

    public $email;

    public $balance;

    public $uid;   
    
    public $referral;
    
    public $referral_code;

    public function initialize() {
        $this->setSource("users");
    }

    public static function CreateObject($data) {
        $object = new Users();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}