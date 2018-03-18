<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        if ($this->session->has("auth")) {
            $data["auth"] = true;

            $user = Users::FindFirst($this->session->get("id"));

            $this->view->user = $user;
        } else {
            $data["auth"] = false;
        }

        $top = $this->helper->getTopUsers();
		$tickets = Tickets::Find();
        
        $cases = Cases::Find([
		    "id != 13",
            "order" => "position"
        ]);

        $this->view->top = $top;
        $this->view->cases = $cases;
		$this->view->tickets = $tickets;
        $this->view->index = true;

        $this->view->pick("site/pages/index/index");
    }

}