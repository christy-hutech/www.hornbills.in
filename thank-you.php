<?php
require_once("library/db_connect.php"); 
include('resize-class.php');   
include('resize_class.php');

error_reporting(0);
/*Added 4 Spam bot Ankur*/


$hornbillname = isset($_POST['hornbillname']) ? $_POST['hornbillname'] : false ;
$hornbillurl = !empty($_POST['hornbillurl']) ? $_POST['hornbillurl'] : '';

$upload_image = isset($_FILES['upload-image']) ? $_FILES['upload-image'] : false;

if (($hornbillname == false) && ($hornbillurl == 'https://')) {

   // display message that the form submission was rejected
    /*END Spam bot Ankur*/   


    $allowedExts = array("jpg", "jpeg", "gif", "png","pjpeg");
    $allowedmimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png","image/jpg");
        $upload_path = getcwd()."/upload/";

    $upload_image = isset($_FILES['upload-image']) ? $_FILES['upload-image'] : false;
    $tmp_name = isset($upload_image['tmp_name']) ? $upload_image['tmp_name'] : false;
    $type =isset($upload_image["type"]) ? $upload_image["type"] : false;

    #print 'temp name:'.$_FILES["upload-image"]["tmp_name"].'<br />';
    #print 'name:'.$_FILES["upload-image"]["name"].'<br />';

    if($tmp_name){
        $imgprops = getimagesize($tmp_name);
    }

       
// if ((($type == "image/gif")
//         || ($type == "image/jpeg")
//         || ($type == "image/pjpeg")
//         || ($type == "image/png")
//         || ($type == "jpg"))
//         && ($type < 307200))
        $imgprops_mine = isset($imgprops["mime"]) ? $imgprops["mime"] : false;
        if ((($imgprops_mine  == "image/gif")
        || ($imgprops_mine  == "image/jpeg")
        || ($imgprops_mine  == "image/pjpeg")
        || ($imgprops_mine  == "image/png")
        || ($imgprops_mine  == "jpg"))
        && ($_FILES["upload-image"]["size"] < 307200))
          {
             
              if ($_FILES["upload-image"]["error"] == 0)
                {
                    $result=submitDetails($_POST,$con);
                    $upload_image=$result['sighting_id']."-".$_FILES['upload-image']['name'];  
                                        
                    if (!file_exists($upload_path.$upload_image))
                      {   

        if(isset($imgprops['mime']) and in_array($imgprops['mime'],$allowedmimetypes)) { 
            
                          $k = move_uploaded_file($_FILES["upload-image"]["tmp_name"],$upload_path.$upload_image);
 
                          $image = new resize($upload_path.$upload_image);
                          
                          if (!is_dir('upload'))
                          {
                             mkdir('upload');
                          }      
                             
                          if (!is_dir($upload_path.'Thumbnail')) 
                          {
                             mkdir($upload_path.'Thumbnail');
                          }
                            
                          if (!is_dir($upload_path.'Large')) 
                          {
                            mkdir($upload_path.'Large');
                          }
                          
                          $image_size=getimagesize($upload_path.$upload_image);

                          if($image_size[0]<=1024)
                          {
                             if($image_size[0]>300)
                             {
                               $image->resizeImage(300,200,'crop');
                               $image->saveImage($upload_path.'Thumbnail/'.$upload_image,100);
                             }
                             if($image_size[0]>620)
                             {
                               $image->resizeImage(620,'', 'auto');
                               $image->saveImage($upload_path.'Large/'.$upload_image,100);
                             }
                               $image->resizeImage(300,200,'crop');
                               $image->saveImage($upload_path.'Thumbnail/'.$upload_image,100);
                             
                               $dbQuery = mysqli_query($con,"INSERT INTO images VALUES (null,'".$result['sighting_id']."','".$upload_image."','0')");
                               $dbquery="select * from images ORDER BY image_id DESC LIMIT 0,1";
                               $images=mysqli_query($con,$dbquery);  
                               $result1=mysqli_fetch_array($images);
                             
                               $dbquery="select * from submitter where submitter_id='".$result['submitter_id']."'  ";
                             
                               $submitter_details=mysqli_query($con,$dbquery);  
                               $submitter_details=mysqli_fetch_array($submitter_details);
                             
                               $dbquery="select * from sighting where submitter_id='".$result['submitter_id']."'  ";
                             
                               $sighting_details=mysqli_query($con,$dbquery);   
                               $sighting_details=mysqli_fetch_array($sighting_details);
                             
                               $dbquery="select species_name from species where species_id='".$sighting_details['species_id']."'  ";       
                             
                               $species_details=mysqli_query($con,$dbquery);   
                               $species_details=mysqli_fetch_array($species_details);
                                                         
                               $fb_share_link = "https://www.facebook.com/dialog/feed?
      app_id=129839053836587&link=".urlencode("http://hornbills.in/photo.php?p=".isset($result1['image_id']) ? $result1['image_id'] : false)."&picture=http://hornbills.in/upload/fbthumb/".$upload_image."&name=".urlencode("I just reported a hornbill sighting on Hornbill Watch")."&
      caption=&description=".urlencode("Wildlife enthusiasts & photographers report sightings of hornbills in India. Click to view/report.")."&redirect_uri=".urlencode("http://hornbills.in/photo.php?p=".$result1['image_id']);
                              
                               //$message =  "<h2>Upload successful!</h2><p>Thank you for submitting your photo. Your image will appear on the gallery post approval. You may then share it on Facebook..</p><p>Would you like to <a href='upload.php'>submit another sighting</a>?</p>";   
                                                            
                                                             $photo_id = $result1['image_id'];
                                                             
                                                             $message =  "<h2>Upload successful!</h2><p>Thank you for submitting your sighting. Would you like to <a href='upload.php'>submit another sighting</a>?</p>
                                                             <p class='muted'>Please note: Your submission ID is <strong>#".$photo_id."</strong>. and ".$result['submitter_id']." If you want to make any corrections to the submission, please <a href='mailto:aparajita@ncf-india.org'>write to us</a> and mention the submission ID along with the changes.</p>";
                                                        
                              // setcookie("SubmitterName",$submitter_details['name'],time()+604800);
                              // setcookie("EmailId",$submitter_details['email_id'],time()+604800);
                              // setcookie("MobileNo",$submitter_details['mobile_no'],time()+604800);
                                                             /*setcookie("Species",$species_details['species_id'],time()+604800); 
                                                             setcookie("Month",$_POST['month'],time()+604800);
                                                             setcookie("Year",$_POST['year'],time()+604800);
                                                             setcookie("Incident",$_POST['typeofincident'],time()+604800);*/
                
                                             
                      }
                          else
                          {
                                   $message = "<h2>Image resolution is too large</h2> <p>Looks like the file you are uploading is wider than 1024 pixels. Please reduce the resolution of the image and upload again.</p><p><a href='./upload.php'>Click here</a> to try again.</p>";
                          }
}
else                       {
                                   $message = "<h2>Not a valid image type that we accept.</h2> <p>Looks like the file you are uploading is not a JPEG, GIF or a PNG.</p><p><a href='./upload.php'>Click here</a> to try again.</p>";
                          }

                      }           
                      else
                      {
                                   $message = "<h2>File already exists!</h2> <p>Please rename the file and upload again.</p><p><a href='./upload.php'>Click here</a> to try again.</p>";
                      }           
                }
                else
                {
                   $message = "<h2>File contains some errors</h2> <p>Looks like the file you are uploading contains some errors. Please upload another copy.</p><p><a href='./upload.php'>Click here</a> to try again.</p>";
                }
           }
           else
           {

                    $result=submitDetails($_POST,$con);
                    
                           $dbQuery = mysqli_query($con,"INSERT INTO images VALUES (null,'".$result['sighting_id']."','NA','0')");
                           $dbquery="select * from images ORDER BY image_id DESC LIMIT 0,1";
                           $images=mysqli_query($con,$dbquery);  
                           $result1=mysqli_fetch_array($images);
                           $dbquery="select * from submitter where submitter_id='".$result['submitter_id']."'  ";
                         
                           $submitter_details=mysqli_query($con,$dbquery);  
                           $submitter_details=mysqli_fetch_array($submitter_details);
                         
                           $dbquery="select * from sighting where submitter_id='".$result['submitter_id']."'  ";
                             
                           $sighting_details=mysqli_query($con,$dbquery);   
                           $sighting_details=mysqli_fetch_array($sighting_details);
                           $species_id = isset($sighting_details['species_id']) ? $sighting_details['species_id'] : false;

                            $dbquery="select species_name from species where species_id='".$species_id."'  ";       
                             
                           $species_details=mysqli_query($con,$dbquery);   
                           $species_details=mysqli_fetch_array($species_details);
                                                 
                           $fb_share_link = "https://www.facebook.com/dialog/feed?app_id=129839053836587&link=".urlencode("http://hornbills.in/photo.php?p=".isset($result1['image_id']))? $result1['image_id'] : false."&picture=http://hornbills.in/upload/fbthumb/".$upload_image."&name=".urlencode("I just reported a hornbill sighting on Hornbill Watch")."&caption=&description=".urlencode("Wildlife enthusiasts & photographers report sightings of hornbills in India. Click to view/report.")."&redirect_uri=".urlencode("http://hornbills.in/photo.php?p=".$result1['image_id']);
            
                            $photo_id = $result1['image_id'];
                              
                            $message =  "<h2>Upload successful!</h2><p>Thank you for submitting your sighting. Would you like to <a href='upload.php'>submit another sighting</a>?</p><p class='muted'>Please note: Your submission ID is <strong>#".$photo_id."</strong>. If you want to make any corrections to the submission, please <a href='mailto:aparajita@ncf-india.org'>write to us</a> and mention the submission ID along with the changes.</p>";   
                                                        
                               //setcookie("SubmitterName",isset($submitter_details['name']) ? $submitter_details['name'] : false,time()+604800);
                              // setcookie("EmailId",isset($submitter_details['email_id']) ? $submitter_details['email_id'] : false,time()+604800);
                              // setcookie("MobileNo",isset($submitter_details['mobile_no']) ? $submitter_details['mobile_no'] : false ,time()+604800);
                                                             /*setcookie("Species",$species_details['species_id'],time()+604800); 
                                                             setcookie("Month",$_POST['month'],time()+604800);
                                                             setcookie("Year",$_POST['year'],time()+604800);
                                                             setcookie("Incident",$_POST['typeofincident'],time()+604800);*/
                                                  
     
           }     
 	   
}

