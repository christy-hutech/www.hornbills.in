<?php
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

function getSightingCount($debug=false)
{   
    $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, DATE_FORMAT(st.sighting_date,'%d %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    ORDER BY st.sighting_id" ;
    
    if($debug) dbg($dbquery,"Query");

        $result = mysqli_query($con,$dbquery);

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

function getDistinctSubmitters($debug = false)
{
    $dbquery = "select distinct email_id, name from submitter ORDER BY submitter_id ;" ;
    
    if($debug) dbg($dbquery,"Query");

        $result = mysqli_query($con,$dbquery);

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

function getDistinctEmailIDs($debug = false)
{
    $dbquery = "SELECT email_id FROM submitter GROUP BY email_id ORDER BY submitter_id " ;
    
    if($debug) dbg($dbquery,"Query");

        $result = mysqli_query($con,$dbquery);

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


function getSightingDetailsForSubmitter($submitter_email, $debug = false)
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

        $result = mysqli_query($con,$dbquery);

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
function getImageDetails($sight_id){
    if(!is_numeric($sight_id))
    return null;
    
    $dbquery = "SELECT * FROM upload_details where sighting_id='".mysqli_real_escape_string($sight_id)."'and upload_status=1 " ;
    
    if($debug) dbg($dbquery,"Query");

        $result = mysqli_query($con,$dbquery);

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

 function getAgeDetails()
    {
        $dbquery = "SELECT * from age_details order by age_id asc" ;
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
        return null;
    } //end
    
    function getSubmitterDetails()
    {
        $dbquery = "SELECT  DISTINCT(sb.name)  AS name
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
    ORDER BY sb.name ASC" ;
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
        return null;
    } //end
    
    
    // get state details
    function getStateDetails()
    {
        $dbquery = "SELECT * from state_master order by State_Id asc" ;
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
        return null;
    } //end
    
    //get district details
     function getDistrictDetails()
    {
        $dbquery = "SELECT * from district_master order by district_id asc" ;
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
        return null;
    } 
      function getSubmitterName()
    {
       $dbquery = "SELECT  sb.name  AS name
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
    ORDER BY sb.name ASC" ;
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
        return null;
    }//end
    
    //get sighttiming details
    
    function getSightingDetails()
    {
        $dbquery = "SELECT * from sight_seeing order by site_id asc" ;
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
        return null;
    } //end
    
    
    //get sigitime details
     function getSightingTimeDetails()
    {
        $dbquery = "SELECT * from sighting_time_details order by sighting_time_id asc" ;
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
        return null;
    } //end 
    
    // get activity details
    
    function getActivityDetails()
    {
        $dbquery = "SELECT * from activity order by activity_id asc" ;
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
        return null;
    } //end
    
    //get habitat details
    function getHabitatDetails()
    {
        $dbquery = "SELECT * from habitat_type order by habitat_id asc" ;
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
        return null;
    }  //end
    
    function getPADetailsFromPAID($pa_id)
    {
        if(!is_numeric($pa_id))
        return null;
        
        $dbquery = "SELECT p.PA_ID, p.FullName, s.State_Id, s.StateName from pa_master p, state_master1 s  Where p.PA_ID = ".mysqli_real_escape_string($con,$pa_id)." AND p.State_Id = s.State_Id  order by PA_ID desc" ;
        $result = mysqli_query($con,$con,$dbquery);
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
    
    function getPADetails()
    {
        $dbquery = "SELECT p.PA_ID, p.FullName, s.State_Id, s.StateName from pa_master p, state_master1 s order by PA_ID desc" ;
        $result = mysqli_query($con,$dbquery);
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
    
    function getPAForAState($state_id)
    {
        if(!is_numeric($state_id))
        return null;
        
        $dbquery = "select * from pa_master where State_Id = ".mysqli_real_escape_string($state_id)."" ;
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
        return null;
    }  
    
    
     // get area status details
     function getAreaStatusDetails()
    {
        $dbquery = "SELECT * from area_status order by status_id desc" ;
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
        return null;
    } //end
    
    //get species details
    
     function getSpeciesDetails($con)
    {
        $dbquery = "SELECT * from species order by species_id asc" ;
        $result = mysqli_query($con,$con,$dbquery);
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
        $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, DATE_FORMAT(st.sighting_date,'%d %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, st.submitting_time_details as SubmissionDate, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    ORDER BY st.submitting_time_details DESC ".mysqli_real_escape_string($limit)."" ;
    }else{ $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, DATE_FORMAT(st.sighting_date,'%d %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    AND st.species_id = '".mysqli_real_escape_string($species_id)."'
    group BY st.sighting_id DESC ".mysqli_real_escape_string($limit)."" ;
		
        
    }

		
        $result = mysqli_query($con,$con,$dbquery); 
        
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
    
    function getAllSubmissionsByStatus($status=''){
             $dbquery = "SELECT st.sighting_id AS SightingID,im.image_status as status,im.filename as Path, st.species_id as speciesid,sb.submitter_id as SubmitterID, sp.species_name AS Species, DATE_FORMAT(st.sighting_date,'%d %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
    FROM sighting st, submitter sb, species sp,images im
    WHERE st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id 
    AND st.species_id = sp.species_id 
    AND im.image_status = '".mysqli_real_escape_string($status)."'
     ORDER BY st.submitting_time_details DESC"; 
//    echo $dbquery;
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
        return null;
    }  // end
    
    
    // get submission by submit number
    function getAllSubmissionsBySubmitNo($submit_no)
    {
        $dbquery = " SELECT st.sighting_id AS SightingID,im.image_status as image_status,st.species_id as speciesid,st.status_id as status_id,st.sighting_time as sighting_time,st.habitat_id as habitat_id,st.activity_id as activity_id,st.site_id as site_id,st.state_id as state_id, st.site_id as site_id,st.age_id as age_id,ad.age as age,se.site_name as site,st.numbers_seen as numbers,st.sighting_description as description,sb.submitter_id as SubmitterID,at.activity as Behaviour, sp.species_name AS Species, ar.status AS Status, sd.sighting_time as SightingTime, st.latitude AS Latitude, st.longitude AS Longitude, st.location_name AS Location, st.district_name AS District,st.state_name AS StateName, ht.habitat_type as Habitat, st.numbers_seen as Numbers, st.sighting_description AS SightingDescription, DATE_FORMAT(st.sighting_time_details,'%d, %M, %Y' ) AS date, sb.name AS UploaderName, sb.email_id AS UploaderEmail, sb.mobile_no AS UploaderMobile,im.filename AS Path,sb.name AS SubmitterName, sb.email_id AS SubmitterMail, sb.mobile_no AS SubmitterMobile
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
ORDER BY st.sighting_id  DESC" ; 
    $dbquery = "SELECT * from sighting st, submitter sb , images im
    where st.sighting_id =$submit_no
    AND st.submitter_id = sb.submitter_id
    AND im.sighting_id=st.sighting_id";
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
        return null;
    } //end 

    
    
function getAllPAStates()
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