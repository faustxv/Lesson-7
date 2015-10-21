<?php
// Задаємо заголовок сторінки.
$page_title = 'Delete article';

require('base/header.php');

// Якщо на сторінку зайшов НЕ редактор, тоді даємо у відповідь статус 403 та пишемо повідомлення.
if (!$editor) {
  header('HTTP/1.1 403 Unauthorized');
  print 'Доступ заборонено.';
  // Підключаємо футер та припиняємо роботу скрипта.
  require('base/footer.php');
  exit;
}
// Підключення БД, адже нам необхідне підключення для видалення статті.
require('base/db.php');
// Робимо запит до БД, вибираємо статтю по параметру ГЕТ.
try {
	$stmt = $conn->prepare('SELECT title FROM content WHERE id = :id');
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
// Якщо ми отримали дані з ПОСТа, тоді обробляємо їх та вставляємо.
if (isset($_POST['del'])) {

	try {
		$stmt = $conn->prepare('DELETE FROM content WHERE id = :id');
		// Додаємо плейсхолдер.
		$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
		$stmt->execute();
		header('location:/');

	} catch(PDOException $e) {
		// Виводимо на екран помилку.
		print "ERROR: {$e->getMessage()}";
		// Закриваємо футер.
		require('base/footer.php');
		// Зупиняємо роботу скрипта.
		exit;
	}
}
?>
<div class = "row"><h1><span class="label label-warning">Delete article: <?php print $article['title']; ?></span></h1><br></div>
	<form class="form-horizontal" action="<?php print $_SERVER["PHP_SELF"].'?id='.$_GET['id']; ?>" method="POST">
		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" name="del" class="btn btn-default text-uppercase btn-danger">Delete</button>
				<a href="/" class="btn btn-success text-uppercase">Back</a>
			</div>
		</div>
	</form>
</div>
