<?php

use \Phalcon\Mvc\Model;

class Send extends Model {

    public $id;

    public $user_id;

    public $drop_id;

    public $name;

    public $telephone;

    public $email;

    public $address;

    public $gift_image;

    public $gift_name;

    public $gift_code;

    public $status;

    public $time;

    public function initialize() {
        $this->setSource("send");
    }

    public static function CreateObject($data) {
        $object = new Send();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}