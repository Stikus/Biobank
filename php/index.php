<?php
//Стартуем сессии
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Авторизация</title>
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta name="keywords" content="Ключевые слова для поисковиков">
		<meta name="description" content="Описание сайта">
	</head>
	<body>
		<?php
		// Проверяем, пусты ли переменные логина и id пользователя
		if (empty($_SESSION['login']) or empty($_SESSION['id']))
		{
		?>
		<!--Если пусты, то выводим форму входа.-->

		<div style="border: 0px solid blue; 
		position:relative; top:100px; left:400px; height:200px; width:300px;">

			<form action="tester.php" method="post">
				<label>логин:</label><br/>
					<input name="login" type="text" size="15" maxlength="15"><br/>
				<label>пароль:</label><br/>
					<input name="password" type="password" size="15" maxlength="15"><br/>
				<label>группа:</label><br/>
					<select name="role">
						<option value="0">Выберите роль</option>
						<?
						$dbcon = mysql_connect("localhost", "mysql", "mysql"); 
						mysql_select_db("Biobank_test", $dbcon);
						if (!$dbcon)
						{
							echo "<p>Произошла ошибка при подсоединении к MySQL!</p>".mysql_error(); exit();
						}
						else 
						{
							if (!mysql_select_db("Biobank_test", $dbcon))
							{
								echo("<p>Выбранной базы данных не существует!</p>");
							}
						}
						$res = mysql_query('select `id`, `name` from `roles`');
						while($row = mysql_fetch_assoc($res))
						{
							?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
							<?
						}
						?>
						</select><br/><br/>
				<input type="submit" value="войти"><br/><br/>
			</form>
			Здравствуйте <font color="red">гость</font>! <br/>
			Авторизуйтесь и пройдите по ссылке!
			<br/><br/><br/>
			Или <a href="forma_reg.php">зарегистрируйтесь</a>

		</div>

		<?php
		}
		else  //Иначе. 
		{
			$login=$_SESSION['login'];

			//Подключаемся к базе данных.
			$dbcon = mysql_connect("localhost", "mysql", "mysql");
			mysql_select_db("Biobank_test", $dbcon);
			if (!$dbcon)
			{
				echo "<p>Произошла ошибка при подсоединении к MySQL!</p>".mysql_error(); exit();
			} 
			else 
			{
				if (!mysql_select_db("Biobank_test", $dbcon))
				{
					echo("<p>Выбранной базы данных не существует!</p>");
				}
			}
			//Формирование оператора SQL SELECT 
			$sqlCart = mysql_query("SELECT * FROM users WHERE login = '$login'", $dbcon);
			//Цикл по множеству записей и вывод необходимых записей 
			while($row = mysql_fetch_array($sqlCart)) 
			{
				//Присваивание записей 
				$name = $row['name'];
			}
			mysql_close($dbcon);
			// Если не пусты, то мы выводим ссылку
			echo "
			<div align='center'
			style='border: 0px solid blue; position:relative; top:100px; height:100px; width:100%;'>
			<h1>
			<font color='green'>Здравствуйте: <font color='red'>".$name."</font>!</font></h1>
			<br/>
			Вы можете перейти по ссылке: <a href='testtable.php'>Таблица организаций</a>
			<br/>
			<br/>
			<br/>
			<a href='exit.php'>выйти</a>
			<br/></div>";
			
		}
		?> 
	</body>
</html>