<?php

use \Phalcon\Mvc\Model;

class TicketsBet extends Model {

    public $id;

    public $ticket_id;

    public $round_id;

    public $place;
	
	public $user_id;



    public function initialize() {
        $this->setSource("ticketsbet");
    }

    public static function CreateObject($data) {
        $object = new TicketsBet();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}