<?php
session_start();
        
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
if(!isset($_SESSION['login']))
{
    header('Location: /admin/login.php');
    exit;
}
require_once('../library/db_connect.php');
require_once('functions.php'); 
require_once('header.php');

error_reporting(0);
$submit_no = mysqli_real_escape_string($con,strip_tags($_GET['id']));
$submission_details = getAllSubmissionsBySubmitNo($submit_no,$con);

if(!empty($submission_details)){
    foreach($submission_details as $details){
        $submitter_id = isset($details['submitter_id']) ? $details['submitter_id'] : false;
    }
}

//echo '<pre>';print_r($submission_details);echo '</pre>';
if(isset($_GET['id']) and empty($submission_details))
{
    $search = 0;
    $error = 2;
}
else{
    $error =3;
}

  

    if(isset($_POST['reject'])){
        $dbquery = "UPDATE images set image_status=2 where sighting_id = $submit_no";
        $reject = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($reject){
             header('Location: index.php');
           exit;
            }
    }
        
        if(isset($_POST['update'])){
           $date = date('Y-m-d',strtotime($_POST['submit-date']));
           //$date = isset($_POST['submit-date']) ? $_POST['submit-date'] : false ;

            
//             echo '<pre>';print_r($_POST);echo '</pre>';
        if(empty($_POST['pa']))
        {
            $p_id = -1;
        }
        else
        {   
            $p_id = $_POST['pa'];
        }
            $nearest_town = isset($_POST['nearest_town']) ? $_POST['nearest_town'] : false;
            $numbers_seen = !empty($_POST['numbers_seen']) ? $_POST['numbers_seen'] : 0;
            $mobile_no = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : false;
            $sighting_date = isset($_POST['sighting_date']) ? $_POST['sighting_date'] : false;
            $state_name = isset($_POST['StateName']) ? $_POST['StateName'] : false;
            $state_id = isset($_POST['StateName']) ? $_POST['StateName'] : false;
            $Species_id = isset($_POST['species']) ? $_POST['species'] : false;
            $sighting_time = isset($_POST['sighting_time']) ? $_POST['sighting_time'] : false;
            $age_id = isset($_POST['age']) ? $_POST['age'] : false;
            $site_id = isset($_POST['typeofincident']) ? $_POST['typeofincident'] : false;
            $activity_id = isset($_POST['activity']) ? $_POST['activity'] : false;
            $habitat_id = isset($_POST['habitat']) ? $_POST['habitat'] : false;
            $sighting_description = isset($_POST['description']) ? $_POST['description'] : false;
            $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : false;
            $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : false;
            $sighting_description = mysqli_real_escape_string($con,$sighting_description);
            $name = isset($_POST['name']) ? $_POST['name'] : false;
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $mobile_no = !empty($_POST['mobile_no']) ? $_POST['mobile_no'] : null;
            $dbqueryl = "UPDATE sighting
              SET
                  `numbers_seen`  = '$numbers_seen',
                  `state_name`    = '$state_name',
                  `location_name` = '$nearest_town',
                  `pa_id`         = '$p_id',
                  `Species_id`    = '$Species_id',
                  `sighting_date` = '$date',
                  `sighting_time` = '$sighting_time',
                  `age_id`        = '$age_id',
                  `site_id`       = '$site_id',
                  `activity_id`   = '$activity_id',
                  `habitat_id`    = '$habitat_id',
                  `sighting_description` = '$sighting_description',
                  `latitude`      = '$latitude',
                  `longitude`     = '$longitude',
                  `state_id`      = '$state_id'


              WHERE `sighting_id` = '$submit_no'";

            $dbquery2 = "UPDATE submitter
              SET
                  `name`  = '$name',
                  `email_id`    = '$email',
                  `mobile_no` = '$mobile_no'

              WHERE `submitter_id` = '$submitter_id'";

            $update2 = false;
            if($name && $email){
              $update1 = mysqli_query($con,$dbqueryl);
              $update2 = mysqli_query($con,$dbquery2); 
            } else {
              echo '<pre>'.print_r('Not Npdated').'</pre>';
            }
            
            if($update2){ 
                  header('Location: index.php');
                exit;
            }
        }
        
    if(isset($_POST['accept'])) {
        $dbquery = "UPDATE images set image_status=1 where sighting_id = $submit_no";
        $accept = mysqli_query($con,$dbquery);
        if($accept){ 
           header('Location: index.php');
           exit;
        }   
    }   
    
    if(isset($_POST['delete'])){
  			$dbquery = "DELETE from sighting where sighting_id = $submit_no";
            $dbquery1 = "DELETE from submitter where submitter_id = $submitter_id ";
            $dbquery2 = "DELETE from images where sighting_id = $submit_no ";                                                                                                                        
            $update = mysqli_query($con,$dbquery);
            $update1 = mysqli_query($con,$dbquery1);
            $update2 = mysqli_query($con,$dbquery2);   
             if($update2){
             header('Location: index.php');
           exit;
            }
    }
        
  $species_details = getSpeciesDetails($con);
  $age_details = getAgeDetails($con);
  $state_details = getStateDetails($con);
  $district_details = getDistrictDetails($con);
  $sighting_type = getSightingDetails($con);
  $sighting_time = getSightingTimeDetails($con);
  $activity = getActivityDetails($con);
  $habitats = getHabitatDetails($con);
  $status = getAreaStatusDetails($con);
   
