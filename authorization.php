<?php
session_start();
$dbh = new PDO('mysql:host=localhost;dbname=pract2', "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$login = $_POST['UserLogin'];
$pass = $_POST['UserPassword'];
$error = true;


if(isset($_REQUEST['submit'])){
	$error = false;
	if($login != '' && $pass != ''){
		if (!preg_match('/^[A-Za-zА-Яа-яЁё0-9]{3,14}/', $login)) {
			echo "В логине от 3 до 14 любых символов";
			$error = true;
		}

		if (!preg_match('/(?=^.{5,10}$)(|(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*/', $pass)) 	{
			echo "Пароль от 5 до 10 символов, хотя бы одна в верхнем регистре, один спецсимвол";
			$error = true;
		}
	} else {
		echo "Заполнить все поля";
		$error = true;
	}
}
if(!$error){
	$stmt = $dbh->prepare("SELECT `id`,`login`, `pass` FROM `users` WHERE `login`='$login' AND `pass`='$pass'");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(!isset($result[0])){
		echo "Пользователя с такими данными не существует";
	}else{
		$_SESSION['id']=$result[0]['id'];
		$new_url = 'profile.php';
		header('Location: '.$new_url);

	}
}


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
			<h2>Авторизация</h2>
			<div class="line"></div>
            
            <label>Ваш логин</label>
			<div class="input-container ">
                    <img src="img/user.png" alt="user" class="ico">
					<input type="text" name="UserLogin"  placeholder="myname1">
			</div>
            
           
            <label>Ваш пароль</label>
			<div class="input-container ">
                    <img src="img/key.png" alt="key" class="ico">
					<input type="password" name="UserPassword" placeholder="12345Aa!">
			</div>
            
			<input type="submit" name="submit" class="button" value="Вход">
		</form>
		<a href="index.php">Регистрация</a>
</body>
</html>
