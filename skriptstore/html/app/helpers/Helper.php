<?php

use Phalcon\Tag;

class Helper extends Tag
{

    /**
     * Получение информации о веще по ID
     */

    public function getItemInfo($id)
    {

        $item = Items::FindFirst($id);

        return $item;
    }

    /**
     * Получение информации о цифровом предмете
     */

    public function getDigitalItemInfo($case_id)
    {

        $case = Cases::FindFirst($case_id);
        $items = explode(",", $case->items);

        foreach ($items as $item) {

            $item = Items::FindFirst($item);

            if ($item->digital) {
                return $item;
            }
        }
    }

    /**
     * Получение рандомного гифт кода
     */

    public function getDigital($type)
    {
        $digital = Digitals::FindFirst("name='$type'");

        return $digital;
    }

    /**
     * Получении информации о предмете в отправке
     */

    public function getSendItem($drop_id)
    {
        $send = Send::FindFirst("drop_id='$drop_id'");

        return $send;
    }

    /**
     * Получение информации из выпаденний
     */

    public function getDropInfo($drop_id)
    {
        $drop = Drops::FindFirst($drop_id);

        return $drop;
    }

/**
     * Получение суммы выйгрышей пользователя
     */
 
    public function getUserDropPrice($user_id)
    {
        $price = Drops::sum([
            "column" => "price",
            "user_id = $user_id"
        ]);
 
        return $price;
    }
 
    /**
     * Получение самых везучих игроков
     */
 
    public function getTopUsers()
    {
 
        $drops = Drops::Find([
            "group" => "user_id"
        ]);
 
        /**
         * Новый массива для сортировки
         */
 
        $drops_array = [];
 
        foreach ($drops as $drop) {
           $drop->price = $this->getUserDropPrice($drop->user_id);
            $drops_array[] = $drop;
        };
 
        /**
         * Сортировка массива с игроками
         */
 
        uasort($drops_array, function ($a, $b) {
            if ($a->price == $b->price) return 0;
            return ($a->price < $b->price) ? 1 : -1;
        });
 
        return $drops_array;
    }

    /**
     * Получекние количества открытых кейсов
     */

    public function getAllDrops()
    {

        $drops = Drops::Find();

        return count($drops);
    }

    /**
     * Получение количества кейсов открытых сегодня
     */

    public function getTodayDrops()
    {
        $drops = Drops::count([
            "DATE_FORMAT(FROM_UNIXTIME(time), '%d%m%Y') = DATE_FORMAT(CURDATE(), '%d%m%Y')"
        ]);

        return $drops;
    }

    /**
     * Получение всех зарегестрированных пользователей
     */

    public function getAllUsers()
    {

        $users = Users::Find();

        return count($users);
    }

    /**
     * Получение пользователей зарегестрированных по реферальному коду
     */

    public function getUsersReferrals($code)
    {

        $users = Users::Find("referral='$code'");

        return count($users);
    }

    /**
     * Получение пользователя по реферальному коду
     */

    public function getUserReferral($code)
    {

        $user = Users::FindFirst("referral='$code'");

        return count($user);
    }

    /**
     * Получение информации о пользователе по ID
     */

    public function getUserInfo($id)
    {

        $user = Users::FindFirst($id);

        return $user;
    }

    /**
     * Получение общего банка пользователя
     */

    public function getUserBank($id)
    {

        $drops = Drops::Find("user_id='$id'");
        $bank = 0;

        foreach ($drops as $drop) {
            $bank += $drop->price;
        }

        return number_format($bank);
    }

    /**
     * Полученеи количества открытых кейсов пользователем
     */

    public function getUserCases($id)
    {

        $drops = Drops::Find("user_id='$id'");

        return number_format(count($drops));
    }

    /**
     * Получение информации о кейсе по ID
     */

    public function getCaseInfo($id)
    {

        $case = Cases::FindFirst($id);

        return $case;
    }

    /**
     * Получение минимального и максимального выйгрыша
     */

    public function getMinMaxCase($id)
    {

        $case = Cases::FindFirst($id);
        $items = explode(",", $case->items);

        $data = (object)[
            "min" => 0,
            "max" => 0
        ];

        foreach ($items as $item) {

            $item = Items::FindFirst($item);

            if (!$data->min && !$data->max) {
                $data->max = $item->cost;
                $data->min = $item->cost;
            }

            if ($data->min > $item->cost) {
                $data->min = $item->cost;
            }

            if ($data->max < $item->cost) {
                $data->max = $item->cost;
            }

        }

        return $data;
    }

