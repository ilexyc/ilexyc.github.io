<?php 

use Phalcon\Mvc\Controller; 

class DiceController extends Controller 
{ 

public function indexAction() 
{ 
if ($this->session->has("auth")) { 
$data['auth'] = true; 

$user = Users::FindFirst($this->session->get("id")); 

$this->view->user = $user; 
} else { 
$data['auth'] = false; 
} 
$this->view->pick("site/pages/dice/dice");  
} 

}