else { 
   include_once('header.php'); 
}
?>

<!-- <div class="content clearfix">
  <div class="grid_8" style="text-align:center;">
    <h1> Thanks !..... </h1>                                                
    </div>
</div>  content -->


<?php
//include_once('footer.php');
                    
/*END Spam bot Ankur*/
?> 


<?php 

    function submitDetails($data,$con) 
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        $mobileno= isset($data['mobile_no']) ? $data['mobile_no'] : false;
        $nearest_town=isset($data['nearest_town']) ? $data['nearest_town'] : false ; 
        $sigthing_year= isset($data['year']) ? $data['year'] : false;
    	if(!is_numeric($mobileno)) {
		$msg = '<span class="error"> Data entered was not numeric</span>';
		}
       
        $sigthing_month= isset($data['month']) ? $data['month'] : false; 
        $sigthing_time = $sigthing_month." ".$sigthing_year;
        $sigthing_time_details = strtotime($sigthing_time);
        $sigthing_time_details = date('Y-m-d 00:00:00',$sigthing_time_details);
        $dname = isset($data['name']) ? $data['name'] : false;
        $demail = isset($data['email']) ? $data['email'] : false ;
       
        $dbquery="insert into submitter values(null,'".$dname."','".$demail."','".$mobileno."',Now())";

        $result = mysqli_query($con,$dbquery) or die(mysqli_error()); 
        $submitter_id=mysqli_insert_id($con);
        $loc_state = isset($data['location-state']) ? $data['location-state'] : '';
        $dbquery="select StateName from  state_master where State_Id='".$loc_state."'";
        $result=mysqli_query($con,$dbquery);
        $result=mysqli_fetch_array($result);
        $state_name= isset($result['StateName']) ? $result['StateName'] : '';
        $district = isset($data['district']) ? $data['district'] : false;
        $dbquery="select district_name from  district_master where district_id='".$district."'";      
        $result = mysqli_query($con,$dbquery) or die($dbquery."<br/><br/>".mysqli_error());

        $result=mysqli_fetch_array($result);
 
        $district_name= isset($result['district_name']) ? $result['district_name'] : false;
        if($nearest_town=='')
        {
               $sigthing_place_name=$district_name.", ".$state_name;
        }
        else if($district_name=='')
        {
             $sigthing_place_name=$nearest_town.", ".$state_name;
        }
        else if($state_name=='')
        {
            $sigthing_place_name=$nearest_town.", ".$district_name;
        }
        else{
           $sigthing_place_name=$nearest_town.", ".$district_name.", ".$state_name; 
        }
        
     
        $data2 = explode(',',isset($data['latitude_longitude'])? $data['latitude_longitude'] : false);
        $latitude= isset($data2[0]) ? $data2[0] : false ;
        $longitude=isset($data2[1]) ? $data2[1] : false; 
        $species_name = isset($data['species']) ? $data['species'] : false;
        $activity = isset($data['activity']) ? $data['activity'] : false;
        $day = isset($data['day']) ? $data['day'] : false;
        $month = isset($data['month']) ? $data['month'] : false;
        $year = isset($data['year']) ? $data['year'] : false;
        $sighting_date = $year."-".$month."-".$day ; 

        $sighting_date = date('Y-m-d',strtotime($sighting_date));

        $species = isset($data['species']) ? $data['species'] : 0;
        $activity = isset($data['activity']) ? $data['activity'] : 0;
        $typeofincident = isset($data['typeofincident']) ? $data['typeofincident'] : 0;
        $numbers_seen = isset($data['numbers_seen']) ? $data['numbers_seen'] : 0;
        $age = isset($data['age']) ? $data['age'] : 0;
        $habitat = isset($data['habitat']) ? $data['habitat'] : 0;
        $location_type = isset($data['location-type']) ? $data['location-type'] : false;
        $sighting_time = isset($data['sighting_time']) ? $data['sighting_time'] : false;
        $state = isset($data['state']) ? $data['state'] : false;
        $location_pa = isset($data['location-pa']) ? $data['location-pa'] : 0;
        $town = isset($data['town']) ? $data['town'] : false;
        $district = isset($data['district']) ? $data['district'] : false;
        $location_state = isset($data['location-state']) ? $data['location-state'] : 0;
        $sightingdesc = isset($data['sightingdesc']) ? $data['sightingdesc'] : false;
        
        if($location_state  == -1)
        {  

            $dbquery = "insert into sighting values(null,'".$submitter_id."','".$species."','".$activity."','".$typeofincident."','".$numbers_seen."','".$age."','".$habitat."','".$location_type."','".$sighting_time."','".$state."','".$location_pa."','".$latitude."','".$longitude."','".$town."','".$district."','".$state."','','".$typeofincident."','".mysqli_real_escape_string($con,strip_tags($sightingdesc))."',NOW(),'".$sighting_date."')";  
        }
        else
        {
            $dbquery="insert into sighting values(null,'".$submitter_id."','".$species."','".$activity."','".$typeofincident."','".$numbers_seen."','".$age."','".$habitat."','".$location_type."','".$sighting_time."','".$location_state."','".$location_pa."','".$latitude."','".$longitude."','".$town."','".$district."','".$state."','','".$typeofincident."','".mysqli_real_escape_string($con,strip_tags($sightingdesc))."',NOW(),'".$sighting_date."')"; 
        }


        $update = mysqli_query($con,$dbquery) or die(mysqli_error());
        $sigthing_id=mysqli_insert_id($con);
        $result_array=array('sighting_id'=>$sigthing_id,'submitter_id'=>$submitter_id);
        if($update) {
            sendemail ($dname,$sigthing_id);
        }
            
        
        return $result_array;
      
      
    }
        
        include_once('header.php');
