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

    <h2 class="page-title">Билеты</h2>

    <div class="alert alert-success" style="display: none">
        <strong>Успешно!</strong> Indicates a successful or positive action.
    </div>

    <button type="button" class="btn btn-primary" style="margin-bottom: 20px;" data-toggle="modal" data-target="#addCase">Добавить билет</button>


    <div class="panel panel-default">
        <div class="panel-heading">Таблица кейсов</div>
        <div class="panel-body table-responsive">
            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
					<th>Раунд</th>
                    <th>Название</th>
                    <th>Стоймость</th>
                    <th>Кол-во билетов</th>
                    <th>Выигрыш</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tickets as $ticket) { ?>
                    <tr class="case-<?= $ticket->id ?>">
                        <td><?= $ticket->id ?></td>
                        <td><div style="display: none"><?= $ticket->round ?></div><input type="text" class="form-control" id="ticket" value="<?= $ticket->round ?>" onchange="Functions.UpdateTicketRound(<?= $ticket->id ?>, this.value)"></td>
                        <td><div style="display: none"><?= $ticket->name ?></div><input type="text" class="form-control" id="name" value="<?= $ticket->name ?>" onchange="Functions.UpdateTicketName(<?= $ticket->id ?>, this.value)"></td>
                        <td>
                            <div style="display: none"><?= $ticket->price ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="price" value="<?= $ticket->price ?>" onchange="Functions.UpdateTicketPrice(<?= $ticket->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ruble"></i></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: none"><?= $ticket->places ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="places" value="<?= $ticket->places ?>" onchange="Functions.UpdateTicketPlaces(<?= $ticket->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ruble"></i></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: none"><?= $ticket->winsum ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="winsum" value="<?= $ticket->winsum ?>" onchange="Functions.UpdateTicketWinsum(<?= $ticket->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-percent"></i></span>
                            </div>
                        </td>
                        <td><button type="button" class="btn btn-danger" onclick="Functions.DeleteTicket(<?= $ticket->id ?>)">Удалить</button></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>


        </div>
    </div>

    <!-- Modal Add Item -->
    <div class="modal fade" id="addCase" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Добавление билета</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" class="form-control" id="name">
                    </div>

    

                    <div class="form-group">
                        <label for="image">Стоймость</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="price">
                            <span class="input-group-addon"> <i class="fa fa-ruble"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Выигрыш</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="winsum">
                            <span class="input-group-addon"> <i class="fa fa-ruble"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Кол-во билетов</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="places">
                            <span class="input-group-addon"> <i class="fa fa-percent"></i></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="Functions.AddTicket()">Добавить билет</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Image -->
    <div class="modal fade" id="addImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Добавление картинки</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="center-block" style="width:200px">
                            <form action="/api/uploadFile" method="post" enctype="multipart/form-data">
                                <input type="file" name="filename"><br>
                                <input type="submit" value="Загрузить" class="center-block"/>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
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