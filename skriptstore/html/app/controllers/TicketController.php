<?php 

use Phalcon\Mvc\Controller; 

class TicketController extends Controller 
{ 

public function indexAction($id) 
{ 
if ($this->session->has("auth")) { 
$data['auth'] = true; 

$user = Users::FindFirst($this->session->get("id")); 

$this->view->user = $user; 
} else { 
$data['auth'] = false; 
} 
$ticket = Tickets::FindFirst($id); 
$placesnow = count(TicketsBet::Find("ticket_id = $ticket->id AND round_id = $ticket->round")); 
$places = [];
            foreach (TicketsBet::Find(["round_id = $ticket->round AND ticket_id = $ticket->id"]) as $l) {
                $user = Users::FindFirst($l->user_id);
                $places[$l->place] = ['user' => $user->id,'time' => $l->time,'place' => $l->place];
            }

if ($ticket) { 

$this->view->title = $ticket->name; 

$this->view->ticket = $ticket; 
$this->view->places = $places; 
$this->view->placesnow = $placesnow; 

$this->view->pick("site/pages/ticket/ticket"); 
} else { 
$this->response->redirect(); 
} 
} 

}