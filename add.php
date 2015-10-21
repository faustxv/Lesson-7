<?php
// Задаємо заголовок сторінки.
$page_title = 'Add new article';

require('base/header.php');

// Якщо на сторінку зайшов НЕ редактор, тоді даємо у відповідь статус 403 та пишемо повідомлення.
if (!$editor) {
  header('HTTP/1.1 403 Unauthorized');
  print 'Доступ заборонено.';
  // Підключаємо футер та припиняємо роботу скрипта.
  require('base/footer.php');
  exit;
}

// Підключення БД, адже нам необхідне підключення для створення статті.
require('base/db.php');

// Якщо ми отримали дані з ПОСТа, тоді обробляємо їх та вставляємо.
if (isset($_POST['submit'])) {

  try {
    $stmt = $conn->prepare('INSERT INTO content VALUES(NULL, :title, :short_desc, :full_desc, :timestamp)');

    // Обрізаємо усі теги у загловку.
    $stmt->bindParam(':title', strip_tags($_POST['title']));

    // Екрануємо теги у полях короткого та повного опису.
    $stmt->bindParam(':short_desc', htmlspecialchars($_POST['short_desc']));
    $stmt->bindParam(':full_desc', htmlspecialchars($_POST['full_desc']));

    // Беремо дату та час, переводимо у UNIX час.
    $date = "{$_POST['date']}  {$_POST['time']}";
    $stmt->bindParam(':timestamp', strtotime($date));
    // Виконуємо запит, результат запиту знаходиться у змінні $status.
    // Якщо $status рівне TRUE, тоді запит відбувся успішно.
    $status = $stmt->execute();

  } catch(PDOException $e) {
    // Виводимо на екран помилку.
    print "ERROR: {$e->getMessage()}";
    // Закриваємо футер.
    require('base/footer.php');
    // Зупиняємо роботу скрипта.
    exit;
  }

  // При успішному запиту перенаправляємо користувача на сторінку перегляду статті.
  if ($status) {
    // За допомогою методу lastInsertId() ми маємо змогу отрмати ІД статті, що була вставлена.
    header("Location: article.php?id={$conn->lastInsertId()}");
    exit;
  }
  else {
    // Вивід повідомлення про невдале додавання матеріалу.
    print "Запис не був доданий.";
  }
}
?>
<!-- Пишемо форму, метод ПОСТ, форма відправляє данні на цей же скрипт. -->
<div class = "row"><h1><span class="label label-warning ">Додати Новину</span></h1><br>
<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="POST">

  <div class="form-group">
    <label for="title">Заголовок</label>
    <input type="text" name="title" id="title" class="form-control" required maxlength="255">
  </div>

  <div class="form-group">
    <label for="short_desc">Короткий зміст</label>
    <textarea name="short_desc" id="short_desc" rows="5" class="form-control" required maxlength="600"></textarea>
  </div>

  <div class="form-group">
    <label for="full_desc">Повний зміст</label>
    <textarea name="full_desc" id="full_desc" rows="8" class="form-control" required></textarea>
  </div>

  <div class="form-group">
    <label for="date">День створення</label>
    <input type="date" name="date" id="date" class="form-control" required value="<?php print date('Y-m-d')?>">
    <label for="time">Час створення</label>
    <input type="time" name="time" id="time" class="form-control" required value="<?php print date('G:i')?>">
  </div>

  <input type="submit" name="submit" class="btn btn-default text-uppercase btn-success" value="Зберегти">

</form>

<?php
// Підключаємо футер сайту.
require('base/footer.php');
?>
