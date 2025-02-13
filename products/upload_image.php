<?
 header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

function cwUpload($field_name = '', $target_folder = '', $file_name = '', $thumb = FALSE, $thumb_folder = '', $thumb_width = '', $thumb_height = ''){

    //folder path setup
    $target_path = $target_folder;
    $thumb_path = $thumb_folder;
    
    //file name setup
    $filename_err = explode(".",$_FILES[$field_name]['name']);
    $filename_err_count = count($filename_err);
    $file_ext = $filename_err[$filename_err_count-1];
    if($file_name != ''){
        $fileName = time().$file_name.'.'.$file_ext;
    }else{
        $fileName = time().$_FILES[$field_name]['name'];
    }
    
    //	$filename = explode(".",$image);
//	$filename[0] = time().$i.".";

    //upload image path
    $upload_image = $target_path.basename($fileName);
    
    //upload image
    if(move_uploaded_file($_FILES[$field_name]['tmp_name'],$upload_image))
    {
        //thumbnail creation
        if($thumb == TRUE)
        {
            $thumbnail = $thumb_path.$fileName;
            list($width,$height) = getimagesize($upload_image);
            $thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
            switch($file_ext){
                case 'jpg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'jpeg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;

                case 'png':
                    $source = imagecreatefrompng($upload_image);
                    $bck   = imagecolorallocate( $source , 0, 0, 0 );
                imagecolortransparent( $source, $bck );
                 imagealphablending( $source, false );
                imagesavealpha( $source, true );
                    break;
                case 'gif':
                    $source = imagecreatefromgif($upload_image);
                    break;
                default:
                    $source = imagecreatefromjpeg($upload_image);
            }

            imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
            switch($file_ext){
                case 'jpg' || 'jpeg':
                    imagejpeg($thumb_create,$thumbnail,300);
                    break;
                case 'png':
                    imagepng($thumb_create,$thumbnail,300);
                    break;

                case 'gif':
                    imagegif($thumb_create,$thumbnail,300);
                    break;
                default:
                    imagejpeg($thumb_create,$thumbnail,300);
            }

        }

        return $fileName;
    }
    else
    {
        return false;
    }
}



if(!empty($_FILES['image']['name'])){
    
    //call thumbnail creation function and store thumbnail name
    $upload_img = cwUpload('image','uploads/','',TRUE,'uploads/thumbs/','200','160');
    
    //full path of the thumbnail image
    $thumb_src = $upload_img;
    
    //set success and error messages
    $message = $upload_img?"<span style='color:#008000;'>Image thumbnail have been created successfully.</span>":"<span style='color:#F00000;'>Some error occurred, please try again.</span>";
    
    
    echo json_encode(array('status' => true,'msg' => 'success',"image_name"=>$thumb_src));
}else{
    
    //if form is not submitted, below variable should be blank
    $thumb_src = '';
    $message = '';
}


?>