<?php 
/**
 * Create avatars
 *
 **/ 
class Avatar{
	

	public $allowed_mime_types = array('image/jpeg','image/pjpeg','image/gif','image/png');
	public $dir = array();
	public $controller;
	public $errors = array();
	public $file;
	public $fileNew;
	public $image_location = 'images';
	public $model;
	public $zoom_crop = 1;//do not zoom crop
	
	/**
	 * Add an error to the results
	 */
	function addError($msg){
		$this->errors[] = $msg;
	}
	
	/**
	 * Generate a thumbnail
	 *
	 * This is the method that actually does the thumbnail generation by setting up 
	 * the parameters and calling phpThumb
	 *
	 * @param string $filename
	 * @param string $model	
	 * @return bool
	 **/
	function make($dir, $sourceFileName, $newFileName, $width, $height){
		require_once(APPPATH . 'libraries/phpthumb/phpthumb.class.php');
		$this->phpthumb = new Phpthumb();
		$this->dir = $dir;		
		$this->file = $this->dir . '/' . $sourceFileName;
		$this->fileNew = $this->dir . '/' . $newFileName;
		$this->phpthumb->config_cache_directory = $this->dir;
		if (($this->hasSize()) && ($this->isWritable())) {
			$this->phpthumb->setSourceFilename($this->file);
			$this->phpthumb->setParameter('new', $newFileName);
			$this->phpthumb->setParameter('w', $width);
			$this->phpthumb->setParameter('h', $height);
			$this->phpthumb->setParameter('zc', $this->zoom_crop);
			if($this->phpthumb->generateThumbnail()){
				if(!$this->phpthumb->RenderToFile($newFileName)){
					$this->addError('Could not render file to: '. $this->dir['dirname']);
				}
			} else {
				$this->addError('could not generate thumbnail');
			}
			chmod($this->fileNew, 0777);	
			if ($this->isError()) {
				$this->remove($this->fileNew);
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}	
	}

	function makeAll($dir, $sourceFileName, $username){
		$size = 24;
		for ($i=0; $i < 4; $i++) { 
			if (!$this->make($dir, $sourceFileName, $dir . '/' .  $username . '_' . $size .'.jpg', $size, $size)) 
			{
				$this->addError('Thumbnail could not be created.');
				return false;
			}
			$size = $size + 12;
		}
		return true;
	}

	/**
	 * Does a file have any size?
	 *
	 * @return bool
	 */
	function hasSize() {
		if(!file_exists($this->file)){
			$this->addError('This file does not exist');
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Are there any errors?
	 */
	function isError() {
		if(count($this->errors) > 0){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Is the file system writable?
	 *
	 * @return bool
	 */
	function isWritable() {
		if(!is_writable($this->dir)){
			$this->addError('directory: ' . $this->dir['dirname'] . ' is not writable.');
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Remove a thumbnail
	 */
	function remove($path) {
		if(file_exists($path)){
			unlink($path);
		}
	}

	/**
	 * Initialize
	 */
	function startup( &$controller ) {
      $this->controller = &$controller;
    }

	/**
	 * Is our file is one of the valid mime type?
	 *
	 * @return bool	
	 */
	function validMimeType() {
		$type = filetype($this->file);
		if(!in_array($file, $this->allowed_mime_types)){
			$this->addError('Invalid File type: '.$this->file['type']);
			return false;
		} else {
			return true;
		}
	}
	
}
?>