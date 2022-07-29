<? 
if(!isset($_SESSION['login']))
{
    header('Location: /admin/login.php');
    exit;
}
require_once("library/db_connect.php"); 
$state_id = $_GET['pa_state_id'];

$pas = getAllPAFromStateID($con,strip_tags($state_id));
$pa_list = '<option value="-1">Choose a protected area</option>';
foreach($pas  as $pa)
{
    $pa_list =  $pa_list .'<option value="'.$pa['PA_ID'].'">'.$pa['FullName'].'</option>';
}
echo $pa_list;
function getAllPAFromStateID($con,$stateid)
{
    $dbquery = "SELECT * FROM pa_master where State_Id = '".mysqli_real_escape_string($stateid)."'" ;

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