?>

<div class="content clearfix">
  <div class="grid_8">
    <?php echo isset($message) ? $message : false ; ?>                                                     
    </div>
</div> <!-- content -->


<?php 

  function sendemail($submitter_name,$image_id)
    {
        
     $to= 'shivanarayant@gmail.com' ;
     $cc = '';
     $subject = "Hornbill Watch - New Submission by ".$submitter_name;
     $message="<p>Hello,</p><p>
              ".$submitter_name." has submitted a new sighting. <a href='http://hornbills.in/admin/submission_details.php?id=".$image_id."' target='_blank'>Click here</a> to view/approve the sighting.</p><p>Thanks!</p>";
   
     $from = 'donotreply@hornbills.in';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: Hornbill Watch <donotreply@hornbills.in>'."\r\n" .
                            'Sender: Hornbill Watch <donotreply@hornbills.in>' . ">\r\n" .
                            'Reply-To: Hornbill Watch <donotreply@hornbills.in>' . ">\r\n";
                           if(!empty($cc)) {
                             $headers .= 'Cc: ' . strip_tags($cc) . "\r\n";
                           }
                           if(!empty($bcc)) {
                             $headers .= 'BCc: ' . strip_tags($bcc) . "\r\n";
                           }
                $headers .= 'X-Mailer: hornbills.in';


        mail($to,$subject,$message,$headers);
          
    }
