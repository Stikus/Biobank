<?php 
 header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<title>Регистрация</title>
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta name="keywords" content="Ключевые слова для поисковиков">
		<meta name="description" content="Описание сайта">
	</head>
	<body>

		<div style="position:relative; left:10px; top:0px; height:200px; width:800px;" align="center">
		<?php 
		if ($_POST) //Условие будет выполнено, если произведен POST-запрос к скрипту.
		{
			$name = trim($_POST['rname']);
			$login = trim($_POST['rlogin']);
			$password = trim($_POST['rpass']);
			$password_r = trim($_POST['rpass_r']);
			$email = trim($_POST['email']);
			$data = date('Ymd');

			$error = false;//Создаем переменную, контролирующую ошибки регистрации.
			$errortext = "<p><b><font color='red'><h3>При регистрации на сайте произошли следующие ошибки:</h3></font></p><ul>";
			if (empty($name))
			{
				$error = true;
				$errortext .= "<li><font color='red'>Вы не заполнили поле ФИО пользователя!</font></li>";
			}
			else 
			{
				if (!preg_match("/^[a-z а-яё]{2,30}$/iu",$name))
				{
					$error = true;
					$errortext .= "<li><font color='red'>Убедитесь, что ФИО содержит от 2 до 30 символов и не содержит цифр</font></li>";
				}
			}
			if (empty($login))
			{
				$error = true;
				$errortext .= "<li><font color='red'>Вы не заполнили поле Логин пользователя!</font></li>";
			} 
			else 
			{
				if (!preg_match("/^[a-z0-9]{2,20}$/i",$login))
				{
					$error = true;
					$errortext .= "<li><font color='red'>Убедитесь, что Логин содержит от 2 до 20 символов, и состоит из латинских символов и цифр</font></li>";
				}
			}
			if (empty($password))
			{
				$error = true;
				$errortext .= "<li><font color='red'>Вы не заполнили поле Пароль!</font></li>";
			}
			else 
			{
				if (!preg_match("/^[a-z0-9]{3,20}$/i",$password))
				{
					$error = true;
					$errortext .= "<li><font color='red'>Убедитесь, что Пароль содержит от 3 до 20 символов, и состоит из латинских символов и цифр</font></li>";
				}
			}
			if (empty($password_r))
			{
				$error = true;
				$errortext .= "<li><font color='red'>Вы не заполнили поле Подтверждение пароля!</font></li>";
			} 
			else 
			{
				if ($password != $password_r)
				{
					$error = true;
					$errortext .= "<li><font color='red'>Поле Пароль и его Подтверждение не совпадают!</font></li>";
				}
			}
			if (empty($email))
			{
				$error = true;
				$errortext .= "<li><font color='red'>Вы не заполнили поле E-Mail</font></li>";
			} 
			else 
			{
				if (!preg_match("/^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,4}$/i",$email))
				{
					$error = true;
					$errortext .= "<li><font color='red'>Не правильно заполнено поле E-Mail. E-mail должен иметь вид user@somehost.com</font></li>";
				}
			}
			$errortext .= "</ul></b>";
			if ($error)
			{
				echo($errortext);//Выводим текст ошибок.
			} 
			else 
			{
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
				 // проверка на существование пользователя с таким же логином.
			    $result = mysql_query("SELECT id FROM users WHERE login='$login'",$dbcon);
			    $myrow = mysql_fetch_array($result);
			    if (!empty($myrow["id"])) 
			    {
			    	exit ("Извините, введённый вами логин уже зарегистрирован. <a href='forma_reg.php'>Введите другой логин</a>.");
			    }
				 // проверка на существование пользователя с такой же электронной почтой.
			    $result = mysql_query("SELECT id FROM users WHERE email='$email'",$dbcon);
			    $myrow = mysql_fetch_array($result);
			    if (!empty($myrow["id"])) 
			    {
			    	exit ("Извините, введённый вами E-mail уже используется другим пользователем. <a href='forma_reg.php'>Введите другой E-mail</a>.");
			    }
			    //Выполняем SQL-запрос записывающий данные пользователя в таблицу.
			    $sql = mysql_query("INSERT INTO users (name,login,password,email,data) Values ('$name','$login','$password','$email','$data')", $dbcon);
				if (!$sql)
				{
					echo "Запрос не прошел. Попробуйте еще раз.";
				}
				if ($sql)
				{
					//Выводим сообщение об успешной регистрации.	
					exit ('<div align="center"><br/><br/><br/><h3> Вы успешно зарегистрированы на сайте! Заполните <a href="index.php">форму входа</a> и войдите</h3></div>');
				}
				mysql_close($dbcon);//Закрываем соединение MySQL.
			}
		}
		if (($_POST && $error) || !$_POST)
		{
		}
		?>
		</div>
		<!--Начало формы регистрации-->
		<form id="register_form" name="register_form" method="post" action="">
			<table width="350" height="315" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFF">
				<tr>
					<td align="right">
						<b>ФИО:</b> <input type="text" name="rname" id="rname" /> 
					</td>
				</tr>
				<tr>
					<td align="right">
						<b>Логин:</b> <input type="text" name="rlogin" id="rlogin" />
					</td>
				</tr>
				<tr>
					<td align="right">
						<b>Пароль:</b> <input type="password" name="rpass" id="rpass" />
					</td>
				</tr>
				<tr>
					<td align="right">
						<b>Повторите пароль:</b> <input type="password" name="rpass_r" id="rpass_r" />
					</td>
				</tr>
				<tr>
					<td align="right">
						<b>E-Mail:</b> <input type="text" name="email" id="email" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="reg_button" id="reg_button" value="Готово" />
					</td>
				</tr>
			</table>
		</form>
		<!--Конец формы регистрации-->
	</body>
</html>