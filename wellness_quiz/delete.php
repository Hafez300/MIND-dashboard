<?php 




header('Content-Type: application/json');



	 include('../config.php');


if( isset($_POST['q_id']) &&  isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);

 $q_id = mysqli_real_escape_string($conn , $_POST['q_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {


   
    $group_sql = "delete from wellness_quiz where id = '$q_id' ";
   
    


$register = mysqli_query($conn,$group_sql) or $error = mysqli_error($conn);



    $product_sql = "delete from wellness_products where question_id = '$q_id' ";
    
    $excute = mysqli_query($conn,$product_sql) or $error = mysqli_error($conn);
    

	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"q_id"=>$q_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"q_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"q_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' q_id and user_id are required',"q_id"=>null));
}


?>