<?php
// Задаємо заголовок сторінки.
$page_title = 'Edit article';

require('base/header.php');

// Якщо на сторінку зайшов НЕ редактор, тоді даємо у відповідь статус 403 та пишемо повідомлення.
if (!$editor) {
  header('HTTP/1.1 403 Unauthorized');
  print 'Доступ заборонено.';
  // Підключаємо футер та припиняємо роботу скрипта.
  require('base/footer.php');
  exit;
}

// Підключення БД, адже нам необхідне підключення для редагування статті.
require('base/db.php');
// Якщо ми отримали дані з ПОСТа, тоді обробляємо їх та вставляємо.
if (isset($_POST['edit'])) {

  try {
    $stmt = $conn->prepare('UPDATE `content` SET `title` = :title, `short_desc` = :short_desc, `full_desc` = :full_desc, `timestamp` = :timestamp WHERE `id`= :id');
    // Обрізаємо усі теги у загловку. UPDATE content SET title= '$title', body= '$body', date= '$date' WHERE id='$id'
    $stmt->bindParam(':title', strip_tags($_POST['title']));
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
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
    header("Location: article.php?id={$_GET['id']}");
    exit;
  }
  else {
    // Вивід повідомлення про невдале редагування матеріалу.
    print "Запис не був доданий.";
  }
}
// Робимо запит до БД, вибираємо статтю по параметру ГЕТ.
try {
  $stmt = $conn->prepare('SELECT title, short_desc, full_desc FROM content WHERE id = :id');
  // Додаємо плейсхолдер.
  $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
  $stmt->execute();
  // Витягуємо статтю з запиту.
  $article = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  // Виводимо на екран помилку.
  print "ERROR: {$e->getMessage()}";
  // Закриваємо футер.
  require('base/footer.php');
  // Зупиняємо роботу скрипта.
  exit;
}
?>
<!-- Пишемо та заповнюємо форму даними з БД, метод ПОСТ, форма відправляє данні на цей же скрипт. -->
<div class = "row"><h1><span class="label label-warning ">Редагувати статтю: <?php print $article['title']; ?></span></h1><br></div>

<form action="/edit.php?id=<?php print $_GET['id'] ?>" method="POST">

  <div class="form-group">
    <label for="title">Заголовок</label>
    <input type="text" name="title" class="form-control" id="title" required maxlength="255" value="<?php print $article['title']; ?>">
  </div>

  <div class="form-group">
    <label for="short_desc">Короткий зміст</label>
    <textarea name="short_desc" class="form-control" rows="5" id="short_desc" required maxlength="600"><?php print $article['short_desc']; ?></textarea>
  </div>

  <div class="form-group">
    <label for="full_desc">Повний зміст</label>
    <textarea name="full_desc" class="form-control" rows="8" id="full_desc" required><?php print $article['full_desc']; ?></textarea>
  </div>

  <div class="form-group">
    <label for="date">День редагування</label>
    <input type="date" class="form-control" name="date" id="date" required value="<?php print date('Y-m-d')?>">
    <label for="time">Час редагування</label>
    <input type="time" class="form-control" name="time" id="time" required value="<?php print date('G:i')?>">
  </div>

  <input type="submit" name="edit" value="Зберегти">

</form>

<?php
// Підключаємо футер сайту.
require('base/footer.php');
?>
