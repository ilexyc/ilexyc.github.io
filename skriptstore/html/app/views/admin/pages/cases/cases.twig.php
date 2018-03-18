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

    <h2 class="page-title">Кейсы</h2>

    <div class="alert alert-success" style="display: none">
        <strong>Успешно!</strong> Indicates a successful or positive action.
    </div>

    <button type="button" class="btn btn-primary" style="margin-bottom: 20px;" data-toggle="modal" data-target="#addCase">Добавить кейс</button>
    <button type="button" class="btn btn-primary" style="margin-bottom: 20px;" data-toggle="modal" data-target="#addImage">Загрузить картинку</button>

    <div class="panel panel-default">
        <div class="panel-heading">Таблица кейсов</div>
        <div class="panel-body table-responsive">
            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Позиция</th>
                    <th>Название</th>
                    <th>Стоймость</th>
                    <th>Скидка</th>
                    <th>Шанс окупаемости</th>
                    <th>Вещи</th>
                    <th>Фоновое изображение</th>
                    <th>Изображение</th>
                    <th>Тип кейса</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cases as $case) { ?>
                    <tr class="case-<?= $case->id ?>">
                        <td><?= $case->id ?></td>
                        <td><div style="display: none"><?= $case->position ?></div><input type="text" class="form-control" id="position" value="<?= $case->position ?>" onchange="Functions.UpdateCasePosition(<?= $case->id ?>, this.value)"></td>
                        <td><div style="display: none"><?= $case->name ?></div><input type="text" class="form-control" id="name" value="<?= $case->name ?>" onchange="Functions.UpdateCaseName(<?= $case->id ?>, this.value)"></td>
                        <td>
                            <div style="display: none"><?= $case->price ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="price" value="<?= $case->price ?>" onchange="Functions.UpdateCasePrice(<?= $case->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ruble"></i></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: none"><?= $case->discount ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="discount" value="<?= $case->discount ?>" onchange="Functions.UpdateCaseDiscount(<?= $case->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ruble"></i></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: none"><?= $case->chance ?></div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="chance" value="<?= $case->chance ?>" onchange="Functions.UpdateCaseChance(<?= $case->id ?>, this.value)">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-percent"></i></span>
                            </div>
                        </td>
                        <td><div style="display: none"><?= $case->items ?></div><input type="text" class="form-control" id="items" value="<?= $case->items ?>" onchange="Functions.UpdateCaseItems(<?= $case->id ?>, this.value)"></td>
                        <td><img src="<?= $case->background_image ?>" style="width: 50px;"></td>
                        <td><img src="<?= $case->image ?>" style="width: 50px;"></td>
                        <td>
                            <div style="display: none"><?= $case->type ?></div>

                            <select class="form-control" onchange="Functions.UpdateCaseType(<?= $case->id ?>, this.value)">
                                <option value="1" <?php if ($case->type == 1) { ?> selected <?php } ?>>Валюта</option>
                                <option value="2" <?php if ($case->type == 2) { ?> selected <?php } ?>>Гифт</option>
                                <option value="3" <?php if ($case->type == 3) { ?> selected <?php } ?>>Цифровые вещи</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger" onclick="Functions.DeleteCase(<?= $case->id ?>)">Удалить</button></td>
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
                    <h4 class="modal-title" id="myModalLabel">Добавление кейса</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="form-group">
                        <label for="name">Позиция</label>
                        <input type="text" class="form-control" id="position">
                    </div>

                    <div class="form-group">
                        <label for="image">Стоймость</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="price">
                            <span class="input-group-addon"> <i class="fa fa-ruble"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Скидка</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="discount">
                            <span class="input-group-addon"> <i class="fa fa-ruble"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Шанс окупаемости</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="chance">
                            <span class="input-group-addon"> <i class="fa fa-percent"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Вещи</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="items">
                            <span class="input-group-addon"><b>ID's</b></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Изображение</label>

                        <select class="selectpicker" id="image">
                            <?php foreach ($images as $image) { ?>
                                <option data-thumbnail="/templates/site/dist/img/cases/<?= $image ?>" value="/templates/site/dist/img/cases/<?= $image ?>"><?= $image ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Фоновое изображение</label>

                        <select class="selectpicker" id="background-image">
                            <?php foreach ($images as $image) { ?>
                                <option data-thumbnail="/templates/site/dist/img/cases/<?= $image ?>" value="/templates/site/dist/img/cases/<?= $image ?>"><?= $image ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Тип кейса</label>

                        <select class="form-control" id="type">
                            <option value="1">Валюта</option>
                            <option value="2">Гифт</option>
                            <option value="3">Цифровые вещи</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="Functions.AddCase()">Добавить кейс</button>
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