<?php
include_once("library/db_connect.php");
include_once('header.php'); 

?>
 
<div class="content clearfix"> 

<!-- //want to remove -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<div class="paginationnew">
   <span class="main-search">
    <input type="search" class="search-buttonnew"  placeholder="Search by species, states, contributors and year(eg:20XX)" />
    <button type="submit" class="search-buttonnew">
    <i class="fa fa-search" style="cursor: auto;"></i>
</button>
</span>
</div> 

<div class="grid_12">
    <h2>Gallery <a href="http://hornbills.in/contributors.php" class="alignright small-text margintop10">View all contributors</a></h2>
 
<ul class="gallery display-gallery">
<?php
 $upload_path = getcwd()."/upload/";
 $perpage = 15;
if(isset($_GET["page"]))
 {
  $page = intval(strip_tags($_GET["page"]));
 }
else
 {
  $page = 1;
 }
 
$calc = $perpage * $page;
$start = $calc - $perpage;

$result = mysqli_query($con,"select * from images where image_status = 1 and filename <> 'NA' ORDER BY image_id DESC Limit ".mysqli_real_escape_string($con,$start).",".mysqli_real_escape_string($con,$perpage));

// $result = mysqli_query($con,"select * from images where image_status = 1 and filename <> 'NA' ORDER BY image_id DESC Limit ".mysqli_real_escape_string($con,$start).",".mysqli_real_escape_string($con,$perpage));

$rows = mysqli_num_rows($result);
if($rows > 0)
{
    $i = 0;
    $image_count = 0;  
    while($row = mysqli_fetch_array($result))
    {  
       $sighting_details=getSightingDetailsFromSightingID($row['sighting_id'],$con); 
       $species_details=getSpeciesDetails($sighting_details['species_id'],$con);
       // $allSpecies_details=getSpeciesDetails($sighting_details['species_id'],$con);
       // $species_details = getSpeciesDetails($species_id,$con);
       // $species_details = !empty($species_id) ? $species_details : $allSpecies_details;
       $age_details=getAgeDetails($sighting_details['age_id'],$con);
       $submitter_details = getSubmitterDetailsFromSubmitterID($sighting_details['submitter_id'],$con);
       $sighting_place_name= stripslashes($sighting_details['state_name']) ;
             
            //echo '<pre>'; print_r($age_details); echo '</pre>';//
       
       if(file_exists($upload_path.$row['filename']) || file_exists($upload_path.'Thumbnail/'.$row['filename']))
      {
        $image_count++;
                $classname = 'grid_4';
                if($image_count == 1 || ($image_count-1)%3==0) { $classname .= ' alpha';}
                if($image_count%3==0) { $classname .= ' omega';}
        echo "<li class='".$classname." hidden'><a class='gallery-link search-photos' href='photo.php?p=".strip_tags($row['image_id'])."'>";
                if(file_exists($upload_path.'Thumbnail/'.$row['filename'])){
                    echo "<img src='./upload/Thumbnail/".$row['filename']." ' />";
                }
                else{
                    echo "<img src='./upload/".$row['filename']." '  />";
                }
                echo"<span class='gallery-caption'>".$species_details['species_name'];
                     echo "</span>"; 
                    //if($age_details['age'] != 'Unknown')
                        {
                            //   echo"<span class='gallery-submitted-by'>".$age_details['age'];                              //akan
                       }        
                echo "</span>"; 
                echo"<span class='gallery-submitted-by'>".$submitter_details['name'];
                                
                if($sighting_details['pa_id']>=1)
                    { 
                        $pa_details = array();
                        $pa_details = getPADetailsFromPAID($sighting_details['pa_id'],$con); 
                        echo ' &middot; '.stripslashes($pa_details['FullName']);                       
                    } 
                    
                elseif(!empty($sighting_details['state_id'])) {
                  
                    $state_details = getStateNameFromStateID($sighting_details['state_id'],$con);
                    echo " &middot; ".stripslashes($state_details ['StateName']); 
                } 

                $state_details = getStateNameFromStateID($sighting_details['state_id'],$con);
                echo "</span>" ;
                echo "</a>";
                echo "<input class='state-name' type='hidden' value='".stripslashes($state_details ['StateName'])."'/>";
                echo "</li>"; 
        if($image_count % 3 == 0): echo "<div class='clear'></div>"; endif;
      }                                                         
    }
}
else {        
echo 'There are no images to show in this gallery';
}
?>
</ul>
<div class="clear"></div>
<?php
 filterData($con);
if(isset($page))
{
    $result = mysqli_query($con,"select Count(*) As Total from images where image_status = 1");
    $rows = mysqli_num_rows($result);
    if($rows)
    {
        $rs = mysqli_fetch_array($result);
        $total = $rs["Total"];
    }
    if($total>$perpage)
    {
    $totalPages = ceil($total / $perpage);
   
    if($page <=1 )
    {
      echo "<ul class='pagination display-pagination'><li><a href='#'>&laquo; Prev</a></li>";
    }
    else
    {
       $j = $page - 1;
       echo "<ul class='pagination display-pagination'><li><a href='gallery.php?page=$j'>&laquo; Prev</a></li>";
    }
 
for($i=1; $i <= $totalPages; $i++)
{
 
    if($i<>$page)
    {
     echo "<li><a href='gallery.php?page=$i'>$i</a></li>";
    }
    else
    {
     echo "<li><a class='active' href='#'>$i</a></li>";
    }
}
 
    if($page == $totalPages )
    {
      echo "";
    }
    else
    {
      $j = $page + 1;
      echo "<li><a href='gallery.php?page=$j'>Next &raquo;</a></li></ul>";
    }
 }  }
 
 
