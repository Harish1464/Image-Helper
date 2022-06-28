<?php
const ABOUTUS_IMAGE_BASE_PATH = "/uploads/images/about_us/"; 
const SERVICE_IMAGE_BASE_PATH = "/uploads/images/service/"; 
const TEAM_IMAGE_BASE_PATH = "/uploads/images/team/"; 
const BLOG_IMAGE_BASE_PATH = "/uploads/images/blog/"; 
const OFFER_IMAGE_BASE_PATH = "/uploads/images/offers/"; 

function getStaticImageQuality(){
    return 30;
}
function resizeAndUploadImage($image, $dir, $quality=100, $thumb="" ){ 
    try{
        // parameters of resizeAndUploadImage function are image_source, path you want to save image, compression level(0 to 9), resolution("widthxhight")

                if(!File::exists($dir)){
                    File::makeDirectory($dir, 0777, true, true);
                }
                
            $getGUID = md5(rand(945976,1232345)."-".time()."-".rand(3456676,3423762)).time();
           if($image){
                list($width,$height)=getimagesize($image); // get the dimensions of image and assign it to the variables
                
                if($thumb != ""){   //if $thumb is not null
                    if(strpos($thumb, 'x') !== false){ //if $thumb contains x
                        list($w,$h) = explode('x', $thumb); //explode x from $thumb and assign the values to $w and $h
                    } else{
                        $w = $thumb;    // if $thumb doesnot contains x then it must contain width only so assign it to $w variable
                    }
                    // list($w,$h) = explode('x', $thumb);
                    // $w = $thumb; 
                    $ratio = $w/$width; // calculate the ratio of new width to original width
                    $nwidth = $w;       // assign $w to $nwidth
                    $nheight = $height*$ratio;  // calculate new height as constraint proportion and assign it to $nheight
                }else{
                    list($nwidth,$nheight)=getimagesize($image);
                }

                $newimage=imagecreatetruecolor($nwidth,$nheight);  // creates a new blank black colored image with provided width and height 
                $file_extension = strtolower($image->getClientOriginalExtension()); //get file extension of the given image

                if($file_extension == 'jpeg' || $file_extension == 'jpg'){  // if the image is jpeg
           
                    $source=imagecreatefromjpeg($image);        // assign the image to source image

                    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height); // this function will resize the image to new width and height
                    $image_name = $getGUID.".".$file_extension;

                    // echo $newimage;
                    // echo $dir.$image_name;
                    // echo $quality;
                    // exit;
                    $status = imagejpeg($newimage,$dir.$image_name,$quality);    // this function will save a jpeg image & return true to status and takes (resized image, destination path to store that image and the quality in range 0-9) as function parameters
                }elseif($file_extension == 'png'){
                    $source=imagecreatefrompng($image);        
                    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
                    $image_name = $getGUID.".".$file_extension;
                    $qualityBasedOnPNG = ($quality*9)/100;
                    $status = imagepng($newimage,$dir.$image_name,$qualityBasedOnPNG);    
                }elseif($file_extension == 'gif'){
                    $source=imagecreatefromgif($image);        
                    imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
                    $image_name = $getGUID.".".$file_extension;
                    $status = imagegif($newimage,$dir.$image_name,$quality);    
                }else{
                    $status= false; // the status is false if the image is not saved anyway
                }
                if($status){
                    return $image_name;  // after successful transaction this will return a image name.
                }else{
                    return 'null'; // otherwise it will return null
                }

           }else{
               return null;  // if the request doesnot contain image file it will return null
           }
    }catch(Exception $e){
        return $e->getMessage();
    }
}

function removeOldImage($imageName,$folderName){
    if (is_file(public_path() . '/uploads/images/'.$folderName.'/'.$imageName)) {
        $imageFile = public_path() . '/uploads/images/'.$folderName.'/'.$imageName;
        unlink($imageFile);
    }
}