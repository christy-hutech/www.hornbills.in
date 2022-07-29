<?php 
session_start();

error_reporting(0);
// include_once('../../private/constants.php');

$username = $password = $userError = $passError = '';  
if(isset($_POST['submit'])){
  $username = $_POST['username']; $password = $_POST['password'];
  if($username === 'admin' && $password === G212bgiqLmn1){
    $_SESSION['login'] = true; header('LOCATION: /admin'); die();
  }
  
  if($username === 'admin' && $password === G212bgiqLmn2){
    $_SESSION['login'] = true; header('LOCATION: /admin'); die();
  }
  
  if($username !== 'admin')$userError = 'Invalid Username';
  if($password !== 'password')$passError = 'Invalid Password';
}
    
    $msg = $_GET['m'];

?>
<?php include_once('header.php'); ?> 

<div class="content">
   <?php if ($msg == 42){ ?>
     <div class="alert alert-success">Your password has been sent to your email address. </div>
      <?php } ?>
       <?php if ($msg == 23){ ?>
     <div class="alert alert-success">You have logged out.</div>
      <?php } ?>
 <h1 class="page-title">Login</h1>
    <form action="" method="post">
        
    <fieldset>
        <label for="username">User Name</label>
      <input type="text" id="username" name="username"/>
      <?php if($userError != '') { ?><div class="alert alert-danger margintop20"><?php echo $userError; ?></div><?php } ?>
    </fieldset>
    
    <fieldset>
        <label for="username">Password</label>
      <input type="password" name ="password" id="password"/>
      <?php if($passError != '') { ?><div class="alert alert-danger margintop20"><?php echo $passError; ?></div><?php } ?>
    </fieldset>
    
    <fieldset class="clearfix">
        <a href="forgot-password.php" class="alignleft margintop10">Forgot Password?</a>
        <input type="submit" class="btn btn-sm btn-success alignright" value="Login" name="submit" />
    </fieldset>
  
  </form>

</div>

<?php include_once('footer.php'); ?>
