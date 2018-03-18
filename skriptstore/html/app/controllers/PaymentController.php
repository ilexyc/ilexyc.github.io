<?php

use Phalcon\Mvc\Controller;

class PaymentController extends Controller
{

    public $user;

    /**
     * Проверка авторизации
     */

    public function onConstruct()
    {
        $this->user = Users::FindFirst($this->session->get("id"));
    }

    /**
     * Перенапрвление на оплату
     */

          

    public function indexAction()
    {
        if (!$this->session->has("auth")) {
            $this->response->redirect();
        }

        $transaction = Transactions::Find()->getLast();
        $transaction_id = rand(100000000,999999999);

        //$this->response->redirect("https://sci.interkassa.com/?ik_co_id=" . $this->config->interkassa->merchant_id . "&ik_pm_no=" . $transaction_id . "&ik_am=" . $_POST["amount"] . "&ik_desc=Пополнение баланаса на сумму " . $_POST["amount"] . " Рублей&ik_x_user=" . $this->user->id . "");
        $sign = md5($this->config->freekassa->merchant_id.':'.$_POST["amount"].':'.$this->config->freekassa->secret_key1.':'.$transaction_id);
        $this->response->redirect("http://www.free-kassa.ru/merchant/cash.php?m=" . $this->config->freekassa->merchant_id . "&oa=". $_POST["amount"] . "&o=" . $transaction_id . "&s=" . $sign . "&us_userid=" . $this->user->id);
        
    }

    /**
     * Вывод срелств с проекта
     */

    public function withdrawAction() {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }
        
        if ($_POST['amount'] >= $this->config->settings->min_withdraw && $_POST['amount'] <= $this->config->settings->max_withdraw) {
            if ($this->user->balance >= $_POST['amount']) {

                if ($_POST['type'] == 'webmoney') {

                    if (!preg_match('/R\d\d\d\d\d\d\d\d\d\d\d/', $_POST['purse'])) {
                        $data = [
                            "success" => false,
                            "message" => "Неверный номер счета WebMoney<br>Пример: R23884920195"
                        ];

                        exit(json_encode($data));
                    }

                    $service = 2;
                    $description = "Вывод средств на WebMoney " . $_POST['purse'];
                } elseif ($_POST['type'] == 'yandex') {

                    if (!preg_match('/4\d\d\d\d\d\d\d\d\d\d\d\d\d/', $_POST['purse'])) {
                        $data = [
                            "success" => false,
                            "message" => "Неверный номер счета Яндекс Денег<br>Пример: 41006643106801"
                        ];

                        exit(json_encode($data));
                    }

                    $service = 3;
                    $description = "Вывод средств на Яндекс.Деньги " . $_POST['purse'];
                } elseif ($_POST['type'] == 'qiwi') {

                    if (!preg_match('/\d\d\d\d\d\d\d\d\d\d\d/', $_POST['purse'])) {

                        $data = [
                            "success" => false,
                            "message" => "Неверный номер счета QIWI<br>Пример: 79001234567"
                        ];

                        exit(json_encode($data));
                    }

                    $service = 4;
                    $description = "Вывод средств на Qiwi " . $_POST['purse'];
                } else {
                    $data = [
                        "success" => false,
                        "message" => "Выбраный сервис не существует"
                    ];

                    exit(json_encode($data));
                }

                $this->user->balance -= $_POST['amount'];
                $this->user->save();

                $transaction = Transactions::CreateObject([
                    "user_id" => $this->user->id,
                    "service" => $service,
                    "cost" => - $_POST['amount'],
                    "description" => $description,
                    "status" => 0,
                    "time" => time()
                ]);

                $transaction->save();

                $data = [
                    "success" => true,
                    "balance" => $this->user->balance,
                    "message" => "Запрос на вывод средств успешно создан.<b>В течении 24 часов мы его обработаем</b>"
                ];

                return json_encode($data);

            } else {
                $data = [
                    "success" => false,
                    "message" => "Недостаточно средств на балансе"
                ];

                return json_encode($data);
            }
        } else {
            $data = [
                "success" => false,
                "message" => "Минимальная сумма вывода <b>" . $this->config->settings->min_withdraw . "</b> р<br>Максимальная сумма вывода <b>" . $this->config->settings->max_withdraw . "</b> р"
            ];

            return json_encode($data);
        }
    }

    public function checkfreekassaAction() {
        if (!isset($_POST)) {
            return false;
        }

        $MERCHANT_ID = $_POST['MERCHANT_ID'];
        $AMOUNT = $_POST['AMOUNT'];
        $intid = $_POST['intid'];
        $MERCHANT_ORDER_ID = $_POST['MERCHANT_ORDER_ID'];
        $SIGN = $_POST['SIGN'];
        $us_userid = $_POST['us_userid'];


        $hash = md5($MERCHANT_ID.':'.$AMOUNT.':'.$this->config->freekassa->secret_key2.':'.$MERCHANT_ORDER_ID);

        if ($hash == $SIGN) {


            $transaction = Transactions::CreateObject([
                "ik_pm_no" => $intid,
                "user_id" => $us_userid,
                "service" => 1,
                "cost" => $AMOUNT,
                "description" => "Пополнение баланса через FreeKassa",
                "status" => 1,
                "time" => time()
            ]);

            $transaction->save();

            $user = Users::FindFirst($us_userid);

            $user->balance += $AMOUNT;
            $user->save();

            if ($user->referral != NULL) {
                $ref = $user->referral;
                $user = Users::FindFirst("referral_code='$ref'");
                $user->balance += $AMOUNT * 0.1;
                $user->save();
            }

            echo('YES');


        }

        return false;

        

    }

    /**
     * Проверка оплаты зачисление баланаса на сайт
     */

    public function checkAction()
    {

        if ($_POST['ik_co_id'] != $this->config->interkassa->merchant_id) {
            return false;
        }

        $ik_key = ($_POST['ik_pw_via'] == 'test_interkassa_test_xts') ? $this->config->interkassa->test_key : $this->config->interkassa->secret_key;

        /**
         * Формированние массива для создание SIGN
         */

        $data = array();

        foreach ($_POST as $key => $value) {
            if (!preg_match('/ik_/', $key))
                continue;
            $data[$key] = $value;
        }

        $ik_sign = $data['ik_sign'];

        unset($data['ik_sign']);

        ksort($data, SORT_STRING);
        array_push($data, $ik_key);

        $signString = implode(':', $data);
        $sign = base64_encode(md5($signString, true));

        if ($sign === $ik_sign && $data['ik_inv_st'] == 'success') {

                        $transaction = Transactions::FindFirst("ik_pm_no = " . $_POST['ik_pm_no']);

            if (!$transaction) {
                $transaction = Transactions::CreateObject([
				    "ik_pm_no" => $_POST['ik_pm_no'],
                    "user_id" => $_POST['ik_x_user'],
                    "service" => 1,
                    "cost" => $_POST['ik_am'],
                    "description" => "Пополнение баланса через InterKassa",
                    "status" => 1,
                    "time" => time()
                ]);

                $transaction->save();

                $user = Users::FindFirst($_POST['ik_x_user']);

                $user->balance += $_POST['ik_am'];
                $user->save();

                if ($user->referral != NULL) {
                    $ref = $user->referral;
                    $user = Users::FindFirst("referral_code='$ref'");
                    $user->balance += $_POST['ik_am'] * 0.1;
                    $user->save();
                }
            }

        } else {
            return false;
        }
    }

    /**
     * Успешная оплата
     */

