<?php
	include_once("library/db_connect.php"); 
	$page_title = ' - ';
	$cur_page = $_SERVER['REQUEST_URI'];
	
	if($cur_page == '/upload.php'){
		$page_title .= 'Report Your Sighting';
	}
	else if(strpos($cur_page,'gallery.php') || strpos($cur_page,'photo.php')){
		$page_title .= 'Gallery';
	}
	else if($cur_page == '/about-hornbills.php'){
		$page_title .= 'About Hornbills';
	}
	else if($cur_page == '/species.php' ||  strpos($cur_page,'great-hornbill.php') ||  strpos($cur_page,'rufous-necked-hornbill.php') ||  strpos($cur_page,'wreathed-hornbill.php') ||  strpos($cur_page,'narcondam-hornbill.php') ||  strpos($cur_page,'white-throated-brown-hornbill.php') ||  strpos($cur_page,'oriental-pied-hornbill.php') ||  strpos($cur_page,'malabar-pied-hornbill.php') ||  strpos($cur_page,'indian-grey-hornbill.php') ||  strpos($cur_page,'malabar-grey-hornbill.php') ||  strpos($cur_page,'great-hornbill.php') ){
		$page_title .= 'Species';
	}
	else if($cur_page == '/hnap.php'){
		$page_title .= 'Hornbill Nest Adoption Program';
	}
	else if($cur_page == '/team.php'){
		$page_title .= 'Team';
	}
	else if($cur_page == '/contributors.php'){
		$page_title .= 'Contributors';
	}
	else{
		$page_title .= 'Home';
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hornbill Watch <?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<link rel="Shortcut Icon" href="images/favicon.png" type="image/x-icon" />

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=	
AIzaSyBPJjhIfQd5xju7OUHbzwfXV4XyT9NmEGg"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<?php if(strpos($cur_page,'photo.php')) { ?>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=447349448734688&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php } ?>

<?php
	if(strpos($cur_page,'photo.php')) :
		$image_id=strip_tags($_GET['p']);
        if(is_numeric($image_id))
        {
		    $dbquery="select * from  images where image_id='".$image_id."'";     
		    $images=mysqli_fetch_array(mysqli_query($con,$dbquery));
           
            
             if(is_numeric($images['sighting_id']))
            {
                $dbquery="select * from sighting where sighting_id='".$images['sighting_id']."'  ";
                $sighting_details=mysqli_query($con,$dbquery);   
                $sighting_details=mysqli_fetch_array($sighting_details);
            }   
            
            if(is_numeric($sighting_details['submitter_id']))
            {    
                $dbquery="select * from submitter where submitter_id='".$sighting_details['submitter_id']."'  ";
                $submitter_details=mysqli_query($con,$dbquery);  
                $submitter_details=mysqli_fetch_array($submitter_details);
            }
            
            if(is_numeric($sighting_details['species_id']))
            { 
                $dbquery="select species_name from species where species_id='".$sighting_details['species_id']."'  ";       
                $species_details=mysqli_query($con,$dbquery);   
                $species_details=mysqli_fetch_array($species_details);
            }
        }
        else
        {
            unset($images);
        }   
		if(file_exists('upload/Thumbnail/'.$images['filename'])) : ?>
			<meta property="og:title" content="<?php echo $species_details['species_name']; echo " by ".$submitter_details['name'] ?>" />
			<meta property="og:image" content="http://hornbills.in/upload/Thumbnail/<?php echo $images['filename'];?>"/>
		<?php 
		endif; 
	else: ?>
		<meta property="og:title" content="Hornbill Watch" />
    	<meta property="og:image" content="http://hornbills.in/images/hornbill-logo.png"/>
	<?php endif; ?>
  
<meta property="og:url" content="<?php echo 'http://hornbills.in'.$_SERVER['REQUEST_URI']; ?>" />
<meta property="og:site_name" content="Hornbill Watch"/>
<meta property="og:type" content="non_profit"/>
<meta property="og:description" content="Bird watchers & photographers report sightings of Hornbills in India. Click to view/report." />

</head>

<body>

<div class="container_12">

	<div class="header clearfix">
    <div class="grid_12">
    	
      <div class="grid_3 alpha marginbottom20">
      	<a href="index.php"><img src="images/hornbill-logo.png" alt="Hornbill Watch" width="100%" /></a>
      </div>
    
      <div class="grid_9 omega alignright">
        <ul class="main-menu">
          <li><a <?php if(strpos($cur_page,'/') || strpos($cur_page,'index.php')) : ?>class="active"<?php endif; ?> href="index.php">Home</a></li>
          <li><a <?php if( strpos($cur_page,'upload.php')) : ?>class="active"<?php endif; ?> href="upload.php">Report Your Sighting</a></li>
          <li><a <?php if(strpos($cur_page,'gallery.php') || strpos($cur_page,'photo.php')) : ?>class="active"<?php endif; ?> href="gallery.php">Gallery</a></li>

          <li><a <?php if(strpos($cur_page,'about-hornbills.php')) : ?>class="active"<?php endif; ?> href="about-hornbills.php">About Hornbills</a></li>
          <li><a <?php if( strpos($cur_page,'species.php') || strpos($cur_page,'great-hornbill.php') ||  strpos($cur_page,'rufous-necked-hornbill.php') ||  strpos($cur_page,'wreathed-hornbill.php') ||  strpos($cur_page,'narcondam-hornbill.php') ||  strpos($cur_page,'white-throated-brown-hornbill.php') ||  strpos($cur_page,'oriental-pied-hornbill.php') ||  strpos($cur_page,'malabar-pied-hornbill.php') ||  strpos($cur_page,'indian-grey-hornbill.php') ||  strpos($cur_page,'malabar-grey-hornbill.php') ||  strpos($cur_page,'great-hornbill.php') ) : ?>class="active"<?php endif; ?> href="species.php">Species</a></li>
          <li><a <?php if(strpos($cur_page,'hnap.php')) : ?>class="active"<?php endif; ?> href="hnap.php">Adopt</a></li>
          <li><a <?php if(strpos($cur_page,'team.php')) : ?>class="active"<?php endif; ?> href="team.php">Team</a></li>
        </ul>
      </div>
      
    </div>
  </div> <!-- header -->
