<?php 




header('Content-Type: application/json');



	 include('../config.php');



if(isset($_POST['q_id']) && isset($_POST['user_id']) &&  isset($_POST['question_en']) &&  isset($_POST['question_ar']) &&  isset($_POST['gender']) &&  isset($_POST['age'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $q_id = mysqli_real_escape_string($conn , $_POST['q_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);
 
 $question_en = mysqli_real_escape_string($conn ,$_POST['question_en']);
$question_ar = mysqli_real_escape_string($conn ,$_POST['question_ar']);
 
 $gender = mysqli_real_escape_string($conn ,$_POST['gender']);
 
  $age = mysqli_real_escape_string($conn ,$_POST['age']);


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {



 
  if(isset($_FILES['image']['name']))
  {
  	$image = $_FILES['image']['name'];
  	
	$filename = explode(".",$image);
	$filename[0] = time().".".end($filename);



	$image = $baseurl.$filename[0];
	$target = "images/".basename($image);

  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

  
  		
  		
  	$w_image = $filename[0];
  	}
  	

 
	
	    
	    $w_image = " image = '$w_image'";
	}
else 

{
    $w_image = " image = image";
    
    
}
    
    $group_sql = "update wellness_quiz set `question_en` = '$question_en',
  `question_ar` = '$question_ar',
    `gender` = '$gender',
    `age` = '$age',
    $w_image
    where id = ' $q_id' ";
   
    


$register = mysqli_query($conn,$group_sql) or $error = mysqli_error($conn);


	
$last_id = $q_id;
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"q_id"=>$last_id));



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
    
    echo json_encode(array('status' => false,'msg' => ' cat_id is required',"q_id"=>null));
}


?>