<?php

use Phalcon\Mvc\Controller;

class AccountController extends Controller
{

    public function indexAction()
    {
        if ($this->session->has("auth")) {
            $data['auth'] = true;

            $user = Users::FindFirst($this->session->get("id"));

            $this->view->user = $user;
       

        $drops = Drops::Find([
            "order" => "id DESC",
            "user_id = $user->id"
        ]);

        $history = History::Find([
            "order" => "id DESC",
            "user_id = $user->id"
        ]);


        $transactions = Transactions::Find([
            "order" => "id DESC",
            "user_id = $user->id"
        ]);

        $send = Send::Find([
            "order" => "id DESC",
            "user_id = $user->id"
        ]);

        $this->view->title = "Личный кабинет";

        $this->view->send = $send;
        $this->view->transactions = $transactions;
        $this->view->drops = $drops;
        $this->view->history = $history;
        $this->view->pick("site/pages/account/account");
} else{
	 $this->response->redirect("/e/404");
}
    }

}