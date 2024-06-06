<?php 


 header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');


	 include('../config.php');


if( isset($_POST['user_id']) && isset($_POST['name_en']) && isset($_POST['short_desc_en']) && isset($_POST['description_en']) && isset($_POST['category_id']) && isset($_POST['list_price'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);




 $name_en = mysqli_real_escape_string($conn ,$_POST['name_en']);
$name_ar = mysqli_real_escape_string($conn ,$_POST['name_ar']);
 
 $short_desc_en = mysqli_real_escape_string($conn ,$_POST['short_desc_en']);
 
 
 $short_desc_ar = mysqli_real_escape_string($conn , $_POST['short_desc_ar']);


 $description_en = mysqli_real_escape_string($conn , $_POST['description_en']);

 $description_ar = mysqli_real_escape_string($conn ,$_POST['description_ar']);
 
 $quantity = mysqli_real_escape_string($conn ,$_POST['quantity']);
 
$category_id = mysqli_real_escape_string($conn ,$_POST['category_id']);

$group_id = mysqli_real_escape_string($conn ,$_POST['group_id']);
 
 $main_image = mysqli_real_escape_string($conn ,$_POST['main_image']);

$thumb_image  =$main_image ;


 $sku = mysqli_real_escape_string($conn , $_POST['sku']);

 $list_price = mysqli_real_escape_string($conn ,$_POST['list_price']);
 
$sale_price = mysqli_real_escape_string($conn ,$_POST['sale_price']);
 
 $barcode = mysqli_real_escape_string($conn ,$_POST['barcode']);
 
 $added_by = mysqli_real_escape_string($conn ,$_POST['user_id']);

$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  	$cat_icon = $_FILES['cat_icon']['name'];
  	
	$filename = explode(".",$cat_icon);
	$filename[0] = time().".".end($filename);



	$cat_icon = $baseurl.$filename[0];
	$target = "icons/".basename($cat_icon);

  	if (move_uploaded_file($_FILES['cat_icon']['tmp_name'], $target)) {

  
  		
  		
  	$icon = $filename[0];
  	
  	}
    
    $rating_sql = "INSERT INTO `products`
(
`name_en`,
`name_ar`,
`short_desc_en`,
`short_desc_ar`,
`description_en`,
`description_ar`,
quantity,
`category_id`,
group_id,
`main_image`,
`thumb_image`,
`sku`,
`list_price`,
`sale_price`,
`barcode`,
`added_by`)
VALUES
(
'$name_en',
'$name_ar',
'$short_desc_en',
'$short_desc_ar',
'$description_en',
'$description_ar',
'$quantity',
'$category_id',
'$group_id',
'$main_image',
'$thumb_image',
'$sku',
'$list_price',
'$sale_price',
'$barcode',
'$added_by') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);
	

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