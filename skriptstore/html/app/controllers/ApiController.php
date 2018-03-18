<?php

use Phalcon\Mvc\Controller;

class ApiController extends Controller
{

    public $user;

    /**
     * Проверка авторизации
     */

    public function onConstruct()
    {
        $this->user = Users::FindFirst($this->session->get("id"));
    }

    public function indexAction()
    {
        $this->response->redirect();
    }

    /**
     * Активация реферальнного кода
     */

    public function referralAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }

        if ($_POST["code"]) {

            $user_referral = Users::FindFirst("referral_code='{$_POST["code"]}'");

            if ($_POST["code"] != $this->user->referral_code) {
                if ($user_referral) {
                    if (!$this->user->referral) {

                        $history = History::CreateObject([
                            "user_id" => $this->user->id,
                            "description" => "Погашение партнерского кода " . $_POST["code"],
                            "cost" => 10,
                            "time" => time()
                        ]);

                        $history->save();

                        $this->user->referral = $_POST["code"];
                        $this->user->balance += 10;

                        $this->user->save();

                        $this->JsonMessage(true, "Вам успешно зачисленно 10 кредитов");
                    } else {
                        $this->JsonMessage(false, "Вы уже погасили код");
                    }
                } else {
                    $this->JsonMessage(false, "Данного кода не существует");
                }
            } else {
                $this->JsonMessage(false, "Вы не можете использовать свой код");
            }
        } else {
            $this->JsonMessage(false, "Вы не ввели код");
        }
    }
/**
     * Игра в кость
     */

    public function betDiceAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }
        if ($_POST["number"]&& $_POST["sum"]) {
                if ($this->user->balance >= $_POST["sum"]) {
					if ($_POST["sum"]>=15) {
						$this->user->balance -= $_POST["sum"];
					$this->user->save();
                    $history = History::CreateObject([
                        "user_id" => $this->user->id,
                        "description" => "Прокрутил кость «" . $_POST["number"] .  "»",
                        "cost" => - $_POST["sum"],
                        "time" => time()
                    ]);
                    $history->save();

                    $dice = Dice::CreateObject([
                        "user_id" => $this->user->id,
                        "price" => $_POST["sum"],
						"numbers" => $_POST["number"],
						"winner_number" => rand(1, 6)
                    ]);
                    $dice->save();
					$dice = Dice::FindFirst(["user_id" => $this->user->id,"order" => "id DESC", ]);	
					if($_POST["number"] == $dice->numbers){
					if($dice->numbers == $dice->winner_number){
						$this->user->balance += $_POST["sum"]*6;
						$this->user->save();
					} elseif($dice->numbers == 7 || $dice->numbers == 8){
						if($dice->numbers == 7){
							if($dice->winner_number == 1 || $dice->winner_number == 2 || $dice->winner_number == 3){
								$this->user->balance += $_POST["sum"]*2;
								$this->user->save();
							} 	
						}
						if($dice->numbers == 8){
							if($dice->winner_number == 4 || $dice->winner_number == 5 || $dice->winner_number == 6){
								$this->user->balance += $_POST["sum"]*2;
								$this->user->save();
							} 	
						}
					}
					}
					
																	
                    $data = [
                        "success" => true,
                        "balance" => $this->user->balance,
						"number" =>  $dice->winner_number
                    ];
                    return json_encode($data);
					} else{
						 $this->JsonMessage(false, "Нельзя ставить меньше 15 рублей");
					}

                } else {
                    $this->JsonMessage(false, "У вас недостаточно средств");
                }

        } else {
            $this->JsonMessage(false, "Вы не передали необходимые параметры для занятия места");
        }
    }
