<?php
require_once('../library/db_connect.php');
function dbg($val,$text=Null)
{
    if(is_array($val))
    {
        
        echo '<pre>'; 
        print_r($val);
        echo '</pre>';
        
    }
    else
        {
        print("<hr>" . $text . ":" . $val . "<hr>");
        }
}

function getSightingCount($con,$debug=false)
{   
    $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species,st.sighting_date AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    ORDER BY st.sighting_id" ;
    
    if($debug) dbg($dbquery,"Query");

               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            if($debug)
            {dbg($records,"Result Array");}
            
            return count($records);
        }
        return Null; 
    
}

function getDistinctSubmitters($debug = false,$con)
{
    $dbquery = "select distinct email_id, name from submitter ORDER BY submitter_id ;" ;
    
    if($debug) dbg($dbquery,"Query");

               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            if($debug)
            {dbg($records,"Result Array");}
            
            return $records;
        }
        return Null; 
}

function getDistinctEmailIDs($debug = false,$con)
{
    $dbquery = "SELECT email_id FROM submitter GROUP BY email_id ORDER BY submitter_id " ;
    
    if($debug) dbg($dbquery,"Query");

             $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            if($debug)
            {dbg($records,"Result Array");}
            
            return $records;
        }
        return Null; 
}


function getSightingDetailsForSubmitter($submitter_email, $debug = false,$con)
{
$dbquery = "SELECT st.sighting_id AS SightingID,sb.submitter_id as SubmitterID, sp.species_name AS Species, tp.type_of_incident AS Incident, st.latitude AS Latitude, st.longitude AS Longitude, st.location_name AS Location, st.district_name AS District,st.state_name AS StateName, st.sighting_description AS SightingDescription, DATE_FORMAT(st.sighting_date,'%M, %Y' ) AS SightingTime, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,im.filename AS Path FROM sighting st, submitter sb, images im, species sp, type_of_incident tp
            WHERE st.submitter_id = sb.submitter_id 
            AND im.sighting_id=st.sighting_id 
            AND im.image_status !=0
            AND st.species_id = sp.species_id
            AND st.incident_id = tp.incident_id
            AND sb.email_id = '$submitter_email'
            ORDER BY st.sighting_id  DESC";
    
    if($debug) dbg($dbquery,"Query");

              $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            if($debug)
            {dbg($records,"Result Array");}
            
            return $records;
        }
        return Null;


}
// get image details
function getImageDetails($sight_id,$con){
    if(!is_numeric($sight_id))
    return null;
    
    $dbquery = "SELECT * FROM upload_details where sighting_id='".mysqli_real_escape_string($sight_id)."'and upload_status=1 " ;
    
    if($debug) dbg($dbquery,"Query");

               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 

        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {                                         
              $records[] = $row;
            }   
            if($debug)
            {dbg($records,"Result Array");}
            
            return $records;
        }
        return Null; 
    
}   // end

