<?php 
session_start(); 
if(!isset($_SESSION['login']))
{
    header('Location: /admin/login.php');
    exit;
} 
require_once('../library/db_connect.php');
require_once('functions.php');
require_once('zip-file.php');

$total_rows = getSightingCount($con);

require_once('Paginator.php');
$adminUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pages = new Paginator;
$pages->items_total = $total_rows;
$pages->mid_range = 2;
$pages->paginate();
if(!empty($pages)){
    $limit = $pages->limit;
} 

  $error = 0;
  if(!empty($_GET)){
      $species_id = mysqli_real_escape_string($con,strip_tags(isset($_GET['species']) ? $_GET['species'] : false ));   
  
    }else{
      $species_id='';
  }

  if(empty($species_id)) 
  {
    $search = 1;
    $submission_details = getAllSubmissions($species_id,$limit,$con);
  }
  else{
    $submission_details = getAllSubmissions($species_id,'LIMIT 0,10000000000',$con); 
    $search = 0;
    $error = 2;
  }
    $species_details = getSpeciesDetails($con);
    $status =  mysqli_real_escape_string($con,strip_tags( isset($_GET['status']) ? $_GET['status'] : false)); 

    if($status){
       $submission_details = getAllSubmissionsByStatus($status,$con,'LIMIT 0,10000000000'); 
    } 

  // img count
  $Rc_with_img = $Rc_without_img = $Aimg_status = array();
  $cout_images = count_image($con);
  $count = 0;
  if(isset($cout_images)) {
    foreach($cout_images as $cout_image) { 
      $filename = isset($cout_image['img_status']) ? $cout_image['img_status'] : false;
      $status = isset($cout_image['status']) ? $cout_image['status'] : false;
      $img_status = $filename == 'NA' ? 'No' : 'Yes';
      if($filename == 'NA') {
        array_push($Rc_without_img, $filename);
      }else {
        array_push($Rc_with_img, $filename);
      }

      if($status == 1 && $filename != 'NA') {
        array_push($Aimg_status, $status);
      }
    }
  }
  
    if (!file_exists(getcwd().'/accept_img')) {
       mkdir(getcwd().'/accept_img', 0777, true);
    }

    $url = trim(str_replace('admin','',getcwd()));
    $images = getAllPAFromStateID($con);
    $old = 'upload/Large';
    $new = getcwd().'/accept_img';
    $old = $url.$old ;
    $dh = opendir($old);
    
    $status = false;
    while (($file = readdir($dh)) !== false) {
      $status = true;
      foreach($images as $image) {
        $fileName = isset($image['filename']) ? $image['filename'] : '';
        if ($fileName == $file && !file_exists($new.'/'.$fileName) && file_exists($new)) {
            $copy = copy($old.'/'.$fileName, $new.'/'.$fileName);
        }
      }
    }
    closedir($dh);
    $filename = "accept_img.zip";
    $filepath = $url.'admin/accept_img.zip';
    if(isset($_POST['download_file'])) {
      unlink($url.'admin/accept_img.zip');
      if($status) {
        $zipper = new ZipArchiver;
        $dirPath = $new;
        $zipPath = $url.'admin/accept_img.zip';
        if(!file_exists($url.'admin/accept_img.zip')) {
          $zip = $zipper->zipDir($dirPath, $zipPath);
        }
        ob_clean();
      }
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: public");
      header("Content-Description: File Transfer");
      header("Content-type: application/octet-stream");
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: ".filesize($filepath));
      ob_end_flush();
      @readfile($filepath);
    }
