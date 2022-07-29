<?php
require_once('library/db_connect.php');
require_once('functions.php');
include_once('header.php');

$submitter_details = getSubmitterDetails();
$column_limit = ceil(count($submitter_details)/3);
$submitter_count = 0;
?>
<div class="content upload-content clearfix">
  <div class="grid_12">
  <h2>Contributors</h2>

<?php 
$submitter_det = getSubmitterName();
//print_r($submitter_det); exit;
?>
<?php if(!empty($submitter_details)){?>

	<ul class="contributors-list">
  <?php foreach($submitter_details as $submitter_detail){ $submitter_count++; $ind_count = 0;
      foreach($submitter_det as $submitter_detl)
{
//print_r($submitter_detl);
//print_r($submitter_detail);
if($submitter_detl == $submitter_detail)
$ind_count++;

}
?>

<ul class="contributors-list">
<li><?php echo strtolower($submitter_detail['name']); echo ' - '; echo $ind_count; ?> </li>
<?php
}
?>
</ul>

    
 

<?php // if(!empty($submitter_details)){?>

	<ul class="contributors-list">
  <?php // foreach($submitter_details as $submitter_detail){ $submitter_count++; ?> 
    <li><?php // echo strtolower($submitter_detail['name']); ?></li>
  <?php } ?>
  </ul>

<?php ?>
</div>
</div>
<? include_once('footer.php'); ?> 