/**
     * Занимаем место
     */

    public function setPlaceAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }

        if ($_POST["id"] && $_POST["round"] && $_POST["user"] && $_POST["place"]) {

            $ticket = Tickets::FindFirst($_POST["id"]);

            if ($ticket) {
                if ($this->user->balance >= $ticket->price) {
					$place = TicketsBet::FindFirst("round_id = $ticket->round AND ticket_id = $ticket->id AND place = ". $_POST['place'] ."");
					if (!$place) {

                    $history = History::CreateObject([
                        "user_id" => $this->user->id,
                        "description" => "Занял билет «" . $ticket->name .  "»",
                        "cost" => - $ticket->price,
                        "time" => time()
                    ]);

                    $history->save();

                    $ticketsbet = TicketsBet::CreateObject([
                        "user_id" => $this->user->id,
                        "ticket_id" => $_POST["id"],
                        "round_id" => $_POST["round"],
                        "place" => $_POST["place"],
                        "time" => time()
                    ]);
                    $ticketsbet->save();
					$this->user->balance -= $ticket->price;
					$this->user->save();
					$placesnow = count(TicketsBet::Find("ticket_id = $ticket->id AND round_id = $ticket->round")); 
					if ($placesnow >= $ticket->places) {
						$ticket->winner_ticket = rand(1, $ticket->places);
						$winner = TicketsBet::FindFirst("round_id = $ticket->round AND ticket_id = $ticket->id AND place = $ticket->winner_ticket");
						$ticket->winner_id = $winner->user_id;
						$ticket->round += 1;
						$ticket->save();
						$user = Users::FindFirst($winner->user_id);
						$user->balance += $ticket->winsum;
						$user->save();					
					}
					
					

                    $data = [
                        "success" => true,
                        "balance" => $this->user->balance,
						"user" => $this->user
                    ];
                    return json_encode($data);
					} else{
						$this->JsonMessage(false, "Это место уже занято. Обновите страницу");
					}

                } else {
                    $this->JsonMessage(false, "У вас недостаточно средств, чтобы занять это место");
                }
            } else {
                $this->JsonMessage(false, "Билет для открытия не найден");
            }
        } else {
            $this->JsonMessage(false, "Вы не передали необходимые параметры для занятия места");
        }
    }
    /**
     * Открытие кейса
     */

    public function openCaseAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }

        if ($_POST["case"] && isset($_POST["chance"])) {

            $case = Cases::FindFirst($_POST["case"]);

            if ($case) {
                if ($this->user->balance >= $case->price-$case->discount) {

                    $items_array = explode(",", $case->items);
                    $items = array();

                    foreach ($items_array as $item) {

                        $item = Items::FindFirst($item);

                        if ($item->digital) {

                            $digital = Digitals::FindFirst("name = '$item->digital' AND reserved = 0");

                            if ($digital) {
                                $items[] = $item;
                            }
                        } else {
                            $items[] = $item;
                        }
                    }

                    $case_price = $case->price - $case->discount;

                    if ($_POST["chance"] == 1) {
                        $case_price = $case_price + ($case_price / 100) * 10;
                        $case_description = " (+увеличитель шанса 10%)";
                    } elseif ($_POST["chance"] == 2) {
                        $case_price = $case_price + ($case_price / 100) * 20;
                        $case_description = " (+увеличитель шанса 20%)";
                    } elseif ($_POST["chance"] == 3) {
                        $case_price = $case_price + ($case_price / 100) * 30;
                        $case_description = " (+увеличитель шанса 30%)";
                    }

                    $this->user->balance -= $case_price;
                    $this->user->save();

                    shuffle($items);

                    
                    $random_item = null;

                    $limit = round($this->helper->getMinMaxCase($case->id)->max / $case_price) * 1.5;
                    $budget = round($this->helper->budget());
					if ($budget < $this->config->settings->profit){
						$drops = Drops::Find([
"case_id = $case->id",
"order" => "id DESC",
"limit" => $limit
]);

$drop_sum = 0;

foreach ($drops as $drop) {
$drop_sum += $drop->price;
}

if ($drop_sum <= $this->helper->getMinMaxCase($case->id)->max) {
foreach ($items as $item) {
if ($item->cost >= $case->price) {
$random_item = $item;
break;
}
}
} else {
foreach ($items as $item) {
if ($item->cost < $case->price) {
$random_item = $item;
break;
}
}
}
					} else{
						if ($this->helper->getChance($case->chance)) {
                    foreach ($items as $item) {
                       if ($item->cost > $case->price) {
                         $random_item = $item;
                          break;
                      }
                    }
                } else {
                     foreach ($items as $item) {
                         if ($item->cost < $case->price) {
                              $random_item = $item;
                              break;
                         }
                     }
                   }
					}


                    if ($random_item->digital) {

                        $digital = Digitals::FindFirst("name = '$item->digital' AND reserved = 0");

                        $digital->reserved = 1;
                        $digital->save();
                    }

                    $history = History::CreateObject([
                        "user_id" => $this->user->id,
                        "description" => "Открытие кейса «" . $case->name . $case_description . "»",
                        "cost" => - $case_price,
                        "time" => time()
                    ]);

                    $history->save();

                    $drop = Drops::CreateObject([
                        "user_id" => $this->user->id,
                        "case_id" => $_POST["case"],
                        "item_id" => $random_item->id,
                        "price" => $random_item->cost,
                        "time" => time()
                    ]);

                    $drop->save();

                    if (!$random_item->digital) {
                        $history = History::CreateObject([
                            "user_id" => $this->user->id,
                            "description" => "Приз «" . $random_item->name . "»",
                            "cost" => $item->cost,
                            "time" => time()
                        ]);

                        $history->save();

                        $this->user->balance += $item->cost;
                        $this->user->save();
                    }

                    $data = [
                        "success" => true,
                        "balance" => $this->user->balance,
                        "item" => $random_item
                    ];

                    return json_encode($data);

                } else {
                    $this->JsonMessage(false, "У вас недостаточно средств для открытия кейса");
                }
            } else {
                $this->JsonMessage(false, "Кейс для открытия не найден");
            }
        } else {
            $this->JsonMessage(false, "Вы не передали необходимые параметры для открытия кейса");
        }
    }