?>
<?php include_once('header.php'); ?>
  <h2>All Submissions</h2>
    <?php if(isset($_SESSION['login'])){?>
  
  <div class="grid_4 alpha">
    <div class="well well-sm">
      <form method="get">
        <label>Filter by Species</label>
        <select class="order-sort" name="species">
          <option value="">ALL</option>
          <?php if(!empty($species_details)){ foreach($species_details as $details){ ?>
          <option <?php if($details['species_id']==$species_id){echo "selected='selected'";} ?> value="<?php echo stripslashes( $details['species_id'] )?>"> <?php echo $details['species_name']; ?> </option>
          <?php } } ?>
        </select>
        <input type="submit" class="btn btn-primary" value="Filter">
      </form>
    </div>
  </div>

  
  <div class="grid_4">
    <div class="well well-sm">
    <form method="get">
      <label>Filter by Status</label> 
      <select class="order-sort" name="status">
        <option value= "">ALL</option>
         <?php 
            $options = array('Accepted'=>1,'Rejected'=>2);
            $status =  mysqli_real_escape_string($con,strip_tags( isset($_GET['status']) ? $_GET['status'] : false ));
            foreach($options as $key => $value){?>
              <option <?php if($status==$value){echo "selected='selected'";} ?> value="<?php echo $value; ?>"> <?php echo $key; ?> </option>
            <?php } ?>
      </select>
      <input type="submit" class="btn btn-primary" value="Filter">
    </form>
    </div>
  </div>
   <div class="grid_3">
   <div class="well"><a href="<?php echo $adminUrl;?>export.php" class="btn btn-success">Export to Excel</a></div>
  </div>
    <div class="grid_12" style="margin-bottom: 32px;">
    <lable><b>Total Accepted Images Count: </b></lable>
  <?php 
    echo count($Aimg_status);?>
 |<form method="post" id="download_zip" style="display: inline-block;">
    <input id="download_file" type="hidden" name="download_file" />
     <a class="d_submit" href="javascript:{}" onclick="document.getElementById('download_zip').submit();">Download All Images <i class="hide fa fa-refresh fa-spin" style="font-size:17px; padding: 0px 4px 0px 3px;"></i></a>
  </form> | <b>Records with Images:</b><?php echo count($Rc_with_img); ?> | <b>Records without Images:</b><?php echo count($Rc_without_img); ?>
</div>

  <?php  if(!empty($submission_details)) { ?>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID </th>
        <th>Species</th>
        <th>Name</th>
        <th>Email ID</th>
        <th>Mobile No.</th>
        <th>Date Of Submission</th>
        <th>Date of Observed
        <th>Status</th>
        <th>Action</th>
        <!-- <th>Images Status</th> --> 
      </tr>
    </thead>
             
        <tbody>

         <?php 
        foreach($submission_details as $submission) { 
          $status = isset($submission['status']) ? $submission['status'] : false;
          $filename = isset($submission['img_status']) ? $submission['img_status'] : false;
          $img_status = $filename == 'NA' ? false : true;
          $SubmissionDate = isset($submission['SubmissionDate']) ? $submission['SubmissionDate'] : false;
          $createDate = new DateTime($SubmissionDate);
          $stripDate = $createDate->format('Y-m-d');
          $date = $submission['date'] !="0000-00-00" ? $submission['date'] : $SubmissionDate;
          $date = date('Y-m-d',strtotime($date));
          ?> 
        <tr>
        <td><?php echo isset($submission['SightingID']) ? $submission['SightingID'] : false;?></td>
        <td><?php echo isset($submission['Species']) ? $submission['Species'] : false; ?></td>
        <td><?php echo isset($submission['SubmitterName']) ? $submission['SubmitterName']: false; ?></td>
        <td><?php echo isset($submission['SubmitterMail']) ?  $submission['SubmitterMail'] : false;?></td>
        <td><?php echo isset($submission['SubmitterMobile']) ? $submission['SubmitterMobile'] : false; ?></td>
        <td><?php echo $stripDate;?></td>
        <td><?php echo $date; ?>
        </td>
        <td><?php if ($status == 1) {echo "Accepted"; } elseif($status == 2){ echo "Rejected";} elseif($status ==0){echo "Pending";}; ?></td>
        <td><a href="submission_details.php?id=<?php echo $submission['SightingID']; ?>">View</a></td> <td><?php echo ($img_status) ? '<i class="fa fa-camera" style="color: green;font-size: 20px;
          text-align: center;
          line-height: revert;"></i>' : '<i class="fa fa-times-rectangle" style="color: #f51212;font-size:20px;
       text-align: center;
    line-height: revert;"></i>'; ?>
    </td>
      </tr>
        <?php } ?> 
    </tbody> 
</table>
<?php 
   
    $species = isset($_GET['species']) ? $_GET['species'] : false;
    $status = isset($_GET['status']) ? $_GET['status'] : false;
  if($species =="" && ($status =="")){ ?>
  <div class="pagination">
        <?php echo $pages->display_pages(); ?>
  </div> 
  
    <?php } ?>
<?php } else{ echo '<div class="grid_12 alpha omega"><h3>NO SUBMISSION DETAILS FOUND</h3></div>'; ?>
<?php } ?>
<?php }else{
        header('Location: /admin/login.php');
        exit;
    } 

function getAllPAFromStateID($con)
{
    $dbquery = "SELECT * FROM images where image_status = 1" ;
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

function download_img($urlFile){
    $file_name  =   basename($urlFile);
    //save the file by using base name
    $fn         =   file_put_contents($file_name,file_get_contents($urlFile));
    header("Expires: 0");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Content-type: application/file");
    header('Content-length: '.filesize($file_name));
    header('Content-disposition: attachment; filename="'.basename($file_name).'"');
    readfile($file_name);
}

?>
