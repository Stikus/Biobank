<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<html>
	<head>
		<title>Таблица организаций</title>
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
		<style>
		.inp { width: 100%; }
		.links { word-break: break-all; font-size:8;}
		th {font-weight:normal;}
		table {table-layout: fixed}
		.dt-buttons{left:2%;}
		</style>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 		<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
 		<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
 		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
 		<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
		<script>
			$(document).ready(function() {
			    // Setup - add a text input to each footer cell
			    $('#orgsTable tfoot th').each( function () {
			        var title = $(this).text();
			        $(this).html( '<input type="text" class="inp" placeholder="Поиск '+title+'" />' );
			    } );
			    $('#orgsTable tr.srch th').each( function () {
			        var title = $(this).text();
			        $(this).html( '<input type="text" class="inp" placeholder="Поиск '+title+'" />' );
			    } );
			 
			    // DataTable
			    var table = $('#orgsTable').DataTable(  {
			        "language": {
					  "processing": "Подождите...",
					  "search": "Поиск:",
					  "lengthMenu": "Показать _MENU_ записей",
					  "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
					  "infoEmpty": "Записи с 0 до 0 из 0 записей",
					  "infoFiltered": "(отфильтровано из _MAX_ записей)",
					  "infoPostFix": "",
					  "loadingRecords": "Загрузка записей...",
					  "zeroRecords": "Записи отсутствуют.",
					  "emptyTable": "В таблице отсутствуют данные",
					  "paginate": {
					    "first": "Первая",
					    "previous": "Предыдущая",
					    "next": "Следующая",
					    "last": "Последняя"
					  },
					  "aria": {
					    "sortAscending": ": активировать для сортировки столбца по возрастанию",
					    "sortDescending": ": активировать для сортировки столбца по убыванию"
					  }
					},
					aLengthMenu: [
				        [10, 25, 50, 100, 200, -1],
				        [10, 25, 50, 100, 200, "Все"]
				    ],
				    dom: 'lBfrtip',
        			buttons: [
			            'copy', 'csv', 'excel',
			            // 'pdf', 'print'
			        ],
			        orderCellsTop: true,
			    } );
			 
			    // Apply the search
			    table.columns().every( function () {
			        var that = this;
			 
			        $( 'input', this.footer() ).on( 'keyup change', function () {
			            if ( that.search() !== this.value ) {
			                that
			                    .search( this.value )
			                    .draw();
			            }
			        } );
			    } );
	 
				$.each($('.inp-sr', table.table().header()), function () {
				    var column = table.column($(this).index());

				    $( 'input', this).on( 'keyup change', function () {
				        if ( column.search() !== this.value ) {
				            column
				                .search( this.value )
				                .draw();
				        }
				    } );
				} );
			} );
		</script>
		<script>
			function newMyWindow1(href) {
			  var d = document.documentElement,
			      h = 700,
			      w = 900,
			      myWindow = window.open(href, 'myWindow', 'scrollbars=1,height='+Math.min(h, screen.availHeight)+',width='+Math.min(w, screen.availWidth)+
			      	',left='+Math.max(0, ((d.clientWidth - w)/2 + window.screenX))+',top='+Math.max(0, ((d.clientHeight - h)/2 + window.screenY)));
			      // абзац для Chrome
			      if (myWindow.screenY >= (screen.availHeight - myWindow.outerHeight)) {myWindow.moveTo(myWindow.screenX, (screen.availHeight - myWindow.outerHeight))};
			      if (myWindow.screenX >= (screen.availWidth - myWindow.outerWidth)) {myWindow.moveTo((screen.availWidth - myWindow.outerWidth), myWindow.screenY)};
				return true;
			}
		</script>
	</head>
	<body>
		<?php
		// Проверяем, пусты ли переменные логина и id пользователя
		if (empty($_SESSION['login']) or empty($_SESSION['id']))
		{
		?>
		<!--Если пусты, то даем ссылку на страницу авторизации.-->
		</br></br></br></br></br></br>
		<h1 align="center">Пожалуйста, <a href="index.php">авторизируйтесь</a>!</h1>

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
			// Если не пусты, то мы выводим таблицу
			?>
			<table id="orgsTable" class="display" cellspacing="0" width="100%" border="1px">
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
				$res = mysql_query('select * from `orgs`');
			?>
			    <thead>
			        <tr>
			        	<? $row = mysql_fetch_assoc($res) ?>
			            <th style="width: 5%;">ID</th>
	<!--		            <th><?//echo $row['name'] ?></th>
			            <th><?//echo $row['usable'] ?></th>
			            <th><?//echo $row['doubles'] ?></th> -->
			            <th style="width: 30%;"><?echo $row['fullname'] ?></th>
			            <th style="width: 10%;"><?echo $row['shortname'] ?></th>
			            <th style="width: 8%;"><?echo $row['abbr'] ?></th>
			            <th style="width: 6%;"><?echo $row['parent'] ?></th>
			            <th style="width: 5%;">ИНН</th>
			            <th style="width: 10%;"><?echo $row['INN link'] ?></th>
			            <th style="width: 10%;"><?echo $row['sourse link'] ?></th>
			            <th style="width: 7%;">Дата актуализации</th>
			            <th style="width: 9%;"><?echo $row['comment'] ?></th>
			        </tr>
			    	<tr class="srch">
			        	<?
			        	$head = mysql_query('select * from `orgs` where `id`=0');
			        	$rowhead = mysql_fetch_assoc($head) 
			        	?>
			            <th class="inp-sr" style="padding: 5px">ID</th>
	<!--		            <th class="inp-sr" style="padding: 5px"><?//echo $rowhead['name'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?//echo $rowhead['usable'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?//echo $rowhead['doubles'] ?></th> -->
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['fullname'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['shortname'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['abbr'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['parent'] ?></th>
			            <th class="inp-sr" style="padding: 5px">ИНН</th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['INN link'] ?></th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['sourse link'] ?></th>
			            <th class="inp-sr" style="padding: 5px">Дата актуализации</th>
			            <th class="inp-sr" style="padding: 5px"><?echo $rowhead['comment'] ?></th>
			        </tr>
			    </thead>
			    <tbody>
			    	<? while($row = mysql_fetch_assoc($res))
							{
								if ($row['fullname'])
								{
									?>
									<tr>
							            <th><?echo $row['id'] ?></th>
		<!--					            <th><?//echo $row['name'] ?></th>
							            <th><?//echo $row['usable'] ?></th>
							            <th><?//echo $row['doubles'] ?></th> -->
							            <th><?echo $row['fullname'] ?></th>
							            <th><?echo $row['shortname'] ?></th>
							            <th><?echo $row['abbr'] ?></th>
							            <th><?echo $row['parent'] ?></th>
							            <th><?echo $row['INN'] ?></th>
							            <th class="links"><a href="<? echo $row['INN link']?>" onclick="return !newMyWindow1(this.href)"><? echo $row['INN link']?></a></th>
							            <th class="links"><a href="<? echo $row['sourse link']?>" onclick="return !newMyWindow1(this.href)"><? echo $row['sourse link']?></a></th>
							            <th><?if ($row['date']>0) {echo $row['date']; }?></th>
							            <th><?echo $row['comment'] ?></th>
									</tr>
									<?
								}
							}
					?>
			    </tbody>
				<tfoot>
			        <tr>
			        	<?
			        	$head = mysql_query('select * from `orgs` where `id`=0');
			        	$rowhead = mysql_fetch_assoc($head) 
			        	?>
			            <th style="padding: 5px">ID</th>
	<!--		            <th style="padding: 5px"><?//echo $rowhead['name'] ?></th>
			            <th style="padding: 5px"><?//echo $rowhead['usable'] ?></th>
			            <th style="padding: 5px"><?//echo $rowhead['doubles'] ?></th> -->
			            <th style="padding: 5px"><?echo $rowhead['fullname'] ?></th>
			            <th style="padding: 5px"><?echo $rowhead['shortname'] ?></th>
			            <th style="padding: 5px"><?echo $rowhead['abbr'] ?></th>
			            <th style="padding: 5px"><?echo $rowhead['parent'] ?></th>
			            <th style="padding: 5px">ИНН</th>
			            <th style="padding: 5px"><?echo $rowhead['INN link'] ?></th>
			            <th style="padding: 5px"><?echo $rowhead['sourse link'] ?></th>
			            <th style="padding: 5px">Дата актуализации</th>
			            <th style="padding: 5px"><?echo $rowhead['comment'] ?></th>
			        </tr>
			    </tfoot>		
			</table>
		<div align='center'	style='border: 0px solid blue; position:relative; bottom:20px; width:100%;'>
			<a href='exit.php'>Выйти на главную страницу</a>
		</div>
		<?
		}
		?>
	</body>
</html>