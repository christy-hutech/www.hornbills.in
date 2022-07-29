<? $cur_page = $_SERVER['REQUEST_URI'] ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hornbill Watch</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="Shortcut Icon" href="../images/favicon.png" type="image/x-icon" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/custom.js"></script> 
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
</head>

<body>

<div class="container_12">

	<div class="header clearfix">
    <?php if (isset($_SESSION['login'])) {?> 
    <a href="logout.php" class="alignright header-links btn btn-danger">Logout</a>
    <?php } ?>
    <h1>Hornbill Watch</h1>
     </div> <!-- header -->
