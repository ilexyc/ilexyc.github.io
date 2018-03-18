<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <title><?php if ($title) { ?> <?= $title ?> | <?php } ?> FUNCASH.RU - Веселая прибыль!</title>

    <link rel="stylesheet" href="/templates/site/dist/css/style.min.css"/>

    <!-- SITE SCRIPTS -->
    <script type="text/javascript" src="/templates/site/vendor/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="/templates/site/vendor/socket.io-1.4.5.js"></script>
    <script type="text/javascript" src="/templates/site/vendor/smoke.min.js"></script>
    <script type="text/javascript" src="/templates/site/vendor/animate.min.js"></script>
    <script type="text/javascript" src="/templates/site/vendor/switch.min.js"></script>
    <script type="text/javascript" src="/templates/site/vendor/roulette.min.js"></script>

    <!-- SITE AUTH -->
    <script type="text/javascript" src="//ulogin.ru/js/ulogin.js"></script>

    <!-- MAIN SCRIPT SITE -->
    <script type="text/javascript" src="/templates/site/dist/js/core.min.js"></script>

    <!-- META SITE -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="<?php if ($title) { ?> <?= $title ?> <?php } else { ?> FUNCASH.RU - Веселая прибыль! <?php } ?>"/>
    <meta name="keywords" content="<?php if ($title) { ?> <?= $title ?> <?php } else { ?> FUNCASH.RU,открытие кейсов,призовые кейсы,дропы,кейсы <?php } ?>"/>

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/favicon.ico"/>

    <!-- PUSH CREW -->
    <script>
        window.js_config = {'ip': 'funcash.ru', 'protocol': 'http'};
        (function (p, u, s, h) {
            p._pcq = p._pcq || [];
            p._pcq.push(['_currentTime', Date.now()]);
            s = u.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
              s.src='https://cdn.pushcrew.com/js/22449da88acb353e5d5b5201d6316697.js';
            h = u.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })(window, document);
    </script>
</head>
<body>