public function getIP() {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
       return $_SERVER['REMOTE_ADDR'];
    }

    public function payf_fail(Request $request) {
        $order_id = $request->MERCHANT_ORDER_ID;
        return view('pages/msg', array(
            'title' => 'Ошибка',
            'body' => 'Заказ № '.$order_id.' не выполнен'
        ));
    }

    public function payf_success(Request $request) {
        $order_id = $request->MERCHANT_ORDER_ID;
        $free_id = $request->intid;
        $order = \DB::table('orders')->where('id_order', $order_id)->first();
        return view('pages/msg', array(
            'title' => 'Пополнение баланса успешно',
            'body' => "Заказ №".$order->id_order." соответствует номеру заказа во free-kassa ".$free_id." и ".$order->sum_pay." добавлена на ваш баланс"
        ));
    }

    public function payf_index(Request $request) {
        $sum = $request->sum;
        $u = $request->id;
        
        if (empty($sum) || empty($u)) {die("Приветики :) Как сам?");}

        $mrh_login = "45424";
        $mrh_pass1 = "tb70t3xr";

        $result = \DB::table('orders')->insertGetId([
            'userid' => $u,
            'sum_pay' => $sum,
            'status' => 0,
            'dateCreate' => time()
        ]);
        $crc = md5("$mrh_login:$sum:$mrh_pass1:$result");
        return redirect('http://www.free-kassa.ru/merchant/cash.php?m=' . $mrh_login . '&oa=' . $sum . '&o='.$result.'&s=' . $crc);
    }

    public function payf_notif(Request $request) {
        // регистрационная информация (пароль #2)
        // registration info (password #2)
        $mrh_login = "45424";
        $mrh_pass2 = "9gzj8aor";

        if (!in_array($this->getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189'))) {
            die("hacking attempt!");
        }

        $merchant_id = $request->MERCHANT_ID;
        $amount = $request->AMOUNT;
        $free_id = $request->intid;
        $order_id = $request->MERCHANT_ORDER_ID;
        $user_email = $request->P_EMAIL;
        $user_phone = $request->P_PHONE ? $request->P_PHONE : "none";
        $cur_id = $request->CUR_ID;
        $free_sign = $request->SIGN;

        $sign = md5($merchant_id.':'.$amount.':'.$mrh_pass2.':'.$order_id);
        if ($sign != $free_sign) {
            die('wrong sign');
        }

        $order = \DB::table('orders')->where('id_order', $order_id)->first();

        if ($amount != $order->sum_pay) {
            die("hacking attempt!");
        }
        $money = $order->sum_pay;
        $userid = $order->userid;
        $result = $order->result;
        if ($result == '0') {
            \DB::table('orders')->where('id_order', $order_id)->update([
                'status' => 2,
                'dateComplete' => time()
            ]);
            \DB::table('users')->where('id', $userid)->increment('money',$money);
            \DB::table('orders_logs')->insert([
                'userid' => $userid,
                'sum_pay' => $money,
                'order_id' => $order_id,
                'date' => time(),
                'comment' => "Order=$order_id amount=$amount free_id=$free_id User_email=$user_email user_phone=$user_phone CUR_ID=$cur_id ALL OK"
            ]);
            \DB::table('last_vvod')->insert([
                'user' => $userid,
                'price' => $money
            ]);
        } else {
            \DB::table('orders_logs')->insert([
                'userid' => $userid,
                'sum_pay' => $money,
                'order_id' => $order_id,
                'date' => time(),
                'comment' => "Order=$order_id amount=$amount free_id=$free_id User_email=$user_email user_phone=$user_phone CUR_ID=$cur_id hacking attempt!!!"
            ]);
        }
        echo "YES";
        exit(0);
    }


    public function successAction()
    {
        $this->view->user = $this->user;

        $this->view->pick("site/pages/payment/success");
    }

    /**
     * Не успешная оплата
     */

    public function failAction()
    {
        $this->view->user = $this->user;

        $this->view->pick("site/pages/payment/fail");
    }

}