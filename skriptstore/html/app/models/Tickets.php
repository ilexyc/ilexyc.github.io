<?php

use \Phalcon\Mvc\Model;

class Tickets extends Model {

    public $id;

    public $name;
	
	public $round;

    public $price;

    public $places;

    public $winsum;
	
	public $winner_id;
	
	public $winner_ticket;


    public function initialize() {
        $this->setSource("tickets");
    }

    public static function CreateObject($data) {
        $object = new Tickets();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}