?>
  
<?php include_once('footer.php'); ?>



<?php
function getimagesizeReal($image) {

    $imageTypes = array (
            IMAGETYPE_GIF,
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
            IMAGETYPE_SWF,
            IMAGETYPE_PSD,
            IMAGETYPE_BMP,
            IMAGETYPE_TIFF_II,
            IMAGETYPE_TIFF_MM,
            IMAGETYPE_JPC,
            IMAGETYPE_JP2,
            IMAGETYPE_JPX,
            IMAGETYPE_JB2,
            IMAGETYPE_SWC,
            IMAGETYPE_IFF,
            IMAGETYPE_WBMP,
            IMAGETYPE_XBM,
            IMAGETYPE_ICO 
    );
    $info = getimagesize ( $image );
    $width = @$info [0];
    $height = @$info [1];
    $type = @$info [2];
    $attr = @$info [3];
    $bits = @$info ['bits'];
    $channels = @$info ['channels'];
    $mime = @$info ['mime'];

    if (! in_array ( $type, $imageTypes )) {
        return false; // Invalid Image Type ;
    }
    if ($width <= 1 && $height <= 1) {
        return false; // Invalid Image Size ;
    }

    if($bits === 1)
    {
        return false; // One Bit Image .. You don't want that  ;
    }
    return $info ;
}

?>