<div class="wrapper">
    <header class="header">
        <div class="inner">
            <div class="logo">
                <a href="/">
                    <img src="/templates/site/dist/img/logo.png" alt="FUNCASH.RU">
                </a>
            </div>
			<div class="stat">
                <div class="o o-1">Онлайн <div id="st-online">0</div></div>
                <div class="l"></div>
                <div class="o">Пользователей <div id="st-users">0</div></div>
                <div class="l"></div>
                <div class="o">Открыто кейсов <div id="st-cases">0</div></div>
                <div class="cls"></div>
                <script>
                    $(document).ready(function(){
                        var csns = $.animateNumber.numberStepFactories.separator(',');
                        $('#st-online').animateNumber({ number: 1, numberStep: csns }, 500);
                        $('#st-users').animateNumber({ number: <?= $this->helper->getAllUsers() ?>, numberStep: csns }, 700);
                        $('#st-cases').animateNumber({ number: <?= $this->helper->getAllDrops() ?>, numberStep: csns }, 1000);
                    });
                </script>
            </div>
            <div class="u-menu">
                <?php if ($user) { ?>
                    <div class="userpic">
                        <a href="/account/">
                            <img src="<?= $user->photo ?>" alt="<?= $user->name ?>">
                        </a>
                    </div>
                    <div class="userinfo">
                        <div class="name"><a href="/account/" class="eas"><?= $user->name ?></a></div>
                        <div class="price">
                            <span class="flaticon-money"></span> <b id="user-balance"><?= $user->balance ?></b><span class="flaticon-ruble ruble-small"></span>
                            <span class="plus eas" onclick="Functions.PopupOpen('#deposit');">+</span>
                            <span class="minus eas" onclick="Functions.PopupOpen('#withdrawal');">-</span>
                        </div>
                    </div>
                <?php } else { ?>
                    <ul class="so-auth" id="uLogin"
                        data-ulogin="display=buttons;fields=first_name,last_name,email,verified_email;verify=1;optional=photo_big,bdate,sex;providers=vkontakte;hidden=;redirect_uri=http://www.funcash.ru/auth">
                        <li class="btn sexy gradient-blue icon-r flaticon-vk" data-uloginbutton="vkontakte"><b>Войти через</b> <span class="flaticon-soc-vk"></span></li>
                    </ul>
                <?php } ?>
                <div class="cls"></div>
            </div>
            <nav class="nav">
                <div class="nav-button">
                    <span class="flaticon-menu"></span>
                </div>
                <ul>
                    <li><a href="/" <?php if ($index) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Кейсы</a></li>
					<li><a href="/pages/contests" <?php if ($contests) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>><span class="flaticon-gift"></span> Конкурс "Ютюбер"</a></li>
                    <li><a href="/pages/faq" <?php if ($faq) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>FAQ</a></li>
                    <li><a href="/pages/guarantees" <?php if ($guarantees) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Гарантии</a></li>
                    <li><a href="/pages/reviews" <?php if ($reviews) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Отзывы</a></li>
                </ul>
            </nav>
            <div class="cls"></div>
        </div>
    </header>

    <div class="sub-header">
	 <div class="inner">
            <div class="live">
			<div class="inner">
                <div class="name">Live лента</div>
                <div class="prize" id="live-prize-box">
                    <br><br>Загрузка...<br><br><br>
                </div></div>
                <div class="cls"></div>
				
            </div>
            <div class="cls"></div>
			 </div>
        </div>
    </div>

    <?php if ($index) { ?>
    <?php } ?>

    <main class="content">
        <div class="inner">
            
    <div class="account">

        <div class="userbox">
            <div class="l">
                <a href="/auth/logout/" class="logout">Выйти</a>
                <img src="<?= $user->photo ?>" alt="<?= $user->name ?>">
                <h1><?= $user->name ?> <a href="https://vk.com/id<?= $user->uid ?>" target="_blank"> <span class="flaticon-soc-vk"></span> </a> </h1>
                <div class="u-cases"><span class="flaticon-case"></span> Кейсы: <span class="n"><?= $this->length($drops) ?></span></div>
                <div class="u-money"><span class="flaticon-money"></span> Выигрыш: <span class="n"><?= $this->helper->getUserBank($user->id) ?>р</span></div>
                <div class="u-balance"><span class="flaticon-ruble"></span> Баланс: <span class="n"><b id="u-balance-field"><?= $user->balance ?></b>р</span></div>
            </div>
            <div class="r">  
                <a href="#deposit" onclick="Functions.PopupOpen('#deposit');return false;" class="btn yellow">Пополнить баланс</a>
                <a href="#withdrawal" class="btn darkblue" onclick="Functions.PopupOpen('#withdrawal');return false;">Вывод средств</a>
            </div>
            <div class="cls"></div>
        </div>

        <div class="referral">

            <div class="b1">
                <h3>
                    <span class="flaticon-users"></span> Пригласи друзей и заработай больше!
                </h3>
                <div class="desc">Отправь свой уникальный код друзьям<br> и <span>получи 10%</span> от каждого пополнения баланса другом!</div>
                <div class="field">
                    <input type="text" class="inp" value="<?= $user->referral_code ?>" readonly="readonly" onclick="select();">
                </div>
                <div class="short">По вашему коду зарегистрировались: <?= $this->helper->getUsersReferrals($user->referral_code) ?></div>
            </div>

            <div class="b2">

                <div class="loader" id="redeem-loader">
                    <img src="/templates/site/dist/img/loader.svg" alt="">
                </div>

                <h3>
                    <span class="flaticon-money"></span> Введи код и получи 10р!
                </h3>

                <div class="desc">У вас есть партнерский код?<br> Введите его в поле и <span>получите 10 рублей</span> прямо сейчас!</div>

                <?php if ($user->referral) { ?>
                    <div class="field">
                        <input type="text" class="inp" value="<?= $user->referral ?>" disabled="disabled">
                    </div>
                <?php } else { ?>
                    <div class="field">
                        <input type="text" class="inp redeem-input">
                        <input type="button" class="btn" value="OK" onclick="Functions.RedeemCode($('.redeem-input'), this, '#redeem-loader');">
                    </div>
                <?php } ?>

                <?php if ($user->referral) { ?>
                    <div class="short">
                        <span class="flaticon-check"></span> Код успешно погашен!
                    </div>
                <?php } else { ?>
                    <div class="short">Введите код и нажмите enter</div>
                <?php } ?>
            </div>
            <div class="cls"></div>
        </div>

        <div class="tabs">
            <div class="tab tab-1 eas active" data-tab-id="1">Призы</div>
            <div class="tab tab-2 eas" data-tab-id="2">История</div>
            <div class="tab tab-3 eas" data-tab-id="3">Финансы</div>
            <div class="tab tab-4 eas" data-tab-id="4">Доставка</div>
        </div>

        <div class="tab-container tab-container-1 active">
            <div class="cls"></div>
            <?php if ($this->length($drops) > 0) { ?>
                <div class="history-cases">
                    <?php foreach ($drops as $drop) { ?>
                        <div class="history-case" id="history-case-<?= $drop->id ?>">

                            <div class="status">
                                <?php if ($this->helper->getItemInfo($drop->item_id)->digital && !$this->helper->getSendItem($drop->id)) { ?>
                                    <span class="flaticon-wait"></span>
                                <?php } else { ?>
                                    <span class="flaticon-check"></span>
                                <?php } ?>
                            </div>

                            <div class="coin silver">
                                <img src="<?= $this->helper->getItemInfo($drop->item_id)->image ?>"
                                     alt="<?= $this->helper->getItemInfo($drop->item_id)->name ?>">
                            </div>

                            <div class="loader">
                                <img src="/templates/site/dist/img/loader.svg" alt="">
                            </div>

                            <?php if ($this->helper->getItemInfo($drop->item_id)->digital && !$this->helper->getSendItem($drop->id)) { ?>
                                <div class="button" onclick="Functions.GetGift(<?= $drop->id ?>, '<?= $this->helper->getItemInfo($drop->item_id)->name ?>', <?= $this->helper->getItemInfo($drop->item_id)->cost ?>)">
                                    <div class="btn">Получить</div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <div class="gift-shipping" id="gift-shipping">
                        <div class="in">
                            <form name="gift-shipping-form">
                                <span class="flaticon-close close" onclick="$('#gift-shipping').fadeOut(100);"></span>
                                <div class="loader">
                                    <img src="/templates/site/dist/img/loader.svg" alt="">
                                </div>
                                <div class="line">ФИО*</div>
                                <div class="input"><input type="text" id="name" class="inp" value="<?= $user->name ?>"></div>
                                <div class="cls"></div>
                                <div class="line">Телефон*</div>
                                <div class="input"><input type="text" id="telephone" class="inp"></div>
                                <div class="cls"></div>
                                <div class="line">E-mail*</div>
                                <div class="input"><input type="text" id="email" class="inp" value="<?= $user->email ?>"></div>
                                <div class="cls"></div>
                                <div class="line">Полный адрес доставки</div>
                                <div class="input"><textarea class="textarea" id="address" placeholder="Пример: Россия, Москва, улица Берозово 12, кв. 48"></textarea></div>
                                <div class="cls"></div><div class="button"><input type="button" class="btn rounded" value="Отправить" onclick="Functions.ShipGift('gift-shipping-form');"></div>
                                <div class="cls"></div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="infobox text-center">
                    <br>
                    <h3>
                        <center>Вы не открывали кейсов :(</center>
                    </h3>
                    <a href="/" class="btn rounded blue">Открыть и выиграть</a>
                    <br>
                    <br>
                </div>
            <?php } ?>
        </div>

        <div class="tab-container tab-container-2">
            <?php if ($this->length($history) > 0) { ?>
                <table class="table history-money">
                    <thead>
                    <tr>
                        <td width="50">№</td>
                        <td>Описание</td>
                        <td width="150" class="text-center">Сумма</td>
                        <td width="150" class="text-center">Дата</td>
                        <td width="80" class="text-center">Статус</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $element) { ?>
                        <tr>
                            <td><?= $element->id ?></td>
                            <td><?= $element->description ?></td>
                            <td class="amount <?php if ($element->cost < 0) { ?> negative <?php } ?> text-center"><?php if ($element->cost > 0) { ?>+<?php } ?><?= $element->cost ?><span class="flaticon-ruble text-11 text-normal"></span></td>
                            <td class="text-center"><?= $this->helper->unixToString('%d %b %Y, %H:%I', $element->time) ?></td>
                            <td class="text-center">
                                <span class="flaticon-check"></span>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="infobox">
                    <div class="text-center">Скоро тут будут ваши миллиарды ;)</div>
                </div>
            <?php } ?>
        </div>

        <div class="tab-container tab-container-3">
            <?php if ($this->length($transactions) > 0) { ?>
                <table class="table history-money">
                    <thead>
                    <tr>
                        <td width="50">№</td>
                        <td>Описание</td>
                        <td width="150" class="text-center">Сумма</td>
                        <td width="150" class="text-center">Дата</td>
                        <td width="80" class="text-center">Статус</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $transaction) { ?>
                        <tr>
                            <td><?= $transaction->id ?></td>
                            <td><?= $transaction->description ?></td>
                            <td class="amount <?php if ($transaction->cost < 0) { ?> negative <?php } ?> text-center"><?php if ($transaction->cost > 0) { ?>+<?php } ?><?= $transaction->cost ?><span class="flaticon-ruble text-11 text-normal"></span></td>
                            <td class="text-center"><?= $this->helper->unixToString('%d %b %Y, %H:%I', $transaction->time) ?></td>
                            <td class="text-center">
                                <?php if ($transaction->status == 1) { ?>
                                    <span class="flaticon-check"></span>
                                <?php } elseif ($transaction->status == 0) { ?>
                                    <span class="flaticon-wait"></span>
                                <?php } elseif ($transaction->status == 2) { ?>
                                    <span class="flaticon-close"></span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="infobox">
                    <div class="text-center">Скоро тут будут ваши миллиарды ;)</div>
                </div>
            <?php } ?>
        </div>

        <div class="tab-container tab-container-4">
            <div id="history-shipping">
                <?php if ($this->length($send) > 0) { ?>
                    <table class="table history-money">
                        <?php foreach ($send as $element) { ?>
                            <?php if ($element->telephone && $element->address) { ?>
                                <tr>
                                    <td width="50"><?= $element->id ?></td>
                                    <td class="gift-shipping-photo" width="200"><img
                                                src="<?= $element->gift_image ?>"><br><?= $element->gift_name ?></td>
                                    <td class="gift-shipping-row"><b><?= $element->name ?></b><br><?= $element->address ?>
                                        <br><?= $element->telephone ?>, <?= $element->email ?></td>
                                    <td width="150"
                                        class="text-center"><?= $this->helper->unixToString('%d %b %Y, %H:%I', $element->time) ?></td>
                                    <td width="80" class="text-center"><?= $element->status ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <div class="text-center">У вас нет призов в доставке</div>
                <?php } ?>
            </div>
        </div>


    </div>

    <div class="seperator"></div>

        </div>
    </main>

    <?php if (($index || $case) && ($this->length($top) >= 3)) { ?>
    <div class="top-users">
        <div class="inner">
            <h3>Самые везучие</h3>
            <div class="cls"></div>
            <div class="top-3">

                <?php $v170326242217263320201iterator = $top; $v170326242217263320201incr = 0; $v170326242217263320201loop = new stdClass(); $v170326242217263320201loop->self = &$v170326242217263320201loop; $v170326242217263320201loop->length = count($v170326242217263320201iterator); $v170326242217263320201loop->index = 1; $v170326242217263320201loop->index0 = 1; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - 1; ?><?php foreach ($v170326242217263320201iterator as $user) { ?><?php $v170326242217263320201loop->first = ($v170326242217263320201incr == 0); $v170326242217263320201loop->index = $v170326242217263320201incr + 1; $v170326242217263320201loop->index0 = $v170326242217263320201incr; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length - $v170326242217263320201incr; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - ($v170326242217263320201incr + 1); $v170326242217263320201loop->last = ($v170326242217263320201incr == ($v170326242217263320201loop->length - 1)); ?>
                    <?php if ($v170326242217263320201loop->index == 2) { ?>
                        <div class="t2">
                            <div class="img">
                                <a href="/profile/<?= $user->user_id ?>">
                                    <img src="<?= $this->helper->getUserInfo($user->user_id)->photo ?>"
                                         alt="<?= $this->helper->getUserInfo($user->user_id)->name ?>">
                                    <img src="/templates/site/dist/img/top-2-3.png" alt="cover" class="back eas">
                                </a>
                            </div>
                            <div class="u-cases"><span class="flaticon-case"></span> <?= $this->helper->getUserCases($user->user_id) ?></div>
                            <div class="u-money"><span class="flaticon-money"></span> <?= $this->helper->getUserBank($user->user_id) ?><span class="flaticon-ruble small-icon"></span></div>
                        </div>
                    <?php } ?>
                <?php $v170326242217263320201incr++; } ?>

                <?php $v170326242217263320201iterator = $top; $v170326242217263320201incr = 0; $v170326242217263320201loop = new stdClass(); $v170326242217263320201loop->self = &$v170326242217263320201loop; $v170326242217263320201loop->length = count($v170326242217263320201iterator); $v170326242217263320201loop->index = 1; $v170326242217263320201loop->index0 = 1; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - 1; ?><?php foreach ($v170326242217263320201iterator as $user) { ?><?php $v170326242217263320201loop->first = ($v170326242217263320201incr == 0); $v170326242217263320201loop->index = $v170326242217263320201incr + 1; $v170326242217263320201loop->index0 = $v170326242217263320201incr; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length - $v170326242217263320201incr; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - ($v170326242217263320201incr + 1); $v170326242217263320201loop->last = ($v170326242217263320201incr == ($v170326242217263320201loop->length - 1)); ?>
                    <?php if ($v170326242217263320201loop->index == 1) { ?>
                        <div class="t1">
                            <div class="img">
                                <a href="/profile/<?= $user->user_id ?>">
                                    <img src="<?= $this->helper->getUserInfo($user->user_id)->photo ?>"
                                         alt="<?= $this->helper->getUserInfo($user->user_id)->name ?>">
                                    <img src="/templates/site/dist/img/top-1.png" alt="cover" class="back eas">
                                </a>
                            </div>
                            <div class="u-cases"><span class="flaticon-case"></span> <?= $this->helper->getUserCases($user->user_id) ?></div>
                            <div class="u-money"><span class="flaticon-money"></span> <?= $this->helper->getUserBank($user->user_id) ?><span class="flaticon-ruble small-icon"></span></div>
                            <div class="back-shadow"></div>
                        </div>
                    <?php } ?>
                <?php $v170326242217263320201incr++; } ?>

                <?php $v170326242217263320201iterator = $top; $v170326242217263320201incr = 0; $v170326242217263320201loop = new stdClass(); $v170326242217263320201loop->self = &$v170326242217263320201loop; $v170326242217263320201loop->length = count($v170326242217263320201iterator); $v170326242217263320201loop->index = 1; $v170326242217263320201loop->index0 = 1; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - 1; ?><?php foreach ($v170326242217263320201iterator as $user) { ?><?php $v170326242217263320201loop->first = ($v170326242217263320201incr == 0); $v170326242217263320201loop->index = $v170326242217263320201incr + 1; $v170326242217263320201loop->index0 = $v170326242217263320201incr; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length - $v170326242217263320201incr; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - ($v170326242217263320201incr + 1); $v170326242217263320201loop->last = ($v170326242217263320201incr == ($v170326242217263320201loop->length - 1)); ?>
                    <?php if ($v170326242217263320201loop->index == 3) { ?>
                        <div class="t3">
                            <div class="img">
                                <a href="/profile/<?= $user->user_id ?>">
                                    <img src="<?= $this->helper->getUserInfo($user->user_id)->photo ?>"
                                         alt="<?= $this->helper->getUserInfo($user->user_id)->name ?>">
                                    <img src="/templates/site/dist/img/top-2-3.png" alt="cover" class="back eas">
                                </a>
                            </div>
                            <div class="u-cases"><span class="flaticon-case"></span> <?= $this->helper->getUserCases($user->user_id) ?></div>
                            <div class="u-money"><span class="flaticon-money"></span> <?= $this->helper->getUserBank($user->user_id) ?><span class="flaticon-ruble small-icon"></span></div>
                        </div>
                    <?php } ?>
                <?php $v170326242217263320201incr++; } ?>

                <div class="cls"></div>
            </div>

            <?php if ($v170326242217263320200loop->index > 3) { ?>
                <div class="top-line"></div>
            <?php } ?>

            <div class="top-10">
                <?php $v170326242217263320201iterator = $top; $v170326242217263320201incr = 0; $v170326242217263320201loop = new stdClass(); $v170326242217263320201loop->self = &$v170326242217263320201loop; $v170326242217263320201loop->length = count($v170326242217263320201iterator); $v170326242217263320201loop->index = 1; $v170326242217263320201loop->index0 = 1; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - 1; ?><?php foreach ($v170326242217263320201iterator as $user) { if ($v170326242217263320201loop->index > 3) { ?><?php $v170326242217263320201loop->first = ($v170326242217263320201incr == 0); $v170326242217263320201loop->index = $v170326242217263320201incr + 1; $v170326242217263320201loop->index0 = $v170326242217263320201incr; $v170326242217263320201loop->revindex = $v170326242217263320201loop->length - $v170326242217263320201incr; $v170326242217263320201loop->revindex0 = $v170326242217263320201loop->length - ($v170326242217263320201incr + 1); $v170326242217263320201loop->last = ($v170326242217263320201incr == ($v170326242217263320201loop->length - 1)); ?>
                <span class="user">
                    <a href="/profile/<?= $user->user_id ?>" class="eas">
                        <img src="<?= $this->helper->getUserInfo($user->user_id)->photo ?>" alt="<?= $this->helper->getUserInfo($user->user_id)->name ?>"></a>
                    <span class="s-cases"><span class="flaticon-case"></span> <?= $this->helper->getUserBank($user->user_id) ?></span>
                    <span class="s-money"><span class="flaticon-money"></span> <?= $this->helper->getUserBank($user->user_id) ?><span class="flaticon-ruble small-icon"></span></span>
                </span>
                <?php } ?><?php $v170326242217263320201incr++; } ?>
            </div>
            <div class="cls"></div>
        </div>
    </div>
    <?php } ?>

    <footer class="footer">
        <div class="inner">
            <div class="l">
                <ul>
                    <li><a href="/" <?php if ($index) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Кейсы</a></li>
                    <li><a href="/pages/faq" <?php if ($faq) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>FAQ</a></li>
                    <li><a href="/pages/guarantees" <?php if ($guarantees) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Гарантии</a></li>
                    <li><a href="/pages/reviews" <?php if ($reviews) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Отзывы</a></li>
                    <li><a href="/pages/support" <?php if ($support) { ?> class="eas active" <?php } else { ?> class="eas" <?php } ?>>Техническая поддержка</a></li>
                </ul>
                <div class="copy">
                    Copyright © 2016 FUNCASH.RU. Все права защищены.<br>Авторизуясь на сайте вы принимаете <a href="/pages/terms">пользовательское соглашение</a><br>
					По всем вопросам обращаться на email <a>support@funcash.ru</a><br>
              
                </div>
            </div>
            <div class="r">
                <h5>Принимаем</h5>
                <div class="cls"></div>
                <div class="img">
                    <img src="/templates/site/dist/img/payment-methods.png" alt="Принимаем">
                </div>
                <div class="wm-box">
                    <a href="http://www.megastock.ru/" target="_blank">
                        <img src="/templates/site/dist/img/payment-wm-logo.png" alt="www.megastock.ru" class="wm wm-1"/>
                    </a>
                    <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=269471717715" target="_blank">
                        <img src="/templates/site/dist/img/payment-wm-attestat.png" class="wm" alt="Здесь находится аттестат нашего WM идентификатора 269471717715" style="position: absolute;bottom: 3px;left: -52px; width: 46px;"/>
                    </a>
                    <a href="//www.free-kassa.ru/" target="_blank" rel="nofollow">
                        <img src="/templates/site/dist/img/payment-freekassa.png?v=2">
                    </a>
                </div>
                <div class="cls"></div>
            </div>
            <div class="cls"></div>
        </div>
    </footer>
</div>

<div id="withdrawal" class="popup">
    <div class="popup-container" style="top: 50%;">
        <form name="form-withdrawal" id="form-withdrawal" method="post">
            <div class="loader"><img src="/templates/site/dist/img/loader.svg" alt=""></div>
            <div class="eas close" onclick="Functions.PopupClose('#withdrawal');"><span class="flaticon-close"></span></div>

            <h3>Вывод средств</h3>

            <div class="info"><b>Вывод средств осуществляется в течении 24 часов.</b><br>Однако, в среднем мы обрабатываем платежи значительно быстро и 3-5 раза в день.<br>Минимальная сумма к выводу <b>100р</b></div>

            <div class="amount-l">
                <h4>Сумма</h4>
                <input type="text" name="amount" class="inp" maxlength="5" value="100" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onchange="if (this.value < 100) this.value=100">
                <input type="hidden" name="type" id="withdrawal-type-field" value="webmoney">
            </div>

            <div class="amount-r amount-r2">
                <h4>Номер кошелька</h4>
                <input type="text" name="purse" class="inp PurseHolder" maxlength="16" placeholder="Пример: R23884920195">
            </div>

            <div class="cls"></div><h4>Куда хотите вывести?</h4>
            <div class="cls"></div>
            <span class="payment-method eas pm-webmoney active" onclick="Functions.ChangePaymentMethod('webmoney','Пример: R23884920195');"><img src="/templates/site/dist/img/payment-webmoney.svg" alt="WebMoney"><span class="flaticon-check"></span></span>
            <span class="payment-method eas pm-yandex" onclick="Functions.ChangePaymentMethod('yandex','Пример: 41003592336109');"><img src="/templates/site/dist/img/payment-yandex.svg" alt="Яндекс Деньги"><span class="flaticon-check"></span></span>
            <span class="payment-method eas pm-qiwi" onclick="Functions.ChangePaymentMethod('qiwi','Пример: 7900123456');"><img src="/templates/site/dist/img/payment-qiwi.svg" alt="QIWI"><span class="flaticon-check"></span></span>

            <div class="cls"></div>

            <div class="foo foo-2">
                <input type="button" class="btn blue rounded" value="Вывести средства" onclick="Functions.WithdrawNow();">
            </div>
        </form>
    </div>
    <div class="popup-overlay" onclick="Functions.PopupClose('#withdrawal');"></div>
</div>

<div id="deposit" class="popup">
    <div class="popup-container" style="top: -300%;">
        <div class="eas close" onclick="Functions.PopupClose('#deposit');"><span class="flaticon-close"></span></div>
        <h3>Пополнить баланс</h3>

        <div class="info"><b>Баланс начисляется моментально, но иногда процесс может занять 1-2 минуты</b><br>Если вы хотите пополнить баланс через VISA, MasterCard, другую банковскую карту, мобильного оператора, терминал и так далее, пожалуйста, выберите Interkassa или FreeKassa</div>

        <h4>Сумма</h4>

        <div class="cls"></div>

        <div class="amount-l">
            <form name="form-deposit" id="form-deposit" method="post" action="/payment/">
                <input type="text" name="amount" class="inp" maxlength="5" value="100" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onchange="if (this.value < 10) this.value=10; if (this.value > 15000) this.value=15000">
            </form>
        </div>

        <div class="amount-r">
            Максимальная сумма за раз: <b>15000р</b><br>Минимальная сумма: <b>10р</b>
        </div>

        <div class="cls"></div>

        <h4>Выберите платежную систему</h4>

        <div class="cls"></div>

      <!-- <span class="payment-method eas pm-webmoney active" onclick="Functions.ChangePaymentMethod('webmoney');"><img src="/templates/site/dist/img/payment-webmoney.svg" alt="Interkassa (WebMoney)"><span class="flaticon-check"></span></span> -->
        <span class="payment-method eas pm-yandex" onclick="Functions.ChangePaymentMethod('yandex');"><img src="/templates/site/dist/img/payment-yandex.svg" alt="Interkassa (Яндекс Деньги)"><span class="flaticon-check"></span></span>
        <span class="payment-method eas pm-qiwi" onclick="Functions.ChangePaymentMethod('qiwi');"><img src="/templates/site/dist/img/payment-qiwi.svg" alt="Interkassa (QIWI)"><span class="flaticon-check"></span></span>
        <span class="payment-method eas pm-interkassa" onclick="Functions.ChangePaymentMethod('interkassa');"><img src="/templates/site/dist/img/payment-interkassa.svg" alt="Interkassa"><span class="flaticon-check"></span></span>

        <div class="cls"></div>

        <div class="foo">
            <input type="button" class="btn orange rounded" value="Пополнить баланс" onclick="Functions.DepositNow();">
        </div>
    </div>
    <div class="popup-overlay" onclick="Functions.PopupClose('#deposit');"></div>
</div>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?136"></script>

<!-- VK Widget -->
<div id="vk_community_messages"></div>
<script type="text/javascript">
VK.Widgets.CommunityMessages("vk_community_messages", 131358928, {});
</script>
<!-- VK BUTTON -->
<a class="vk-button" href="http://vk.com/funcash_ru" rel="nofollow" target="_blank">
    <span class="flaticon-soc-vk"></span> Мы Вконтакте
</a>

</body>
</html>