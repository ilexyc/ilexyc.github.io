<?php

use Phalcon\Mvc\Controller;

class AuthController extends Controller
{

    public function indexAction()
    {
        if ($this->session->has("auth")) {
            $this->response->redirect();
        }

        if ($_POST["token"]) {

            $request = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
            $user_info = json_decode($request, true);

            $user = Users::FindFirst("uid = " . $user_info['uid']);

            if($user_info['verified_email']) {

                if ($user !== false || json_encode($user) !== 'false') {
                    $user = Users::UpdateObject($user, [
                        "name" => $user_info['last_name'] . ' ' . $user_info['first_name'],
                        "photo" => $user_info['photo_big'],
                        "sex" => $user_info['sex'],
                        "date" => $user_info['bdate'],
                        "email" => $user_info['email']
                    ]);
                } else {
                    $user = Users::CreateObject([
                        "name" => $user_info['last_name'] . ' ' . $user_info['first_name'],
                        "photo" => $user_info['photo_big'],
                        "sex" => $user_info['sex'],
                        "date" => $user_info['bdate'],
                        "uid" => $user_info['uid'],
                        "email" => $user_info['email'],
                        "referral_code" => substr(md5(uniqid()), 0, 10)
                    ]);
                }

                $user->save();

                $this->session->set("id", $user->id);
                $this->session->set("auth", true);

                $this->response->redirect();
            } else {
                $this->response->redirect();
            }
        } else {
            $this->response->redirect();
        }
    }

    public function logoutAction()
    {
        if (!$this->session->has("auth")) {
            $this->response->redirect();
        }

        $this->session->destroy();
        $this->response->redirect();

        $this->session->set("auth", false);
    }

}