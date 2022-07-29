<?php
require_once("library/db_connect.php"); 
        
$image_id = strip_tags($_GET['p']);
if(is_numeric($image_id ))
$image_details=getImageDetailsdFromImageId($image_id,$con);
else
unset($image_details);
if(!empty($image_details)) 
{  
$dbquery="select * from sighting where sighting_id='". mysqli_real_escape_string($con,$image_details['sighting_id'])."'  ";
$sighting_details=mysqli_query($con,$dbquery);   
$sighting_details=mysqli_fetch_array($sighting_details);

$dbquery="select * from submitter where submitter_id='".mysqli_real_escape_string($con,$sighting_details['submitter_id'])."'  ";
$submitter_details=mysqli_query($con,$dbquery);  
$submitter_details=mysqli_fetch_array($submitter_details);

$dbquery="select species_name from species where species_id='".mysqli_real_escape_string($con,$sighting_details['species_id'])."'  ";    
$species_details=mysqli_query($con,$dbquery);   
$species_details=mysqli_fetch_array($species_details);

$dbquery ="select age from age_details where age_id ='".mysqli_real_escape_string($con,$sighting_details['age_id'])."' "; 
$age_details=mysqli_query($con,$dbquery);   
$age_details=mysqli_fetch_array($age_details);


$dbquery = "select image_id from images where image_id = (select max(image_id) from images where image_id < ".mysqli_real_escape_string($con,$image_id)." and image_status = 1)";
$prev_photo=mysqli_query($con,$dbquery);   
$prev_photo=mysqli_fetch_array($prev_photo);

$dbquery = "select image_id from images where image_id = (select min(image_id) from images where image_id > ".mysqli_real_escape_string($con,$image_id)." and image_status = 1)";
$next_photo=mysqli_query($con,$dbquery);   
$next_photo=mysqli_fetch_array($next_photo);
}

include_once('header.php');
?>

<div class="content clearfix">

	<div class="grid_12"><div class="breadcrumbs"><a href="gallery.php">&laquo; Back to Gallery</a></div></div>

  <div class="grid_8">
    <?php if(empty($image_details)) { ?>
    <p>The photo you are looking for does not exist. <a href="gallery.php">Click here to return to the Gallery</a>.</p>
    <?php } else {?>
    
    <div class="photo-wrap">
      <?php 
          if(file_exists('upload/Large/'.$images['filename'])) : echo "<img src='upload/Large/".$images['filename']." ' />";
          else: echo "<img src='upload/".$images['filename']." ' />";
          endif;
        ?>
    </div>
    
    <?php 
			$fb_share_link = "https://www.facebook.com/dialog/feed?app_id=447349448734688&link=".urlencode("http://www.hornbills.in/photo.php?p=".$_GET['p'])."&picture=http://www.hornbills.in/upload/Thumbnail/".$images['filename']."&name=".urlencode($species_details['species_name']." by ".$submitter_details['name'])."&caption=&description=".urlencode("Bird watchers & photographers report sightings of Hornbills in India. Click to view.")."&redirect_uri=".urlencode("http://www.hornbills.in/photo.php?p=".$_GET['p']);
    ?>
    
    <div class="photo-actions"> 
    
    	<span class="prev-photo-link">
      <?php if(isset($next_photo['image_id']) ? $next_photo['image_id'] : false) : ?>
      <a href="<?php echo "photo.php?p=".$next_photo['image_id']; ?>">&laquo; Previous photo</a>
      <?php endif; ?>
      </span>
      
      <span class="fb-share-link"><a href="<? echo $fb_share_link; ?>" target="_blank">Share on Facebook</a></span>
      
      <span class="next-photo-link">
      <?php if($prev_photo['image_id']) : ?>
      <a href="<?php echo "photo.php?p=".$prev_photo['image_id']; ?>">Next photo &raquo;</a>
      <?php endif; ?>
      </span>
      
    </div>
    
    <div class="photo-comments clearfix"> <span class="post-comment">Post Comment</span> 
      <div class="fb-comments" data-href="<?php echo getUrl(); ?>" data-numposts="15" data-width="620" data-colorscheme="dark"></div>
    </div>
    <?php  } // no photo ?>
  </div>
  <div class="grid_4">
  	<?php if(!empty($image_details)) {  //echo '<pre>';print_r($age_details);echo '</pre>';  ?>
    <h2><?php echo $species_details['species_name']; ?></h2>
            <h3>  <?php if(isset($age_details['age']) ? $age_details['age'] : false != 'Unknown')
                    echo isset($age_details['age']) ? $age_details['age'] : false; ?>   </h3>
    
    <?php
        if($sighting_details['pa_id'] > 0 )
        {
          
            $pa_details = getPADetailsFromPAID($sighting_details['pa_id'],$con);
        ?>
        
    <h3 class="muted regular-text"><?php echo stripslashes($pa_details['FullName']).', '.stripslashes($pa_details['StateName']); ?></h3>
    <?php 
        }
        elseif(!empty($sighting_details['state_id']))
        {
            $state_name = getStateNameFromStateID($sighting_details['state_id'],$con)
             
    ?>
    <h3 class="muted regular-text"><?php echo stripslashes($state_name['StateName']); ?></h3>
    <?php } ?>
    <h3 class="muted regular-text"><?php echo stripslashes($submitter_details['name']); ?></h3>
    
    <?php
			$sighting_date = intval(substr($sighting_details['sighting_date'],0,4));
			if($sighting_date >= 2000) { 
		?>
    	<h3 class="muted regular-text"><?php echo $sighting_date; ?></h3>
    <?php } ?>
    
    <?php
        if(!empty($sighting_details['sighting_description']))
        echo '<p>'.$sighting_details['sighting_description'].'</p>';
    ?>
    <!--<p class="centertext"><a href="upload.php" class="btn">UPLOAD YOUR IMAGE</a></p>-->
    <?php } ?>
    
  </div>
</div>
<!-- content -->

<?php

	function getUrl() {
		$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
		//$url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
		$url .= $_SERVER["REQUEST_URI"];
		return $url;
	}

 function getImageDetailsdFromImageId($image_id,$con)
 {
     if(!is_numeric($image_id))
     return null;
     $dbquery = "select * from  images where image_id='".mysqli_real_escape_string($con,$image_id)."' and image_status = '1'" ;  
        $result = mysqli_query($con,$dbquery);

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                 $records=$row;
            }
            return $records;
        }
        return Null;
 }

 
   function getPADetailsFromPAID($pa_id,$con)
    {
    if(!is_numeric($pa_id)){
        return null;
    }
     
        $dbquery = "SELECT p.PA_ID, p.FullName, s.State_Id, s.StateName from pa_master p, state_master1 s  Where p.PA_ID = ".mysqli_real_escape_string($con,$pa_id)." AND p.State_Id = s.State_Id  order by PA_ID desc" ;
        $result = mysqli_query($con,$dbquery);
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {

                $records[] = $row;
            }
            return $records[0];
        }
        return null;
    }   

   function getStateNameFromStateID($s_id,$con)
   
    {
        
        
        if(!is_numeric($s_id))
     return null;
        $dbquery = "select * from state_master1 where State_Id = ".mysqli_real_escape_string($con,$s_id)."" ;
        $result = mysqli_query($con,$dbquery);
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records[0];
        }
        return null;
    }   
include_once('footer.php'); 


?>
