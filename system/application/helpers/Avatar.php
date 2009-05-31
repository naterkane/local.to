<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* CodeIgniter Gravatar Helper
*
* @package      CodeIgniter
* @subpackage   Helpers
* @category     Helpers
*/
class Avatar extends Gravatar{
	private $upload_dir = "upload_pic"; 				// The directory for the images to be saved in
	private $upload_path;				// The path to where the image will be saved
	private $large_image_prefix = "resize_"; 			// The prefix name to large image
	private $thumb_image_prefix = "thumbnail_";			// The prefix name to the thumb image
	private $large_image_name;     // New name of the large image (append the timestamp to the filename)
	private $thumb_image_name;     // New name of the thumbnail image (append the timestamp to the filename)
	var $large_image_location;
	var $thumb_image_location;
	private $max_file = "3"; 							// Maximum file size in MB
	private $max_width = "500";							// Max width allowed for the large image
	private $thumb_width = "100";						// Width of thumbnail image
	private $thumb_height = "100";						// Height of thumbnail image
	// Only one of these image types should be allowed for upload
	private $allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
	private $allowed_image_ext; // do not change this
	private $image_ext = "";	// initialise variable, do not change this.
	
	function __construct()
	{
		if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
		    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); //assign the timestamp to the session variable
			$_SESSION['user_file_ext']= "";
		}
		$this->upload_path = $this->upload_dir."/";				// The path to where the image will be saved
		$this->large_image_name = $this->large_image_prefix.$_SESSION['random_key'];     // New name of the large image (append the timestamp to the filename)
	 	$this->thumb_image_name = $this->thumb_image_prefix.$_SESSION['random_key'];     // New name of the thumbnail image (append the timestamp to the filename)
		$this->allowed_image_ext = array_unique($this->allowed_image_types); // do not change this
		foreach ($this->allowed_image_ext as $mime_type => $ext) {
		    $this->image_ext.= strtoupper($ext)." ";
		}
			//Image Locations
		$this->large_image_location = $this->upload_path.$this->large_image_name.$_SESSION['user_file_ext'];
		$this->thumb_image_location = $this->upload_path.$this->thumb_image_name.$_SESSION['user_file_ext'];
		//Create the upload directory with the right permissions if it doesn't exist
		if(!is_dir($this->upload_dir)){
			mkdir($this->upload_dir, 0777);
			chmod($this->upload_dir, 0777);
		}
		
		//Check to see if any images with the same name already exist
		if (file_exists($this->large_image_location)){
			if(file_exists($this->thumb_image_location)){
				$this->thumb_photo_exists = "<img src=\"".$this->upload_path.$this->thumb_image_name.$_SESSION['user_file_ext']."\" alt=\"Thumbnail Image\"/>";
			}else{
				$this->thumb_photo_exists = "";
			}
		   	$this->large_photo_exists = "<img src=\"".$this->upload_path.$this->large_image_name.$_SESSION['user_file_ext']."\" alt=\"Large Image\"/>";
		} else {
		   	$this->large_photo_exists = "";
			$this->thumb_photo_exists = "";
		}
		
		if (isset($_POST["upload"])) { 
			//Get the file information
			$this->userfile_name = $_FILES['image']['name'];
			$this->userfile_tmp = $_FILES['image']['tmp_name'];
			$this->userfile_size = $_FILES['image']['size'];
			$this->userfile_type = $_FILES['image']['type'];
			$this->filename = basename($_FILES['image']['name']);
			$this->file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
			
			//Only process if the file is a JPG, PNG or GIF and below the allowed limit
			if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
				
				foreach ($this->allowed_image_types as $mime_type => $ext) {
					//loop through the specified image types and if they match the extension then break out
					//everything is ok so go and check file size
					if($file_ext==$ext && $userfile_type==$mime_type){
						$error = "";
						break;
					}else{
						$error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
					}
				}
				//check if the file size is above the allowed limit
				if ($userfile_size > ($max_file*1048576)) {
					$error.= "Images must be under ".$max_file."MB in size";
				}
				
			}else{
				$error= "Select an image for upload";
			}
			//Everything is ok, so we can upload the image.
			if (strlen($error)==0){
				
				if (isset($_FILES['image']['name'])){
					//this file could now has an unknown file extension (we hope it's one of the ones set above!)
					$this->large_image_location = $this->large_image_location.".".$file_ext;
					$this->thumb_image_location = $this->thumb_image_location.".".$file_ext;
					
					//put the file ext in the session so we know what file to look for once its uploaded
					$_SESSION['user_file_ext']=".".$file_ext;
					
					move_uploaded_file($userfile_tmp, $large_image_location);
					chmod($large_image_location, 0777);
					
					$width = $this->getWidth($large_image_location);
					$height = $this->getHeight($large_image_location);
					//Scale the image if it is greater than the width set above
					if ($width > $max_width){
						$scale = $max_width/$width;
						$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
					}else{
						$scale = 1;
						$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
					}
					//Delete the thumbnail file so the user can create a new one
					if (file_exists($thumb_image_location)) {
						unlink($thumb_image_location);
					}
				}
				//Refresh the page to show the new uploaded image
				header("location:".$_SERVER["PHP_SELF"]);
				exit();
			}
		}
		if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
			//Get the new coordinates to crop the image.
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			//Scale the image to the thumb_width set above
			$scale = $thumb_width/$w;
			$cropped = $this->resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
			//Reload the page again to view the thumbnail
			header("location:".$_SERVER["PHP_SELF"]);
			exit();
		}
		
		
		if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
		//get the file locations 
			$large_image_location = $upload_path.$large_image_prefix.$_GET['t'];
			$thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'];
			if (file_exists($large_image_location)) {
				unlink($large_image_location);
			}
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
			header("location:".$_SERVER["PHP_SELF"]);
			exit(); 
		}
	}
	
	##########################################################################################################
	# IMAGE FUNCTIONS																						 #
	# You do not need to alter these functions																 #
	##########################################################################################################
	/**
	 * Resizes an image with the help of the GD library
	 * 
	 * @see imagecopyresampled()
	 * @return 
	 * @param object $image
	 * @param string $width
	 * @param string $height
	 * @param string $scale
	 */
	function resizeImage($image,$width,$height,$scale) {
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
	  	}
		imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		
		switch($imageType) {
			case "image/gif":
		  		imagegif($newImage,$image); 
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($newImage,$image,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$image);  
				break;
	    }
		
		chmod($image, 0777);
		return $image;
	}
	
	function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
	  	}
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) {
			case "image/gif":
		  		imagegif($newImage,$thumb_image_name); 
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
	    }
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}

	/**
	 * 
	 * @return 
	 * @param object $image
	 */
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	/**
	 * 
	 * @return 
	 * @param object $image
	 */
	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}
		
	
	/**
	 * Generates an image tag of a gravatar from the Gravatar website using the specified params
	 * 
	 * @access public
	 * @return string
	 * @param string $email
	 * @param string $rating[optional]
	 * @param integer $size[optional]
	 * @param string $default[optional]
	 */
	
	function img( $email, $size = null,$rating = 'X',  $default = 'http://gravatar.com/avatar.php' ) {
	    // Hash the email address
	    
		
	    // Return the generated URL
	    return "<img width=\"{$size}\" height=\"{$size}\" src=\"{$this->url($email,$size,$rating,$default)}\" />";
	}
	
	/**
	 * Fetches the url of a gravatar from the Gravatar website using the specified params
	 * 
	 * @access public
	 * @return string
	 * @param string $email
	 * @param string $rating[optional]
	 * @param integer $size[optional]
	 * @param string $default[optional]
	 */
	function url( $email, $size = null,$rating = 'X',  $default = 'http://gravatar.com/avatar.php' ) {
	    // Hash the email address
	    $email = md5(strtolower($email));
		$size = ($size == null)?'80':$size;
	    // Return the generated URL
	    return "http://gravatar.com/avatar.php?gravatar_id="
	        .$email."&amp;rating="
	        .$rating."&amp;size="
	        .$size."&amp;default="
	        .urlencode($default);
	}
	
	
	
}
?>