<?php

use Phalcon\Mvc\Controller;

class AdminController extends Controller
{

    public $user;

    /**
     * Проверка авторизации
     */

    public function onConstruct()
    {
        if ($this->session->has("auth")) {

            $this->user = Users::FindFirst($this->session->get("id"));

            if (!$this->user->admin) {
                $this->response->redirect("/e/404");
            }
        } else {
            $this->response->redirect("/e/404");
        }
    }

    /**
     * Главная страница
     */

    public function indexAction()
    {
        $history = History::Find([
            "DATE_FORMAT(FROM_UNIXTIME(time), '%d%m%Y') = DATE_FORMAT(CURDATE(), '%d%m%Y')"
        ]);

        $this->view->index = true;

        $this->view->history = $history;

        $this->view->pick("admin/pages/index/index");
    }
/**
     * Страница билетов
     */

    public function ticketsAction()
    {
        $tickets = Tickets::Find();

        $this->view->tickets = $tickets;

        $this->view->pick("admin/pages/tickets/tickets");
    }
    /**
     * Страница кейсов
     */

    public function casesAction()
    {
        $directory = '../public/templates/site/dist/img/cases';
        $cases = Cases::Find();

        $this->view->images = array_diff(scandir($directory), array('..', '.'));
        $this->view->cases = $cases;

        $this->view->pick("admin/pages/cases/cases");
    }

    /**
     * Страница вещей
     */

    public function itemsAction() 
    {
        $directory = '../public/templates/site/dist/img/cases';
        $items = Items::Find();

        $this->view->images = array_diff(scandir($directory), array('..', '.'));
        $this->view->items = $items;

        $this->view->pick("admin/pages/items/items");
    }

    /**
     * Страница пользователей
     */

    public function usersAction()
    {
        $users = Users::Find();

        $this->view->users = $users;

        $this->view->pick("admin/pages/users/users");
    }

    /**
     * Страница транзакций
     */

    public function transactionsAction()
    {
        $transactions = Transactions::Find([
            "cost > 0"
        ]);

        $this->view->transactions = $transactions;

        $this->view->pick("admin/pages/transactions/transactions");
    }

    /**
     * Страница транзакций
     */

    public function digitalsAction()
    {
        $digitals = Digitals::Find();

        $this->view->digitals = $digitals;

        $this->view->pick("admin/pages/digitals/digitals");
    }

    /**
     * Страница отправки вещей
     */

    public function sendAction()
    {
        $send = Send::Find();

        $this->view->send = $send;

        $this->view->pick("admin/pages/send/send");
    }

    /**
     * Страница зявок на вывод
     */

    public function withdrawsAction()
    {
        $withdraws = Transactions::Find([
            "cost < 0"
        ]);

        $this->view->withdraws = $withdraws;

        $this->view->pick("admin/pages/withdraws/withdraws");
    }
}