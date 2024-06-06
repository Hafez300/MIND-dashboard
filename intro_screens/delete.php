<?php 




header('Content-Type: application/json');



	 include('../config.php');


if( isset($_POST['intro_id']) &&  isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);

 $intro_id = mysqli_real_escape_string($conn , $_POST['intro_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {


   
    $group_sql = "delete from onboarding where id = '$intro_id' ";
   
    


$register = mysqli_query($conn,$group_sql) or $error = mysqli_error($conn);



if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"intro_id"=>$intro_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"intro_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"intro_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' intro_id and user_id are required',"intro_id"=>null));
}


?>