//get age details

 function getAgeDetails($con)
    {
        $dbquery = "SELECT * from age_details order by age_id asc" ;
             $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    function getSubmitterDetails($con)
    {
        $dbquery = "SELECT * from submitter ORDER BY submitter_id desc" ;
              $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    
    // get state details
    function getStateDetails($con)
    {
        $dbquery = "SELECT * from state_master order by State_Id asc" ;
               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    //get district details
     function getDistrictDetails($con)
    {
        $dbquery = "SELECT * from district_master order by district_id asc" ;
               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    //get sighttiming details
    
    function getSightingDetails($con)
    {
        $dbquery = "SELECT * from sight_seeing order by site_id asc" ;
               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    
    //get sigitime details
     function getSightingTimeDetails($con)
    {
        $dbquery = "SELECT * from sighting_time_details order by sighting_time_id asc" ;
               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end 
    
    // get activity details
    
    function getActivityDetails($con)
    {
        $dbquery = "SELECT * from activity order by activity_id asc" ;
              $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    //get habitat details
    function getHabitatDetails($con)
    {
        $dbquery = "SELECT * from habitat_type order by habitat_id asc" ;
              $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    }  //end
    
    function getPADetailsFromPAID($pa_id,$con)
    {
        if(!is_numeric($pa_id)){
            return null;
        }
        
        
        $dbquery = "SELECT p.PA_ID, p.FullName, s.State_Id, s.StateName from pa_master p, state_master1 s  Where p.PA_ID = ".mysqli_real_escape_string($con,$pa_id)." AND p.State_Id = s.State_Id  order by PA_ID desc" ;
             $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
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
    
    function getPADetails($con)
    {
        $dbquery = "SELECT p.PA_ID, p.FullName, s.State_Id, s.StateName from pa_master p, state_master1 s order by PA_ID desc" ;
               $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records = $row;
            }
            return $records;
        }
        return null;
    }  
    
    function getPAForAState($con)
    {
        $dbquery = "SELECT * FROM pa_master where State_Id = State_Id" ;
        $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    }  
    
    
     // get area status details
     function getAreaStatusDetails($con)
    {
        $dbquery = "SELECT * from area_status order by status_id desc" ;
        $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end
    
    //get species details
    
     function getSpeciesDetails($con)
    {
        $dbquery = "SELECT * from species order by species_id asc" ;
       $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end 
    
    
    // get all submissions by species id
     function getAllSubmissions($species_id='',$limit,$con)
    {

       
    if(empty($species_id)){  

        $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,im.filename as img_status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species,st.sighting_date AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, st.submitting_time_details as SubmissionDate, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
     AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    ORDER BY st.submitting_time_details DESC ".mysqli_real_escape_string($con,$limit)."" ;
    }else{ 
        $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID,im.filename as img_status, sp.species_name AS Species,st.sighting_date AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    AND st.species_id = '".mysqli_real_escape_string($con,$species_id)."'
    group BY st.sighting_id DESC ".mysqli_real_escape_string($con,$limit)."" ;
		
        
    }

		
            $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;

            }
            return $records;
        }
        return null;  
    } //end 
    
    // get submission  by stauts  
    //ORDER BY st.submitting_time_details DESC
    function getAllSubmissionsByStatus($status='',$con,$limit){
     $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,im.filename as Path, im.filename as img_status, st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, st.sighting_date AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    AND im.image_status = '".mysqli_real_escape_string($con,$status)."'
    
    group BY st.submitting_time_details DESC ".mysqli_real_escape_string($con,$limit).""; 
    $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    }  // end
    
    
    // get submission by submit number
    function getAllSubmissionsBySubmitNo($submit_no,$con)
    {
        error_reporting(E_ALL);
        /*$dbquery = " SELECT st.sighting_id AS SightingID,im.image_status as image_status,st.species_id as speciesid,st.status_id as status_id,st.sighting_time as sighting_time,st.habitat_id as habitat_id,st.activity_id as activity_id,st.site_id as site_id,st.state_id as state_id, st.site_id as site_id,st.age_id as age_id,ad.age as age,se.site_name as site,st.numbers_seen as numbers,st.sighting_description as description,sb.submitter_id as SubmitterID,at.activity as Behaviour, sp.species_name AS Species, ar.status AS Status, sd.sighting_time as SightingTime, st.latitude AS Latitude, st.longitude AS Longitude, st.location_name AS Location, st.district_name AS District,st.state_name AS StateName, ht.habitat_type as Habitat, st.numbers_seen as Numbers, st.sighting_description AS SightingDescription, DATE_FORMAT(st.sighting_time_details,'%d, %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,im.filename AS Path,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
FROM sighting st, submitter sb, images im, species sp, state_master sm, habitat_type ht, area_status as ar,sighting_time_details as sd, activity as at, age_details as ad ,sight_seeing as se
WHERE st.submitter_id = sb.submitter_id 
AND im.sighting_id=st.sighting_id 
AND st.species_id = sp.species_id 
AND st.activity_id = at.activity_id                                                                                                                                          
AND st.state_id = sm.State_id
AND st.age_id = ad.age_id 
AND st.habitat_id = ht.habitat_id
AND st.status_id = ar.status_id
AND st.sighting_time = sd.sighting_time_id
AND st.site_id = se.site_id 
AND st.sighting_id = $submit_no
ORDER BY st.sighting_id  DESC" ; */
    $dbquery = "SELECT * from sighting st, submitter sb , images im
    where st.sighting_id =$submit_no
    AND st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id  ";

    // $dbquery = "SELECT * from sighting st, submitter sb
    //     where st.sighting_id =$submit_no
    //     AND st.submitter_id = sb.submitter_id ";
           //$result = mysqli_query($con,$dbquery); 
           $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        if($result)
        {
            $records = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $records[] = $row;
            }
            return $records;
        }
        return null;
    } //end 

    
    
function getAllPAStates($con)
{
    $dbquery = "SELECT * FROM state_master1 order by StateName" ;

          $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
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

//featch images
function count_image($con){
  $dbquery = "SELECT im.filename as img_status,im.image_status as status 
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 

    ORDER BY st.submitting_time_details DESC ".mysqli_real_escape_string($con,'LIMIT 0,10000000000')."" ;
    $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
    
    if($result)
    {
        $records = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $records[] = $row;

        }
        return $records;
    }
    return null;  
}   

?>