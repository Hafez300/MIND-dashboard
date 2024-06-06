<?php



header('Content-Type: application/json');



//$data = json_decode(file_get_contents('php://input'),true);



	include('../config.php');

if(isset($_REQUEST['user_id']) ) {

$user_id = mysqli_real_escape_string($conn,$_REQUEST['user_id']);
$auth_key = mysqli_real_escape_string($conn,$_REQUEST['auth_key']);
$item_count = (isset($_REQUEST['item_count'])) ? $_REQUEST['item_count'] : 0;
$limit_count = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != 0) ? $_REQUEST['limit'] : 50;



 $sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

        

        

     

		

	$query = mysqli_query($conn,"SELECT `products`.`id`,
    `products`.`name_en`,
    `products`.`name_ar`,
    `products`.`short_desc_en`,
    `products`.`short_desc_ar`,
    `products`.`description_en`,
    `products`.`description_ar`,
    `products`.`quantity`,
     `products`.`category_id`,
     GROUP_CONCAT(DISTINCT categories.cat_name_en ORDER BY categories.id) category_name,
      `products`.`group_id`,
      GROUP_CONCAT(DISTINCT groups.group_name_en ORDER BY groups.id) group_name,
    `products`.`main_image`,
    `products`.`thumb_image`,
    `products`.`sku`,
    `products`.`list_price`,
    `products`.`sale_price`,
    `products`.`barcode`,
    `products`.`added_by`,
    `products`.`added_on`,
    `products`.`main_product`,
    `products`.`active`,(select COUNT(*) from products where products.active = 1) as total_products from products
    inner join categories on FIND_IN_SET(categories.id, category_id) > 0
    left join groups on FIND_IN_SET(groups.id, group_id) > 0
    group by `products`.`id`
    limit $limit_count OFFSET $item_count ");

	





 if (mysqli_num_rows($query) >= 1) {

	



	while($row = mysqli_fetch_assoc($query)){
	    
	    	isset($row["main_image"])?$row["main_image"] =  $baseurl."products/uploads/".$row["main_image"]:null;
	    isset($row["thumb_image"])?$row["thumb_image"] =  $baseurl."products/uploads/thumbs/".$row["thumb_image"]:null;
	    $temp_array[] = $row;
	    
	}



  





echo json_encode(array('status' => true,'msg' => 'success',"products_list"=>$temp_array));



 }	

 



else

{

	echo json_encode(array('status' => false,'msg' => 'No Data',"products_list"=>[]));

	

}

}

else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"products_list"=>[])); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => 'Missing Parameters',"products_list"=>[]));

}


?>