?>
<?php include_once('header.php'); ?>
<div class="grid_12 alpha omega margintop20 marginbottom20">
	<a href="/admin" class="breadcrumbs">&laquo; Back to All Submissions</a>
  <h2 class="nomargintop nomarginbottom">Submission #<?php echo $submit_no; ?></h2>
</div>

<?php 

if (!empty($submission_details)){ foreach($submission_details as $details) {
	$hasImage = $hasMap = false; 
	$lat = $details['latitude'];
	$lng = $details['longitude'];

if($details['filename'] !='NA'){
	$hasImage = true;
}
if(!empty($details['latitude'])){
	$hasMap = true;
}

?>

<?php if($hasImage) { ?>
<div class="grid_6 alpha">
	<legend>Image</legend>
  <fieldset>
    <img src="/upload/<?php echo isset($details['filename']) ? $details['filename'] : false ; ?>" style="max-width:460px">
  </fieldset> 
</div>
<?php } ?>

<?php if($hasMap) { ?>
<div class="grid_6 omega">
	<legend>Map</legend>
  <div id="map" style="width:460px; height:300px"></div>
</div>
<?php } ?>

<div class="clear"></div>

<div class="grid_12 alpha omega">
<form method="post" action="" enctype="multipart/form-data">

  <div class="grid_4 alpha">
    <legend>Submitter's Details</legend>
    <fieldset>
      <label>Name</label>
      <input type="text" name="name" value="<?php echo $details['name'];?>" id="name" />
    </fieldset>
    <fieldset>
      <label>Email</label>
      <input type="email" name="email" value="<?php echo $details['email_id']?>" id="email" />
    </fieldset>
    <fieldset>
      <label>Phone</label>
      <input type="tel" name="mobile_no" value="<?php echo $details['mobile_no']?>" id="mobile_no" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
    </fieldset>
  </div>
  
  <div class="grid_4">
    <legend>Location Details</legend>
    
    <?php if($details['pa_id'] <= 0) { 
    // if($details) { 
      ?>
   <fieldset>
      <label>State</label>
      <?php $states = getAllPAStates($con);?>
        <select id="location-state" name="StateName">
                <option value="-1">Choose a State</option>
                  <?php foreach($states as $state)
                  
            {?>
                 <option <?php if($state['State_Id']==$details['state_id']){echo "selected='selected'";} ?> value="<?php echo $state['State_Id'] ?>"> <?php echo $state['StateName']; ?> </option>
              
           <?php }?>
          
          </select> 
    </fieldset>
    
    <fieldset>
      <label>Nearest Town</label>
      <input type="text" name="nearest_town" id="town" value="<?php echo $details['location_name']; ?>" />
    </fieldset>                               
  </div>
  
  <div class="grid_4 omega">
    <legend>Location Details Continued</legend>
    <fieldset>
      <label>Latitude</label>
      <input type="text" name="latitude" value="<?php echo $details['latitude']?>" />
    </fieldset>
    <fieldset>
      <label>Longitude</label>
      <input type="text" name="longitude" value="<?php echo $details['longitude']?>" />
    </fieldset>
     <?php } else
     {
     $pa_details = getPADetailsFromPAID($details['pa_id'],$con);
     $states = getAllPAStates($con);
     ?>
     <fieldset>
      <label>State</label>
       <!--    <select id="location-state" name="StateName">
                <option value="-1">Choose a State</option>
                  <?php foreach($states as $state)
                  
            {?>
                 <option <?php if((isset($state['State_Id']) ?  $state['State_Id'] : false) == isset($pa_details['State_Id']) ? $pa_details['State_Id'] : false ){echo "selected='selected'";} ?> value="<?php echo isset($state['State_Id']) ? $state['State_Id']: false ?>"> <?php echo $state['StateName']; ?> </option>
              
           <?php }?>
      
          </select> -->

          <?php $states = getAllPAStates($con);?>
          <select id="location-state" name="StateName">
                <option value="-1">Choose a State</option>
                  <?php foreach($states as $state)
                  
            {?>
                 <option <?php if($state['State_Id']==$details['state_id']){echo "selected='selected'";} ?> value="<?php echo isset($state['State_Id']) ? $state['State_Id'] : '' ?>"> <?php echo $state['StateName']; ?> </option>
           <?php }?>
          </select> 

    </fieldset> 
    
    <?php  $state_pas = getPAForAState($con);  

    ?>
     
    <fieldset>
      <label>Protected Area</label>
          <select id="pa1" name="pa">
                <option value="-1">Choose a Protected Area</option>
                  <?php foreach($state_pas as $state_pa)
            {?>
                 <option data-state_id="<?php echo $state_pa['State_Id']; ?>" <?php if($state_pa['PA_ID']==$pa_details['PA_ID']){echo "selected='selected'";} ?> value="<?php echo $state_pa['PA_ID'] ?>"> <?php echo $state_pa['FullName']; ?> </option>
              
           <?php }?>
          
          </select>
    </fieldset> 
   <?php  }}?>        
  </div>
  
  <div class="clear"></div>
  
  <div class="grid_4 alpha">
    <legend>Sighting Details</legend>
    <fieldset>
      <label>Species</label>
     <select name="species" id="species">
            <option value="-1">Choose one</option>
            <?php if(!empty($species_details)){ foreach($species_details as $species){?>
          <option <?php if($species['species_id']==$details['species_id']){echo "selected='selected'";} ?> value="<?php echo $species['species_id'] ?>"> <?php echo $species['species_name']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
    <fieldset>
      <label>Type of Sighting</label>
          <select name="typeofincident" id="typeofincident">
            <option value="-1">Choose one</option>
            <?php if(!empty($sighting_type)){ foreach($sighting_type as $sight){?>
          <option <?php if($sight['site_id']==$details['site_id']){echo "selected='selected'";} ?> value="<?php echo $sight['site_id'] ?>"> <?php echo $sight['site_name']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
    <fieldset>
      <label>Behaviour</label>
       <select name="activity" id="activity">
            <option value="-1">Choose one</option>
            <?php if(!empty($activity)){ foreach($activity as $active){?>
          <option <?php if($active['activity_id']==$details['activity_id']){echo "selected='selected'";} ?> value="<?php echo $active['activity_id'] ?>"> <?php echo $active['activity']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
  </div>
  <div class="grid_4">
    <legend>Sighting Details Continued</legend>
    <fieldset>
      <label>Date</label>
      <?php
        $NewStDate = new DateTime($details['submitting_time_details']);
        $stripDate = $NewStDate->format('Y-m-d');
      ?>
      <input type="date" class="datepicker" name="submit-date" value="<?php echo $details['sighting_date'] != "0000-00-00" ? $details['sighting_date'] : $stripDate; ?>" />
    </fieldset>
    <fieldset>
      <label>Time</label>
      <select name="sighting_time" id="sighting_time">
            <option value="-1">Choose one</option>
            <?php if(!empty($sighting_time)){ foreach($sighting_time as $time){ print_r($sighting_time);?>
          <option <?php if($time['sighting_time_id']==$details['sighting_time']){echo "selected='selected'";} ?> value="<?php echo $time['sighting_time_id'] ?>"> <?php echo $time['sighting_time']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
    <fieldset>
      <label>Habitat Type</label>
      <select name="habitat" id="habitat">
            <option value="-1">Choose one</option>
            <?php if(!empty($habitats)){ foreach($habitats as $habitat){?>
          <option <?php if($habitat['habitat_id']==$details['habitat_id']){echo "selected='selected'";} ?> value="<?php echo $habitat['habitat_id'] ?>"> <?php echo $habitat['habitat_type']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
  </div>
  <div class="grid_4 omega">
    <legend>Sighting Details Continued</legend>
    <!--<fieldset>
      <label>Status of the Area</label>
      <select name="status" id="status">
            <option value="-1">Choose one</option>
            <?php if(!empty($status)){ foreach($status as $area){?>
          <option <?php if($area['status_id']==$details['status_id']){echo "selected='selected'";} ?> value="<?php echo $area['status_id'] ?>"> <?php echo $area['status']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset> -->
    <fieldset>
      <label>Numbers Seen</label>
      <input type="number" name="numbers_seen" id="numbers_seen" value=<?php echo isset($details['numbers_seen']) ? $details['numbers_seen']: 0 ?> />
    </fieldset>
    <fieldset>
      <label>Age/Sex of Birds</label>
     <select name="age" id="age">
            <option value="-1">Choose one</option>
            <?php if(!empty($age_details)){ foreach($age_details as $age){?>
          <option <?php if($age['age_id']==$details['age_id']){echo "selected='selected'";} ?> value="<?php echo isset($age['age_id']) ? $age['age_id'] : 0; ?>"> <?php echo $age['age']; ?> </option>
          <?php } } ?>
          </select>
    </fieldset>
     
  </div>
   <div class="grid_12 alpha omega">
   <legend>Sighting Details Continued</legend> 
       <fieldset>
      <label>Sighting Description</label>
      <textarea cols="" rows="" name="description" ><?php echo $details['sighting_description']?></textarea> 
    </fieldset>
   </div>   
   
   
   
  <div class="grid_12 alpha omega text-right">
    <div class="well">
    	<input type="submit" class="btn btn-info" name ="update" value="Update" > 
    	<input type="submit" class="btn btn-warning" name ="delete" value="Delete" onClick="if(!confirm('Are you sure you want to delete this submission, it will delete the complete entry from the database?')) return false;" > 
      <?php $status = !empty($details['image_status']) ? $details['image_status'] : 0 ?>
    	    <?php if( $status == 1) {
           ?>
               <input type="submit" class="btn btn-danger" name="reject" value="Reject" onClick="if(!confirm('Are you sure you want to reject this submission?')) return false;">
               <?php } ?>
               
               <?php if($status  == 2) { ?>
              <input type="submit" class="btn btn-success" name ="accept" value="Accept" >
               <?php } ?>
            
             <?php if($status  == 0) { ?>
               <input type="submit" class="btn btn-danger" name="reject" value="Reject" onClick="if(!confirm('Are you sure you want to reject this submission?!')) return false;"> 
               <input type="submit" class="btn btn-success" name ="accept" value="Accept" >
               <?php } ?>
        
        </form>
        
    </div>
  </div>
    
</div>
<?php } ?>

<!-- <?php if($hasMap) { ?> -->

  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDio6YMDu3GzKxk7wSlYCTazbecT0Ysev4&sensor=false"></script>

<!--	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzXmUzWIXHKun7m5Mwu5Z1SFA53OHKCWE&sensor=false"></script> -->
  <script type="text/javascript">
  function initMaps(){
    console.log('initMaps');
    var map = new google.maps.Map( document.getElementById('map'), {
        zoom: 4,
        mapTypeControl: true,
        scaleControl: true,
        mapTypeId: 'roadmap'
    });
    
    var geocoder = new google.maps.Geocoder();
    
    geocoder.geocode( { 'address': 'India'}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
      } 
    });
    
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>),
      map: map
    });
  }
  
  window.onload = initMaps;
  </script> 
<?php } ?>

<?php include_once('footer.php'); ?>
