<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>FunCash Админ</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/font-awesome.min.css">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/dataTables.bootstrap.min.css">
    <!-- Bootstrap social button library -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/bootstrap-social.css">
    <!-- Bootstrap select -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/bootstrap-select.css">
    <!-- Bootstrap file input -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/fileinput.min.css">
    <!-- Awesome Bootstrap checkbox -->
    <link rel="stylesheet" href="/templates/admin/vendor/css/awesome-bootstrap-checkbox.css">
    <!-- Admin Stye -->
    <link rel="stylesheet" href="/templates/admin/dist/css/style.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="brand clearfix">
    <a href="/admin" class="navbar-brand">Панель администратора</a>
    <span class="menu-btn"><i class="fa fa-bars"></i></span>
</div>

<div class="ts-main-content">
    <nav class="ts-sidebar">
        <ul class="ts-sidebar-menu">

            <li class="ts-label">Страницы</li>

            <li <?php if ($index) { ?> class="open" <?php } ?>>
                <a href="/admin"><i class="fa fa-dashboard"></i> Статистика</a>
            </li>
			
			<li <?php if ($tickets) { ?> class="open" <?php } ?>>
                <a href="/admin/tickets"><i class="fa fa-ticket"></i> Билеты</a>
            </li>

            <li <?php if ($cases) { ?> class="open" <?php } ?>>
                <a href="/admin/cases"><i class="fa fa-shopping-cart"></i> Кейсы</a>
            </li>

            <li <?php if ($items) { ?> class="open" <?php } ?>>
                <a href="/admin/items"><i class="fa fa-bars"></i> Вещи</a>
            </li>

            <li <?php if ($transactions) { ?> class="open" <?php } ?>>
                <a href="/admin/transactions"><i class="fa fa-ruble"></i> Транзакции</a>
            </li>

            <li <?php if ($users) { ?> class="open" <?php } ?>>
                <a href="/admin/users"><i class="fa fa-users"></i> Пользователи</a>
            </li>

            <li <?php if ($digitals) { ?> class="open" <?php } ?>>
                <a href="/admin/digitals"><i class="fa fa-gift"></i> Цифровые вещи</a>
            </li>

            <li <?php if ($send) { ?> class="open" <?php } ?>>
                <a href="/admin/send"><i class="fa fa-send"></i> Вещи для отправки</a>
            </li>

            <li <?php if ($withdraws) { ?> class="open" <?php } ?>>
                <a href="/admin/withdraws"><i class="fa fa-money"></i>  Вывод средств(<?= $this->helper->getNowWithdraws() ?>руб.)</a>
            </li>


        </ul>
    </nav>

    <div class="content-wrapper">
        <div class="container-fluid">

            
    <div class="row">
    <div class="col-md-12">

    <h2 class="page-title">Пользователи</h2>

    <div class="alert alert-success" style="display: none">
        <strong>Успешно!</strong> Indicates a successful or positive action.
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Таблица пользователей</div>
        <div class="panel-body table-responsive">
            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фото</th>
                    <th>Пол</th>
                    <th>Администратор</th>
                    <th>Дата рождения</th>
                    <th>Почта</th>
                    <th>Баланс</th>
                    <th>Реферал</th>
                    <th>Реферальный код</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr class="user-<?= $user->id ?>">
                        <td><?= $user->id ?></td>
                        <td><a href="/profile/<?= $user->id ?>" target="_blank"><?= $user->name ?></a> / <a href="https://vk.com/id<?= $user->uid ?>" target="_blank">VK</a></td>
                        <td><img src="<?= $user->photo ?>" style="width: 50px;"></td>
                        <td><?php if ($user->sex == 1) { ?> Женский <?php } else { ?> Мужской <?php } ?></td>
                        <td>
                            <div style="display: none"><?= $user->admin ?></div>

                            <select class="form-control" onchange="Functions.UpdateUserRole(<?= $user->id ?>, this.value)">
                                <option value="1" <?php if ($user->admin == 1) { ?> selected <?php } ?>>Да</option>
                                <option value="0" <?php if ($user->admin == 0) { ?> selected <?php } ?>>Нет</option>
                            </select>
                        </td>
                        <td><?= $user->date ?></td>
                        <td><?= $user->email ?></td>
                        <td>

                            <div style="display: none"><?= $user->balance ?></div>

                            <div class="input-group">
                                <input type="text" class="form-control" id="balance" value="<?= $user->balance ?>" onchange="Functions.UpdateUserBalance(<?= $user->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ruble"></i></span>
                            </div>
                        </td>
                        <td><?php if ($user->referral) { ?> <?= $user->referral ?> <?php } else { ?> Не вводил код <?php } ?></td>
                        <td>
                            <div style="display: none"><?= $user->referral_code ?></div>
                            <input type="text" class="form-control" id="balance" value="<?= $user->referral_code ?>" onchange="Functions.UpdateUserReferralCode(<?= $user->id ?>, this.value)">
                        </td>
                        <td><button type="button" class="btn btn-danger" onclick="Functions.DeleteUser(<?= $user->id ?>)">Удалить</button></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>


        </div>
    </div>


        </div>
    </div>
</div>

<!-- Loading Scripts -->
<script src="/templates/admin/vendor/js/jquery.min.js"></script>
<script src="/templates/admin/vendor/js/bootstrap.min.js"></script>
<script src="/templates/admin/vendor/js/jquery.dataTables.min.js"></script>
<script src="/templates/admin/vendor/js/dataTables.bootstrap.min.js"></script>
<script src="/templates/admin/vendor/js/bootstrap-select.js"></script>
<script src="/templates/admin/vendor/js/main.js"></script>
<script src="/templates/admin/dist/js/core.min.js"></script>


</body>

</html>