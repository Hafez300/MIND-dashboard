<?php



header('Content-Type: application/json');



//$data = json_decode(file_get_contents('php://input'),true);



	include('../config.php');

if(isset($_REQUEST['product_id']) ) {

	$product_id = mysqli_real_escape_string($conn,$_REQUEST['product_id']);

$auth_key = mysqli_real_escape_string($conn,$_REQUEST['auth_key']);




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
    `products`.`active`,(select COUNT(*) from products) as total_products from products
    inner join categories on FIND_IN_SET(categories.id, category_id) > 0
    left join groups on FIND_IN_SET(groups.id, group_id) > 0
    where products.id = '$product_id'
    group by `products`.`id`  ");

	





 if (mysqli_num_rows($query) >= 1) {

	



	while($row = mysqli_fetch_assoc($query)){
	    
	    	isset($row["main_image"])?$row["main_image"] =  $baseurl."products/uploads/".$row["main_image"]:null;
	    isset($row["thumb_image"])?$row["thumb_image"] =  $baseurl."products/uploads/thumbs/".$row["thumb_image"]:null;
	    
	    
	    	     $photos_array = array();

	$rating_photos = mysqli_query($conn,"select id,`name` as photo , thumb as thumbnail_photo from images
	 where product_id = '$row[id]'");
		while($photos_result = mysqli_fetch_assoc($rating_photos)){
		    
		     		     if($photos_result["photo"] <> '' && $photos_result["photo"] <> null){$photos_result["photo"] =  $baseurl."products/uploads/".$photos_result["photo"];} else {$photos_result["photo"] = null;}
		     		     
		     		     if($photos_result["thumbnail_photo"] <> '' && $photos_result["thumbnail_photo"] <> null){$photos_result["thumbnail_photo"] =  $baseurl."products/uploads/thumb/".$photos_result["thumbnail_photo"];} else {$photos_result["thumbnail_photo"] = null;}
		     		     

		    $photos_array[]= $photos_result; 
		    
		}
		
		
		$variations = array();
		
	    $variation_data = mysqli_query($conn,"SELECT distinct variation_options.id as option_id,variation_options.variation_id,variations.name_en as variation_name_en , variations.name_ar as variation_name_ar,value_en,value_ar,product_variation.product_id  FROM variation_options
inner join variations on variations.id = variation_options.variation_id
left join product_variation on variation_options.id = product_variation.variation_option_id
where  main_product_id = '$row[id]'");
		while($variation_result = mysqli_fetch_assoc($variation_data)){
		    

		     		     

		    $variation_array[]= $variation_result; 
		    
		}
		
	    $row["photos"] = $photos_array;
	    
	    $row["variations"] = $variation_array;
	    
	    $temp_array = $row;
	    
	}



  





echo json_encode(array('status' => true,'msg' => 'success',"product_view"=>$temp_array));



 }	

 



else

{

	echo json_encode(array('status' => false,'msg' => 'No Data',"product_view"=>''));

	

}

}

else
{
   echo json_encode(array('status' => false,'msg' => 'Wrong key',"product_view"=>"")); 
    
}

}
else
{
echo json_encode(array('status' => false,'msg' => ' product_id is Missing ',"product_view"=>""));

}


?>