    /**
     * Получение выйгрыша с кейса
     */

    public function getBankCase($id)
    {

        $drops = Drops::Find("case_id='$id'");
        $bank = 0;

        foreach ($drops as $drop) {
            $item = Items::FindFirst($drop->item_id);
            $bank += $item->cost;
        }

        return number_format($bank);

    }

    /**
     * Получение сегоднешних вводов
     */

    public function getTodayDeposits()
    {
        $deposits = Transactions::Find([
            "DATE_FORMAT(FROM_UNIXTIME(time), '%d%m%Y') = DATE_FORMAT(CURDATE(), '%d%m%Y')"
        ]);

        $bank = 0;

        foreach ($deposits as $deposit) {
            if ($deposit->cost > 0) {
                $bank += $deposit->cost;
            }
        }

        return $bank;
    }
	 public function getAllDeposits()
    {
        $deposits = Transactions::Find();

        $bank = 0;

        foreach ($deposits as $deposit) {
            if ($deposit->cost > 0) {
                $bank += $deposit->cost;
            }
        }

        return $bank;
    }
public function budget()
    {
        $historys = History::Find();

        $budget =  round($this->getAllDeposits()*0.98 - $this->getNowWithdraws() - $this->getAllWithdraws()  - $this->getOverBalance()) ;

        foreach ($historys as $history) {
              //  $budget += $history->cost;
        }

        return $budget;
    }
	public function getOverBalance()
    {
        $users = Users::Find("admin=0");

        $bank = 0;

        foreach ($users as $user) {
			if ($user->balance >=  100) {
                $bank += $user->balance;
			}

        }

        return $bank;
    }
    /**
     * Получение сегоднешних выводов
     */

    public function getTodayWithdraws()
    {
        $deposits = Transactions::Find([
            "DATE_FORMAT(FROM_UNIXTIME(time), '%d%m%Y') = DATE_FORMAT(CURDATE(), '%d%m%Y')"
        ]);

        $bank = 0;

        foreach ($deposits as $deposit) {
            if ($deposit->cost < 0 && $deposit->status ==  1) {
                $bank -= $deposit->cost;
            }
        }

        return $bank;
    }
	public function getNowWithdraws()
    {
        $deposits = Transactions::Find([
            "cost < 0"
        ]);

        $bank = 0;

        foreach ($deposits as $deposit) {
            if ($deposit->status ==  0) {
                $bank -= $deposit->cost;
            }
        }

        return $bank;
    }
	public function Withdraw()
    {
        $deposits = Transactions::Find([
            "cost < 0"
        ]);

        $bank = 0;

        foreach ($deposits as $deposit) {
                $bank -= $deposit->cost;
        }

        return $bank;
    }
public function getAllWithdraws()
    {
		$deposits = Transactions::Find([
            "cost < 0"
        ]);

        $bank = 0;

        foreach ($deposits as $deposit) {
            if ($deposit->status == 1) {
                $bank -= $deposit->cost;
            }
        }

        return $bank;
    }

	public function getTotalBalance()
    {
        $users = Users::Find("admin='0'");

        $bank = 0;

        foreach ($users as $user) {
                $bank += $user->balance;
        }

        return $bank;
    }
	public function getRefBal($code)
    {

        $users = count(Users::Find("referral!='0'"))*10;

        return $users;
    }
    /**
     * Получение шанса (Математика це Пиздец)
     */

    public function getChance($percent)
    {
        $rand = mt_rand(1, 100);

        if ($rand <= $percent) {
            return true;
        }
    }

    /**
     * Функции перевода времени
     */

    public function unixToString($format, $timestamp)
    {
        $locale = setlocale(LC_TIME, 'ru_RU.UTF-8', 'Rus');
        $unix = strftime($format, $timestamp);

        if (strpos($locale, '1251') !== false) {
            return mb_convert_case(iconv('cp1251', 'utf-8', $unix), MB_CASE_TITLE, "UTF-8");
        } else {
            return mb_convert_case($unix, MB_CASE_TITLE, "UTF-8");
        }
		
    }
	public function curl($url) 
{ 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
$output = curl_exec($ch); 
curl_close($ch); 
return $output; 
}
}