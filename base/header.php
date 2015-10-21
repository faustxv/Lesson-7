<?php
// Початок буферу.
ob_start();
// Початок або продовження сесії.
session_start();
// Створюємо змінну $editor, у якій міститься інформація про роль користувача на сайті.
if (isset($_SESSION['login'])) {
  $editor = TRUE;
}
else $editor = FALSE;
// Якщо раніше заголовок сторінки не був заданий, тоді ми його задаємо.
if (!isset($page_title)) {
  $page_title = 'Blog site';
}

?>
<!-- Виводимо основну структуру сайту. -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php print $page_title; ?></title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
<!-- Будуємо меню сайту. -->
<div class="header">
  <nav class="navbar navbar-inverse">
      <ul class="nav nav-pills">
        <li role="presentation"><a href="/"><span class="glyphicon glyphicon-home"></span> Головна Сторінка</a></li>
        <?php if ($editor): ?>
        <li role="presentation"><a href="/add.php"><span class = "glyphicon glyphicon-pencil"></span> Додати статтю</a></li>
        <li role="presentation" class="li-right"><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Вихід</a></li>
        <?php endif; ?>
        <?php if (!$editor): ?>
        <li role="presentation" class="li-right"><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span> Вхід</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
<div class="container">
  <div class="row body">
    <div class="col-md-8 col-md-offset-2">
