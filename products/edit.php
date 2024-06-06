<?php 




header('Content-Type: application/json');


	 include('../config.php');


if( isset($_POST['record_id']) && isset($_POST['user_id']) && isset($_POST['name_en']) && isset($_POST['short_desc_en']) && isset($_POST['description_en']) && isset($_POST['category_id']) && isset($_POST['list_price'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $record_id = mysqli_real_escape_string($conn ,$_POST['record_id']);

 $name_en = mysqli_real_escape_string($conn ,$_POST['name_en']);
$name_ar = mysqli_real_escape_string($conn ,$_POST['name_ar']);
 
 $short_desc_en = mysqli_real_escape_string($conn ,$_POST['short_desc_en']);
 
 
 $short_desc_ar = mysqli_real_escape_string($conn , $_POST['short_desc_ar']);


 $description_en = mysqli_real_escape_string($conn , $_POST['description_en']);

 $description_ar = mysqli_real_escape_string($conn ,$_POST['description_ar']);
 
 $quantity = mysqli_real_escape_string($conn ,$_POST['quantity']);
 
$category_id = mysqli_real_escape_string($conn ,$_POST['category_id']);

$group_id = mysqli_real_escape_string($conn ,$_POST['group_id']);
 
 if(isset($_POST['main_image']))
 {
     
 
 $main_image = mysqli_real_escape_string($conn ,$_POST['main_image']);

$thumb_image  =$main_image ;

$mainimage = " `main_image`	= 	'$main_image', `thumb_image`	= 	'$thumb_image', ";
}
else
{
    
    $mainimage = " `main_image`	= 	main_image, `thumb_image`	= 	thumb_image, ";
    
}


 $sku = mysqli_real_escape_string($conn , $_POST['sku']);

 $list_price = mysqli_real_escape_string($conn ,$_POST['list_price']);
 
$sale_price = mysqli_real_escape_string($conn ,$_POST['sale_price']);
 
 $barcode = mysqli_real_escape_string($conn ,$_POST['barcode']);
 
 $added_by = mysqli_real_escape_string($conn ,$_POST['user_id']);

$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  
    
    $edit_sql = "update `products` set `name_en`= '$name_en',
`name_ar`	= 	'$name_ar',
`short_desc_en`	= 	'$short_desc_en',
`short_desc_ar`	= 	'$short_desc_ar',
`description_en`	= 	'$description_en',
`description_ar`	= 	'$description_ar',
`quantity`	= 	'$quantity',
`category_id`	= 	'$category_id',
group_id = '$group_id',
$mainimage
`sku`	= 	'$sku',
`list_price`	= 	'$list_price',
`sale_price`	= 	'$sale_price',
`barcode`	= 	'$barcode',
`added_by`	= 	'$added_by' where id = '$record_id' ";
    


$register = mysqli_query($conn,$edit_sql) or $error = mysqli_error($conn);


	
$last_id = $record_id;
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"products_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"products_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"products_id"=>null));
}

}
else 
{
    
   
    echo json_encode(array('status' => false,'msg' => ' user_id is required , name_en, short_desc_en,description_en,category_id and list_price are required    ',"products_id"=>null));
}


?>