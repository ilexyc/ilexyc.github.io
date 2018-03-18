<?php

use Phalcon\Mvc\Controller;

class PagesController extends Controller
{

    public function onConstruct()
    {
        if ($this->session->has("auth")) {
            $data["auth"] = true;

            $user = Users::FindFirst($this->session->get("id"));

            $this->view->user = $user;
        } else {
            $data["auth"] = false;
        }
    }

    public function indexAction()
    {
        $this->response->redirect();
    }

    /**
     * Вывод страниц сайта
     */

    public function faqAction()
    {
        $this->view->title = "FAQ";

        $this->view->faq = true;

        $this->view->pick("site/pages/faq/faq");
    }

    public function guaranteesAction()
    {
        $this->view->title = "Гарантии";

        $this->view->guarantees = true;

        $this->view->pick("site/pages/guarantees/guarantees");
    }

    public function reviewsAction()
    {
        $this->view->title = "Отзывы о сайте";

        $this->view->reviews = true;

        $this->view->pick("site/pages/reviews/reviews");
    }
    public function contestsAction()
    {
        $this->view->title = "Конкурс";

        $this->view->contests = true;

        $this->view->pick("site/pages/contests/contests");
    }
    public function termsAction()
    {
        $this->view->title = "Пользовательское соглашение";

        $this->view->terms = true;

        $this->view->pick("site/pages/terms/terms");
    }

    public function supportAction()
    {
        $this->view->title = "Техническая поддержка";

        $this->view->support = true;

        $this->view->pick("site/pages/support/support");
    }

}