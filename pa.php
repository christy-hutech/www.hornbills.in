<? 
require_once("library/db_connect.php"); 
$state_id = 2;//$_GET['pa_state_id'];
//echo $state_id;
var_dump($state_id);
$pas = getAllPAFromStateID($con,strip_tags($state_id));

$pa_list = '<option value="-1">Choose a protected area</option>';
foreach($pas  as $pa)
{
    $pa_list =  $pa_list .'<option value="1">test</option>';
}
echo $pa_list;
function getAllPAFromStateID($con,$stateid)
{
    if(!is_numeric($stateid)){
        return null;
    }
    
    $dbquery = "SELECT * FROM pa_master where State_Id = '".mysqli_real_escape_string($con,$stateid)."'" ;

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

//$pa_list =  $pa_list .'<option value="'.$pa['PA_ID'].'">'.$pa['FullName'].'</option>';
?>