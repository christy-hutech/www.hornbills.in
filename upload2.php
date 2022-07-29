<?php
include_once("library/db_connect.php"); 
include_once('header.php');

$pa_states = getAllPAStates($con); 
$state_options = '';
foreach($pa_states as $pa_state)
{
		$state_options .=  '<option value="'.$pa_state['State_Id'].'" data-name="'.$pa_state['StateName'].'">'.$pa_state['StateName'].'</option>';
}
?>

<div class="content upload-content clearfix">
  <div class="grid_12">
    <form id="couponForm" method="post" action="thank-you.php"  enctype="multipart/form-data">
      <div class="grid_4 alpha form-section">
        <h2>Report Your Sighting</h2>
        <p class="marginright20 nomarginbottom">The information you share is valuable and can help identify important sites for hornbills and the threats they face. It can help in initiating conservation action in some sites. You can share just a sighting or a sighting with an image. Images of any quality are useful and welcome! </p>
      </div>
      <div class="grid_4 form-section">
        <legend>Your Details</legend>
        <fieldset>
          <?php if (isset($_COOKIE))
            { ?>
          <label>Your Name*</label>
          <input type="text" name="name"  value= "<?php echo $_COOKIE['SubmitterName'];?>"id="name" />
        </fieldset>
        <fieldset>
          <label>Email*</label>
          <input type="text" name="email" value="<?php echo $_COOKIE['EmailId']?>" id="email" />
        </fieldset>
        <fieldset>
          <label>Phone</label>
          <input type="text" name="mobile_no" value="<?php echo $_COOKIE['MobileNo']?>" id="mobile_no" />
        </fieldset>
        <?php }?>
      </div>
      <div class="grid_4 omega form-section">
        <fieldset>
          <legend>Upload Image</legend>
          <label>Choose file (optional)</label>
          <input type="file" name="upload-image" id="upload-image" >
          <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
          <p class="upload-hint">Maximum file size is 300KB. Suggested image size would be 1024 pixels on its longest side. Please avoid borders and photographer name on the image.</p>
          <p class="upload-hint">If you are facing difficulties uploading, please email your image with all of the above information to <a href="mailto:aparajita@ncf-india.org">Hornbill Watch support</a>.</p>
        </fieldset>
      </div>
      <hr class="hr" />
      <div class="grid_4 alpha form-section">
        <legend>Specify Location</legend>
        <fieldset>
          <label>Where was this sighting?</label>
          <select id="location-type" name="location-type">
            <option value="pa" selected>Inside a protected area</option>
            <option value="latlng">Outside a protected area</option>
          </select>
        </fieldset>
        <div id="location-type-pa" class="location-type-option active">
          <fieldset>
            <label>Specify the state</label>
            <select id="location-state" name="location-state">
              <option value="-1">Choose one</option>
              <?php echo $state_options; ?>
            </select>
          </fieldset>
          <fieldset>
          	<label>Specify the protected area</label>
            <select id="location-pa" name="location-pa">
              <option value="-1">Choose a state first</option>
            </select>
          </fieldset>
        </div>
        <div id="location-type-latlng" class="location-type-option">
        	<label>Specify the location on a map</label>
          <div class="location-wrap"> <a href="#modal" class="fancybox"><img src="images/map.jpg" width="274" height="174" /><span class="location-text">Click on the map to pin the location.</span></a> </div>
          <div id="modal">
            <div class="modal-header">
              <div class="container_12">
              	<div class="grid_10 alpha omega">
                	<p>It is important for us to have information on the exact location of sighting. We request you to pin the area where you saw the hornbill/s so as to get the latitude and longitude of the place.</p>
                  <p>Choose a state and enter the name of the nearest town (optional) and the map will zoom in. Then, click on the exact location of the sighting to automatically capture the latitude &amp; longitude.</p>
                </div>
                <div class="grid_3 alpha">
                  <fieldset class="nomarginbottom">
                    <label>State</label>
                   
                    <select id="state" name="state">
                    <option value="-1">Choose a state</option>
                    <?php echo $state_options; ?>
                    </select>
                  </fieldset>
                </div>
                <?php /*<div class="grid_3">
                  <fieldset class="nomarginbottom">
                    <label>District</label>
                    <input type="text" name="district" id="district" readonly value="" />
                  </fieldset>
                </div> */ ?>
                <div class="grid_3">
                  <fieldset class="nomarginbottom">
                    <label>Nearest Town</label>
                    <input type="text" name="town" id="town" value="" />
                  </fieldset>
                </div>
                <div class="grid_4 omega">
                	<fieldset class="nomarginbottom">
                    <label>Latitude &amp; Longitude*</label>
                    <input type="text" name="latitude_longitude" id="latlong" readonly value="" />
                  </fieldset>
                </div>
              </div>
            </div>
            <!-- modal-header -->
            <div class="modal-content">
              <div id="map" style="width:805px; height:400px"></div>
            </div>
            <!-- modal-content -->
            <div class="modal-footer">
              <input type="button" class="btn btn-large close-modal" value="Done" />
            </div>
            <!-- modal-footer --> 
          </div>
          <!-- modal --> 
        </div>
      </div>
      <div class="grid_4 form-section">
        <legend>Sighting Details</legend>
        <fieldset>
          <label>Species*</label>
          <select name="species" id="species">
            <option value="-1">Choose one</option>
            <?php
              $query = mysql_query('SELECT * FROM species ORDER BY species_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['species_id'].">".$row['species_name']."</option>";
              }
              ?>
          </select>
        </fieldset>
        <fieldset>
          <label>Type of Sighting</label>
          <select name="typeofincident" id="typeofincident">
            <option value="-1">Choose one</option>
            <?php
              $query = mysql_query('SELECT * FROM sight_seeing ORDER BY site_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
              	$sighting_type = str_replace('Direct ','',$row['site_name']);
                  echo "<option value=".$row['site_id'].">".$sighting_type."</option>";
              }
              ?>
          </select>
        </fieldset>
        <fieldset>
          <label>Numbers Seen</label>
          <input type="text" name="numbers_seen" id="numbers_seen" />
        </fieldset>
      </div>
      <div class="grid_4 omega form-section">
        <legend>Sighting Details Continued</legend>
        
        <div class="grid_4 alpha">
          <fieldset>
            <label>Date</label>
            <select class="select-day" name="day">
            	<option value="-1">Day</option>
              <?php 
								for($day = 1; $day <= 31; $day++) {
									echo '<option value="'.$day.'">'.$day.'</option>';
								} 
							?>
            </select>
            
            <select class="select-month" name="month">
            	<option value="-1">Month</option>
              <option value="01">January</option>
              <option value="02">February</option>
              <option value="03">March</option>
              <option value="04">April</option>
              <option value="05">May</option>
              <option value="06">June</option>
              <option value="07">July</option>
              <option value="08">August</option>
              <option value="09">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>
            
            <select class="select-year" name="year">
            	<option value="-1">Year</option>
              <?php 
								for($year = 2000; $year <= 2015; $year++) {
									echo '<option value="'.$year.'">'.$year.'</option>';
								} 
							?>
            </select>
            
          </fieldset>
        </div>
        
        <!--<div class="grid_2 omega">
          <fieldset>
            <label>Status of the Area</label>
            <select name="status" id="status">
              <?php /*
               $query = mysql_query('SELECT * FROM area_status ORDER BY status_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['status_id'].">".$row['status']."</option>";
              } */
               ?>
            </select>
          </fieldset>
        </div>-->
        
        <div class="grid_2 alpha">
          <fieldset>
            <label>Time</label>
            <select name="sighting_time" id="sighting_time">
              <option value="-1">Choose One</option>
              <?php
               $query = mysql_query('SELECT * FROM sighting_time_details ORDER BY sighting_time_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['sighting_time_id'].">".$row['sighting_time']."</option>";
              }
               ?>
            </select>
          </fieldset>
        </div>
        <div class="grid_2 omega">
          <fieldset>
            <label>Age/Sex of Birds</label>
            <select name="age" id="age">
              <option value="-1">Choose One</option>
              <?php
               $query = mysql_query('SELECT * FROM age_details ORDER BY age_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['age_id'].">".$row['age']."</option>";
              }
               ?>
            </select>
          </fieldset>
        </div>
        <div class="grid_2 alpha">
          <fieldset>
            <label>Habitat Type</label>
            <select name="habitat" id="habitat">
              <option value="-1">Choose One</option>
              <?php
               $query = mysql_query('SELECT * FROM habitat_type ORDER BY habitat_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['habitat_id'].">".$row['habitat_type']."</option>";
              }
               ?>
            </select>
          </fieldset>
        </div>
        <div class="grid_2 omega">
          <fieldset>
            <label>Behaviour</label>
            <select name="activity" id="activity">
              <option value="-1">Choose one</option>
              <?php
              $query = mysql_query('SELECT * FROM activity ORDER BY activity_id ASC');
              while ( $row = mysql_fetch_assoc($query) )
              {
                  echo "<option value=".$row['activity_id'].">".$row['activity']."</option>";
              }
              ?>
            </select>
          </fieldset>
        </div>
      </div>
      <hr class="hr" />
      <div class="grid_6 alpha form-section">
        <fieldset>
          <legend>Sighting Description</legend>
          <label>Tell us more about the sighting (optional)</label>
          <textarea cols="26" rows="5" name="sightingdesc"></textarea>
        </fieldset>
      </div>
      <div class="grid_6 omega form-section">
			<!--  <img src="captcha.php" id="captcha" /><br/>
			<a href="#" onclick="
			    document.getElementById('captcha_img').src='captcha.php?'+Math.random();
			    document.getElementById('captcha-form').focus();"
			    id="change-image">Not readable? Change text.</a><br/><br/>
			<input type="text" name="captcha" id="captcha-form" autocomplete="off" /><br/> -->
        <p>
          <input type="checkbox" class="checkbox" id="tnc" checked="checked" />
          I am happy to share this information to populate the database as part of a global database sharing project. I am the owner of the image and own the copyrights for the same.</p>
        <fieldset>
          <input type="submit" class="btn btn-large submit" value="Submit" />
        </fieldset>
      </div>
      <div class="grid_12 alpha omega">
        <hr class="hr" />
        <!--<p class="small-text">Please note: Specific spatial data will not be shared on the open platform but will be provided only to the IPBES project team. We understand the problems of sharing specific locations of endangered wild species. However, a rough overall map of the locations of the different species will be provided on this web page at the end of the project.</p>--> 
        <span>The data generated would be summarized and shared on this website within a year.</span> </div>
    </form>
  </div>
</div>
<!-- content -->

<? include_once('footer.php'); 

function getAllPAStates($con)
{
    $dbquery = "SELECT * FROM state_master1 order by StateName" ;

        $result = mysqli_query($con,$dbquery);

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            return $records;
        }
        return Null; 
}
?>