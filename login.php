<?php 





header('Content-Type: application/json');





	 include('config.php');
	 

	 

	 $username = mysqli_real_escape_string($conn , $_REQUEST['username']);

 $password = mysqli_real_escape_string($conn ,$_REQUEST["password"]  );
 
 


$login = mysqli_query( $conn , "select admins.*,auth_key from admins,auth where username = '$username' and password = '$password' order by rand() limit 1") or die('Error in login query');
$row = mysqli_fetch_assoc($login);

	
$temp_array= $row;
	

if (mysqli_num_rows($login) >= 1) {


	 



//array_walk_recursive($temp_array,function(&$item){$item=strval($item);});

echo json_encode(array('status' => true,'msg' => 'success',"login"=>$temp_array));




} else {

	

    // treat this as json

	



echo json_encode(array('status' => false,'msg' => 'login faild',"login"=>$temp_array));


mysqli_close($conn);



}







?>