?> 
</div>
</div>
  
<?php 
 function getSightingDetailsFromSightingID($sighting_id,$con)
{
    if(!is_numeric($sighting_id))
    return false;

     $dbquery = "SELECT * FROM sighting WHERE sighting_id = ".mysqli_real_escape_string($con,$sighting_id).""; 

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
        return false;
 }
  
 function getSubmitterDetailsFromSubmitterID($submitter_id,$con)
{
     $dbquery = "SELECT * FROM submitter WHERE submitter_id = ".mysqli_real_escape_string($con,$submitter_id).""; 

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
        return false;
 }  

  function getSpeciesDetails($species_id,$con)
{   
    if($species_id) {
        $dbquery = "SELECT * FROM species WHERE species_id = ".mysqli_real_escape_string($con,$species_id).""; 

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
    }
        return false;
 } 
 
   function getAgeDetails($age_id,$con)
{
     $dbquery ="SELECT * FROM age_details  WHERE age_id = ".mysqli_real_escape_string($con,$age_id).""; 

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
        return false;
 } 
 
  function getPADetailsFromPAID($pa_id,$con)
    {
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

    function getSightingData($con)
    {

        $dbquery = "SELECT * from sighting order by sighting_id asc" ;
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
 
    function getStateNameFromStateID($s_id,$con)
    {
        $dbquery = "select * from state_master1 where State_Id = ".mysqli_real_escape_string($con,$s_id)."" ;
        $result = mysqli_query($con,$dbquery);
        if($result&& $s_id>0)
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

    function filterData($con){?>
        <ul class="gallery gallery-copy">
        <?php
         $upload_path = getcwd()."/upload/";
         $perpage = 1000000;
         //$perpage = 15;
        if(isset($_GET["page"]))
         {
          $page = intval(strip_tags($_GET["page"]));
         }
        else
          {
           $page = 1;
          }
         
        $calc = $perpage * $page;
        $start = $calc - $perpage;

        $result = mysqli_query($con,"select * from images where image_status = 1 and filename <> 'NA' ORDER BY image_id DESC Limit ".mysqli_real_escape_string($con,0).",".mysqli_real_escape_string($con,1000000000000));

        $rows = mysqli_num_rows($result);
 
        $i = 0;
        $image_count = 0;  
        while($row = mysqli_fetch_array($result))
        {  
           $sighting_details=getSightingDetailsFromSightingID($row['sighting_id'],$con); 
           $species_details=getSpeciesDetails($sighting_details['species_id'],$con);
           $age_details=getAgeDetails($sighting_details['age_id'],$con);
           $submitter_details = getSubmitterDetailsFromSubmitterID($sighting_details['submitter_id'],$con);
           $sighting_place_name= stripslashes($sighting_details['state_name']) ;
                //echo '<pre>'; print_r($age_details); echo '</pre>';//
           
           if(file_exists($upload_path.$row['filename']) || file_exists($upload_path.'Thumbnail/'.$row['filename']))
          {
            $image_count++;
                    $classname = 'grid_4';
                    if($image_count == 1 || ($image_count-1)%3==0) { $classname .= ' alpha';}
                    if($image_count%3==0) { $classname .= ' omega';}
            echo "<li class='".$classname." hidden'><a class='gallery-link search-photos' href='photo.php?p=".strip_tags($row['image_id'])."'>";
                    if(file_exists($upload_path.'Thumbnail/'.$row['filename'])){
                        echo "<img src='./upload/Thumbnail/".$row['filename']." ' />";
                    }
                    else{
                        echo "<img src='./upload/".$row['filename']." '  />";
                    }
                    echo"<span class='gallery-caption'>".$species_details['species_name'];
                         echo "</span>";        
                        echo "</span>"; 
                    echo"<span class='gallery-submitted-by'>".$submitter_details['name'];
                                    
                    if($sighting_details['pa_id']>=1)
                        { 
                            $pa_details = array();
                            $pa_details = getPADetailsFromPAID($sighting_details['pa_id'],$con); 
                            echo ' &middot; '.stripslashes($pa_details['FullName']);                       
                        } 
                        
                    elseif(!empty($sighting_details['state_id'])) {
                      
                        $state_details = getStateNameFromStateID($sighting_details['state_id'],$con);
                        echo " &middot; ".stripslashes(isset($state_details ['StateName'])?$state_details ['StateName']: ''); 
                    } 

                    $state_details = getStateNameFromStateID($sighting_details['state_id'],$con);
                    echo "</span>" ;
                    echo "</a>";
                    echo "<input class='state-name' type='hidden' value='".stripslashes(isset($state_details ['StateName']) ? $state_details ['StateName'] : '')."'/>";
                    echo "<input class='c-name' type='hidden' value='".stripslashes(isset($submitter_details['name'])? $submitter_details['name'] : '')."'/>";
                    echo "<input class='sighting-date' type='hidden' value='".stripslashes(isset($sighting_details['sighting_date'])? $sighting_details['sighting_date'] : '')."'/>";
                    echo "</li>"; 
            if($image_count % 3 == 0): echo "<div class='clear'></div>"; endif;
          }                                                         
        }

        ?>
        </ul>
        <?php
    }  
 
  include_once('footer.php'); ?>
