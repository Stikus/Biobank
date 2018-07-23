<?php 
 header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>ТЕСТ</title>
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="keywords" content="Ключевые слова для поисковиков">
<meta name="description" content="Описание сайта">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
</head>
<body>
	<script>
$(document).ready(function(){
    $("p").click(function(){
        $(this).hide();
        document.write('test');
    });
});
</script>
<script>

</script>
<p>If you click on me, I will disappear.</p>
<p>Click me away!</p>
<p>Click me too!</p>
<table border=2px>
	<thead>
		<tr>
			<th>
				1
			</th>
			<th>
				2
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>
				3
			</th>
			<th>
				4
			</th>
		</tr>
		<tr>
			<th>
				5
			</th>
			<th>
				6
			</th>
		</tr>
	</tbody>
</table>

</body>
</html>