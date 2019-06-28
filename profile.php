<?php
session_start();
if(!isset($_SESSION['id'])){
    $new_url = 'authorization.php';
    header("Location: ".$new_url);
}
$dbh = new PDO('mysql:host=localhost;dbname=pract2', "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$id = $_SESSION['id'];


$firstName = $_POST['UserFirstName'];
$lastName = $_POST['UserLastName'];
$login = $_POST['UserLogin'];
$mail = $_POST['UserEmail'];
$pass = $_POST['UserPassword'];
$news =(int) (bool)$_POST['news'];
$stock =(int)  (bool)$_POST['stock'];
$events =(int)  (bool)$_POST['events'];


$error = true;

if(isset($_REQUEST['submit'])){
	$error = false;
	if($firstName != '' && $lastName != '' && $login != '' && $mail != '' && $pass != ''){
		if (!preg_match('/[A-Za-zА-Яа-яЁё]{2,12}/', $firstName)){
			echo "В имени только буквы от 2х до 12 символов";
			$error = true;
		}

		if (!preg_match('/[A-Za-zА-Яа-яЁё]{2,14}/', $lastName)) {
			echo "В фамилии только буквы от 2 до 14 символов";
			$error = true;
		}

		if (!preg_match('/^[A-Za-zА-Яа-яЁё0-9]{3,14}/', $login)) {
			echo "В логине от 3 до 14 любых символов";
			$error = true;
		}

		if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i', $mail)) {
		echo "Введиет почту в формате sitehere.ru@my.com";
		$error = true;
		}

		if (!preg_match('/(?=^.{5,10}$)(|(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*/', $pass)) 	{
			echo "Пароль от 5 до 10 символов, хотя бы одна в верхнем регистре, один спецсимвол";
			$error = true;
		}
	} else {
		echo "Заполнить все поля";
		$error = true;}
}
if(!$error){
    $stmt = $dbh->prepare("UPDATE `users` SET `firstName`='$firstName',`lastName`='$lastName',`login`='$login',`mail`='$mail',`pass`='$pass',`news`='$news',`stock`='$stock',`events`='$events' WHERE `id`='$id'");
    $stmt->execute();
}

$stmt = $dbh->prepare("SELECT * FROM `users` WHERE `id` = '$id'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta mane="viewport" content="widht=device-width, initial-scale = 1">
	<title>Практическа 3</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
		<form  method="POST">
			<h2>Ваши данные</h2>
			<div class="line"></div>
            <label>Имя</label>
			<div  class="input-container ">
                    <img src="img/user.png" alt="user" class="ico">
					<input type="text" name="UserFirstName"  placeholder="FirstName" value="<?php echo $result['firstName']; ?>">
			</div>
            
            <label>Фамилия</label>
			<div class="input-container ">
                    <img src="img/user.png" alt="user" class="ico">
					<input type="text" name="UserLastName"  placeholder="LastName" value="<?php echo $result['lastName']; ?>">
			</div>
            
            <label>Ваш логин</label>
			<div class="input-container ">
                    <img src="img/user.png" alt="user" class="ico">
					<input type="text" name="UserLogin"  placeholder="myname1" value="<?php echo $result['login']; ?>">
			</div>
            
            <label>Ваш e-mail</label>
			<div class="input-container ">
                    <img src="img/emails.png" alt="email" class="ico">
					<input type="email" name="UserEmail" placeholder="sitehere.ru@my.com" value="<?php echo $result['mail']; ?>"> 
			</div>
            
            <label>Ваш пароль</label>
			<div class="input-container ">
                    <img src="img/key.png" alt="key" class="ico">
					<input type="password" name="UserPassword" placeholder="12345Aa!" value="<?php echo $result['pass']; ?>">
			</div>
            
            <!-- <label>Подвердите ваш пароль</label>
			<div class="input-container ">
                    <img src="img/key.png" alt="key" class="ico">
					<input type="password" name="UserRePassword" placeholder="12345Aa!" ><
					pattern="(?=^.{5,10}$)(|(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*" 
			</div> -->
			<div class="checkbox-container">
				<input type="checkbox" name="news" class="checkB" id="SubCheck1" <?php if($news==1): echo 'checked="checked"'; endif;  ?>>
				<label for="SubCheck1">Подписаться на новости</label>
			</div>
			<div class="checkbox-container">
				<input type="checkbox" name="stock" class="checkB" id="SubCheck2" <?php if($stock==1): echo 'checked="checked"'; endif;  ?>>
				<label for="SubCheck2">Подписаться на акции</label>
			</div>
			<div class="checkbox-container">
				<input type="checkbox" name="events" class="checkB" id="SubCheck3" <?php if($events==1): echo 'checked="checked"'; endif;  ?>>
				<label for="SubCheck3">Подписаться на мероприятия</label>
			</div>
			<input type="submit" name="submit" class="button" value="Изменить">
		</form>
        <a href="logOut.php">Выйти</a>
</body>
</html>
