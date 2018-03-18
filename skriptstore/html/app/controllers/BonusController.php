<?php 

use Phalcon\Mvc\Controller; 

class BonusController extends Controller 
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

$top = $this->helper->getTopUsers(); 
$case = Cases::FindFirst(13); 
$items = explode(",", $case->items); 

if ($case) { 

$this->view->title = $case->name; 

$this->view->top = $top; 
$this->view->items_reverse = array_reverse($items, true); 
$this->view->items = $items; 
$this->view->case = $case; 

$this->view->pick("site/pages/bonus/bonus"); 
} else { 
$this->response->redirect(); 
} 
} 
}