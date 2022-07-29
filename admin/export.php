<?php

// load library
require 'php-excel.class.php';
 require_once('../library/db_connect.php');
require_once('functions.php');
// create a simple 2-dimensional array
$limit = null;
    
        $headerarray = array();
        $headerarray[]= array('SigintingID','Species','Name','Email','Mobile','Date','Habitat','Numbers seen','Sighting Time','Activity','Age','Nearest Town','State','Latitude','Longitude','Protected Area');
$query = "SELECT st.sighting_id AS SightingID,hb.habitat_type as habitat,st.numbers_seen,sd.sighting_time,st.location_name,sm.StateName,pm.fullname, at.activity as activity,st.latitude,st.longitude,st.status_id,st.pa_id, ad.age as age,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, DATE_FORMAT(st.sighting_date,'%d %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, st.submitting_time_details as SubmissionDate, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st
    join submitter sb on st.submitter_id = sb.submitter_id left
    join species sp on st.species_id = sp.species_id left
    join images im on im.sighting_id=st.sighting_id left
    join habitat_type hb on st.habitat_id = hb.habitat_id left
    join activity at on st.activity_id = at.activity_id left
    join age_details as ad on st.age_id = ad.age_id left
    join state_master1 sm on st.state_id = sm.state_id left
    join pa_master pm on st.pa_id = pm.PA_ID left
    join sighting_time_details sd on st.sighting_time = sd.sighting_time_id
    where im.image_status = 1
    ORDER BY st.submitting_time_details DESC ".mysqli_real_escape_string($con,$limit).""; 
$result = mysqli_query($con,$query) or die($query . '<br>' . mysqli_error()); $myarray = array(); while($row = mysqli_fetch_array($result)) {
$myarray[] = array($row['SightingID'], $row['Species'], $row['UploaderName'] ,$row['UploaderEmail'],$row['UploaderMobile'],$row['date'],$row['habitat'],$row['numbers_seen'],$row['sighting_time'],$row['activity'],$row['age'],$row['location_name'],$row['StateName'],$row['latitude'],$row['longitude'],$row['fullname']); 
//     print_r($myarray);
    } 
    
// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
$xls->addArray($headerarray);
$xls->addArray($myarray);
//$xls->addArray($statusarray);
$xls->generateXML('Hornbill');  

?>
