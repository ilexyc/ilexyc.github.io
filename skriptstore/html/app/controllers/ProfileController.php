<?php

use Phalcon\Mvc\Controller;

class ProfileController extends Controller
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

        $profile = Users::FindFirst($id);

        $drops = Drops::Find([
            "user_id = $id",
            "order" => "id DESC",
            "limit" => 30
        ]);

        if ($profile) {
            $this->view->drops = $drops;
            $this->view->profile = $profile;

            $this->view->pick("site/pages/profile/profile");
        } else {
            $this->response->redirect();
        }
    }

}