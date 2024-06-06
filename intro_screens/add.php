<?php 




header('Content-Type: application/json');







	 include('../config.php');


if( isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);

 $title_en = mysqli_real_escape_string($conn ,$_POST['title_en']);
$title_ar = mysqli_real_escape_string($conn ,$_POST['title_ar']);
 
 $desc_en = mysqli_real_escape_string($conn ,$_POST['desc_en']);
 $desc_ar = mysqli_real_escape_string($conn ,$_POST['desc_ar']);

$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  	$group_icon = $_FILES['photo']['name'];
  	
	$filename = explode(".",$group_icon);
	$filename[0] = time().".".end($filename);



	$group_icon = $baseurl.$filename[0];
	$target = "images/".basename($group_icon);

  	if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {

  
  		
  		
  	$photo = $filename[0];
  	
  	}
    
    $rating_sql = "insert into onboarding (`title_en`,
  `title_ar`,
    `desc_en`,
    `desc_ar`,
    `image`
   ) values ('$title_en','$title_ar','$desc_en','$desc_ar','$photo') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"intro_id"=>$last_id));



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
    
    echo json_encode(array('status' => false,'msg' => ' user_id , titles, desc are required',"intro_id"=>null));
}


?>