/**
     * Открытие бонус кейса
     */

    public function openBonusAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }

        if ($_POST["case"] && isset($_POST["chance"])) {

            $case = Cases::FindFirst($_POST["case"]);

            if ($case) {
                if ($this->helper->getUsersReferrals($this->user->referral_code) >= 0 ) {
					$number = 0;
                $historys = History::Find(["user_id = " .$this->user->id]);
				foreach ($historys as $history) {
            if ($history->cost ==  0) {
                $number += 1;
            }
        }

		if ($number == 0){
			                    $items_array = explode(",", $case->items);
                    $items = array();

                    foreach ($items_array as $item) {

                        $item = Items::FindFirst($item);

                        if ($item->digital) {

                            $digital = Digitals::FindFirst("name = '$item->digital' AND reserved = 0");

                            if ($digital) {
                                $items[] = $item;
                            }
                        } else {
                            $items[] = $item;
                        }
                    }

                    $case_price = $case->price - $case->discount;

                    if ($_POST["chance"] == 1) {
                        $case_price = $case_price + ($case_price / 100) * 10;
                        $case_description = " (+увеличитель шанса 10%)";
                    } elseif ($_POST["chance"] == 2) {
                        $case_price = $case_price + ($case_price / 100) * 20;
                        $case_description = " (+увеличитель шанса 20%)";
                    } elseif ($_POST["chance"] == 3) {
                        $case_price = $case_price + ($case_price / 100) * 30;
                        $case_description = " (+увеличитель шанса 30%)";
                    }

                    $this->user->balance -= $case_price;
                    $this->user->save();

                    shuffle($items);

                    
                    $random_item = null;

                    $limit = round($this->helper->getMinMaxCase($case->id)->max / $case_price) * 1.5;
						$drops = Drops::Find([
"case_id = $case->id",
"order" => "id DESC",
"limit" => $limit
]);

$drop_sum = 0;

foreach ($drops as $drop) {
$drop_sum += $drop->price;
}

if ($drop_sum <= $this->helper->getMinMaxCase($case->id)->max) {
foreach ($items as $item) {
if ($item->cost >= $case->price) {
$random_item = $item;
break;
}
}
} else {
foreach ($items as $item) {
if ($item->cost < $case->price) {
$random_item = $item;
break;
}
}
}
					


                    if ($random_item->digital) {

                        $digital = Digitals::FindFirst("name = '$item->digital' AND reserved = 0");

                        $digital->reserved = 1;
                        $digital->save();
                    }

                    $history = History::CreateObject([
                        "user_id" => $this->user->id,
                        "description" => "Открытие кейса «" . $case->name . $case_description . "»",
                        "cost" => - $case_price,
                        "time" => time()
                    ]);

                    $history->save();


                    if (!$random_item->digital) {
                        $history = History::CreateObject([
                            "user_id" => $this->user->id,
                            "description" => "Приз «" . $random_item->name . "»",
                            "cost" => $item->cost,
                            "time" => time()
                        ]);

                        $history->save();

                        $this->user->balance += $item->cost;
                        $this->user->save();
                    }

                    $data = [
                        "success" => true,
                        "balance" => $this->user->balance,
                        "item" => $random_item
                    ];

                    return json_encode($data);
		}else {
                    $this->JsonMessage(false, "Кейс можно открыть только 1 раз.");
                }


                } else {
                    $this->JsonMessage(false, "Необходимо 10 рефераллов для открытия кейса.");
                }
            } else {
                $this->JsonMessage(false, "Кейс для открытия не найден");
            }
        } else {
            $this->JsonMessage(false, "Вы не передали необходимые параметры для открытия кейса");
        }
    }
    /**
     * Получение приза
     */

    public function getDigitalAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }

        if ($_POST['id'] && $_POST['type']) {

            switch ($_POST["type"]) {
                case 1;

                    $drop = $this->helper->getDropInfo($_POST['id']);

                    if ($drop) {

                        $item = $this->helper->getItemInfo($drop->item_id);

                        if ($drop->user_id != $this->user->id) {
                            $this->JsonMessage(false, "Вы пытаетесь получить не свой предмет !");
                        }

                        if ($item->digital && !$this->helper->getSendItem($_POST['id'])) {

                            $history = History::CreateObject([
                                "user_id" => $this->user->id,
                                "description" => "Приз «" . $item->name . "»",
                                "cost" => $item->cost,
                                "time" => time()
                            ]);

                            $history->save();

                            $send = Send::CreateObject([
                                "user_id" => $this->user->id,
                                "drop_id" => $_POST['id'],
                                "name" => $this->user->name,
                                "telephone" => '',
                                "email" => $this->user->email,
                                "address" => '',
                                "gift_image" => $item->image,
                                "gift_name" => $item->name,
                                "gift_code" => '',
                                "status" => 'Пользователь продал предмет - гифт',
                                "time" => time()
                            ]);

                            $digital = Digitals::FindFirst("name='$item->digital' AND reserved=1");

                            $digital->reserved = 0;
                            $digital->save();

                            $send->save();

                            $this->user->balance += $item->cost;
                            $this->user->save();

                            $data = [
                                "success" => true,
                                "message" => "Вы успешно продали предмет",
                                "balance" => $this->user->balance
                            ];

                            return json_encode($data);

                        } else {
                            $this->JsonMessage(false, "Вы пытаетесь получить не предмет, либо вы уже сделали заявку на отправку !");
                        }
                    } else {
                        $this->JsonMessage(false, "Данного дропа не существует !");
                    }
                    break;
                case 2:
                    $drop = $this->helper->getDropInfo($_POST['id']);

                    if ($drop) {

                        $item = $this->helper->getItemInfo($drop->item_id);

                        if ($drop->user_id != $this->user->id) {
                            $this->JsonMessage(false, "Вы пытаетесь получить не свой предмет !");
                        }

                        if ($item->digital && !$this->helper->getSendItem($_POST['id'])) {

                            $digital = $this->helper->getDigital($item->digital);

                            $send = Send::CreateObject([
                                "user_id" => $this->user->id,
                                "drop_id" => $_POST['id'],
                                "name" => $_POST['name'],
                                "telephone" => $_POST['telephone'],
                                "email" => $_POST['email'],
                                "address" => $_POST['address'],
                                "gift_image" => $item->image,
                                "gift_name" => $item->name,
                                "gift_code" => $digital->code,
                                "status" => 'Принято в обработку',
                                "time" => time()
                            ]);

                            $digital->delete();

                            $send->save();

                            $data = [
                                "success" => true,
                                "message" => "Предмет успешно принят в обработку для доставки",
                                "balance" => $this->user->balance
                            ];

                            return json_encode($data);

                        } else {
                            $this->JsonMessage(false, "Вы пытаетесь получить не предмет, либо вы уже сделали заявку на отправку !");
                        }
                    } else {
                        $this->JsonMessage(false, "Данного дропа не существует !");
                    }
                    break;
            }

        } else {
            $this->JsonMessage(false, "Вы не передали необходимые параметры для отправления предмета");
        }
    }

    /**
     * Лента LIVE дропа
     */

    public function liveDropAction()
    {
        $drops = Drops::Find([
            "order" => "id DESC",
            "limit" => 15
        ]);

        $data = array(
            "success" => true,
            "live" => []
        );

        foreach ($drops as $drop) {
            $data["live"][] = [
                "id" => $drop->id,
                "user" => [
                    "id" => $this->helper->getUserInfo($drop->user_id)->id,
                    "photo" => $this->helper->getUserInfo($drop->user_id)->photo
                ],
                "item" => $this->helper->getItemInfo($drop->item_id)
            ];
        }

        $data["last"] = $drops->getLast()->id;

        return json_encode($data);
    }

    /**
     * Функция обновления данных пользователя
     */

    public function userAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $user = Users::FindFirst($_POST["user"]);

            switch ($_POST["action"]) {
                case "updateBalance":

                    $user->balance = $_POST["balance"];
                    $user->save();

                    $this->JsonMessage(true, "Пользователь успешно обновлён");

                    break;
                case "updateRole":

                    $user->admin = $_POST["role"];
                    $user->save();

                    $this->JsonMessage(true, "Пользователь успешно обновлён");

                    break;
                case "updateCode":

                    $user->referral_code = $_POST["code"];
                    $user->save();

                    $this->JsonMessage(true, "Пользователь успешно обновлён");

                    break;
                case "delete":

                    $drops = Drops::Find("user_id='$user->id'");
                    $history = History::Find("user_id='$user->id'");
                    $transactions = Transactions::Find("user_id='$user->id'");

                    $drops->delete();
                    $history->delete();
                    $transactions->delete();

                    $user->delete();

                    $this->JsonMessage(true, "Пользователь успешно удалён");

                    break;
            }
        }
    }

    /**
     * Функция обновления данных вещей
     */

    public function itemAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $item = Items::FindFirst($_POST["item"]);

            switch ($_POST["action"]) {
                case "create":

                    $item = Items::CreateObject([
                        "name" => $_POST["name"],
                        "image" => $_POST["image"],
                        "digital" => $_POST["digital"],
                        "cost" => $_POST["cost"]
                    ]);

                    $item->save();

                    $this->JsonMessage(true, "Вещь успешно добавлена");

                    break;
                case "updateName":

                    $item->name = $_POST["name"];
                    $item->save();

                    $this->JsonMessage(true, "Вещь успешно обновлена");

                    break;
                case "updateCost":

                    $item->cost = $_POST["cost"];
                    $item->save();

                    $this->JsonMessage(true, "Вещь успешно обновлена");

                    break;
                case "delete":

                    $item->delete();

                    $this->JsonMessage(true, "Вешь успешно удалена");

                    break;
            }
        }
    }

    /**
     * Функция обновления данных кейсов
     */

    public function caseAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $case = Cases::FindFirst($_POST["case"]);

            switch ($_POST["action"]) {
                case "create":

                    $case = Cases::CreateObject([
                        "name" => $_POST["name"],
                        "position" => $_POST["position"],
                        "price" => $_POST["price"],
                        "discount" => $_POST["discount"],
                        "chance" => $_POST["chance"],
                        "items" => $_POST["items"],
                        "image" => $_POST["image"],
                        "background_image" => $_POST["background_image"],
                        "type" => $_POST["type"]
                    ]);

                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно добавлен");

                    break;
                case "updateName":

                    $case->name = $_POST["name"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updatePosition":

                    $case->position = $_POST["position"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updatePrice":

                    $case->price = $_POST["price"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updateItems":

                    $case->items = $_POST["items"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updateDiscount":

                    $case->discount = $_POST["discount"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updateChance":

                    $case->chance = $_POST["chance"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "updateType":

                    $case->type = $_POST["type"];
                    $case->save();

                    $this->JsonMessage(true, "Кейс успешно обновлён");

                    break;
                case "delete":

                    $case->delete();

                    $this->JsonMessage(true, "Кейс успешно удалён");

                    break;
            }
        }
    }
	public function ticketAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $ticket = Tickets::FindFirst($_POST["ticket"]);

            switch ($_POST["action"]) {
                case "create":

                    $ticket = Tickets::CreateObject([
                        "name" => $_POST["name"],
						"round" => 1,
                        "price" => $_POST["price"],
                        "winsum" => $_POST["winsum"],
                        "places" => $_POST["places"],
						"winner_id" => 0,
						"winner_ticket" => 0
                    ]);

                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно добавлен");

                    break;
					case "updateRound":

                    $ticket->round = $_POST["round"];
                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно обновлён");

                    break;
                case "updateName":

                    $ticket->name = $_POST["name"];
                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно обновлён");

                    break;
                case "updateWinsum":

                    $ticket->winsum = $_POST["winsum"];
                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно обновлён");

                    break;
                case "updatePrice":

                    $ticket->price = $_POST["price"];
                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно обновлён");

                    break;
                case "updatePlaces":

                    $ticket->places = $_POST["places"];
                    $ticket->save();

                    $this->JsonMessage(true, "Билет успешно обновлён");

                    break;
                case "delete":

                    $ticket->delete();

                    $this->JsonMessage(true, "Билет успешно удалён");

                    break;
            }
        }
    }

    /**
     * Функция обновления данных гифтов
     */

    public function digitalAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $digital = Digitals::FindFirst($_POST["digital"]);

            switch ($_POST["action"]) {
                case "create":

                    $digital = Digitals::CreateObject([
                        "name" => $_POST["name"],
                        "code" => $_POST["code"],
                    ]);

                    $digital->save();

                    $this->JsonMessage(true, "Гифт успешно добавлен");

                    break;
                case "updateName":

                    $digital->name = $_POST["name"];
                    $digital->save();

                    $this->JsonMessage(true, "Гифт успешно добавлен");

                    break;
                case "updateCode":

                    $digital->code = $_POST["code"];
                    $digital->save();

                    $this->JsonMessage(true, "Гифт успешно добавлен");

                    break;
                case "delete":

                    $digital->delete();

                    $this->JsonMessage(true, "Гифт успешно удалён");

                    break;
            }
        }
    }

    /**
     * Функция обновления данных ывводов
     */

    public function withdrawAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if ($_POST["action"]) {

            $withdraw = Transactions::FindFirst($_POST["id"]);

            switch ($_POST["action"]) {
                case "updateStatus":

                    $withdraw->status = $_POST["status"];
                    $withdraw->save();

                    $this->JsonMessage(true, "Вывод успешно добавлен");

                    break;
            }
        }
    }

    /**
     * Загрузка картинок на сайт
     */

    public function uploadFileAction()
    {
        if (!$this->user->admin) {
            $this->response->redirect("/e/404");
        }

        if (is_uploaded_file($_FILES["filename"]["tmp_name"]))
        {
            move_uploaded_file($_FILES["filename"]["tmp_name"], "../public/templates/site/dist/img/cases/" . $_FILES["filename"]["name"]);

            return $this->response->redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->JsonMessage(false, "Ошибка загрузки файла");
        }
    }

    /**
     * Функциия отправки сообщение на почту тех-поддержки
     */

       public function supportAction()
    {
        if (!$this->session->has("auth")) {
            $this->JsonMessage(false, "Вы не авторизированны !");
        }
       
        if ($_POST["name"] && $_POST["email"] && $_POST["subject"] && $_POST["message"]) {
 
            $to      = $this->config->settings->email;
            $subject = $_POST["name"] . " / " . $_POST["subject"];
            $message = $_POST["message"];
 
            $headers = 'From: ' . $_POST["email"] . "\r\n";
 
            if(mail($to, $subject, $message, $headers)) {
                $this->JsonMessage(true, "Спасибо, сообщение успешно доставлено.<br>В течении 24 часов мы с вами свяжемся!");
            } else {
                $this->JsonMessage(false, "Ошибка.<br>При отправке письма произошла ошибка!");
            }
        } else {
            $this->JsonMessage(false, "Опишите вашу проблему более детально");
        }
    }

    /**
     * Функция управляемого рандома
     */

    public function gunsRandom($items, $chances)
    {
        $total = array_sum($chances);
        $n = 0;

        $num = mt_rand(1, $total);

        foreach ($items as $i => $value) {
            $n += $chances[$i];

            if ($n >= $num) {
                return $items[$i];
            }
        }
    }

    /**
     * Создание JSON ответа
     */

    public function JsonMessage($success, $message, $error = null)
    {
        exit(json_encode([
            "success" => $success,
            "message" => $message,
            "error" => $error
        ]));
    }

}