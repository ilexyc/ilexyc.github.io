<?php

use \Phalcon\Mvc\Model;

class Dice extends Model {

    public $id;

    public $user_id;
	
	public $price;

    public $numbers;

    public $winner_number;



    public function initialize() {
        $this->setSource("dice");
    }

    public static function CreateObject($data) {
        $object = new Dice();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}