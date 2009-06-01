<?php 
/**
 * Image upload
 *
 */
class Uploader{

		private $count = 0;
		private $dir;		
		private $imageName;
		private $errorMsg;
		private $isError = false;
		private $lastUploadData;
		public $allowedMime = array( 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif');		
		public $files2upload = 0;
		public $maxFileSize = 3584;
		public $uploadedFiles = 0;		
		
		public function __construct()
		{
			$this->dir = dirname(dirname(dirname(dirname(__FILE__)))) . '/webroot/uploads/';
		}
		
		private function getType($type) 
		{
			$imageName = str_replace('image/', '', $type);
			if ($imageName == 'jpeg') 
			{
				$imageName = 'jpg';
			}
			return '.' . $imageName;
		}
		
		private function setError($error) 
		{
			$this->isError = true;
			$this->errorMsg = $error;
		}		

		public function getLastUploadInfo() 
		{
			if(!is_array($this->lastUploadData)) 
			{
				$this->setError('No upload detected.');
			} else 
			{
				return $this->lastUploadData;
			}
		}

		public function getMime($file) 
		{
			if (!function_exists('mime_content_type')) 
			{
				return system(trim('file -bi ' . escapeshellarg ($file)));
			} else 
			{
				return mime_content_type($file);
			}
		}
		
		public function getName() 
		{
			return $this->imageName;
		}

		public function isError()
		{
			return $this->isError;
		}

		public function results() 
		{
			if (true === $this->isError)
			{
				return $this->errorMsg;
			}
			else 
			{
				return 'Your file was uploaded';				
			}
		}
	
		public function success() 
		{
			if (true === $this->isError)
			{
				return false;				
			}
			else
			{
				return true;				
			}
		}

		private function tryUpload($field = null, $fileId, $newName) 
		{
			if (empty($_FILES[$field]['name'])) 
			{
				$this->setError('No file supplied.');
				return false;
			}
			if ($_FILES[$field]['error'] != 0) 
			{				
				switch($_FILES[$field]['error']) {
					case 1:
						$this->setError('The file is too large (server).');
					break;
					
					case 2:
						$this->setError('The file is too large (form).');
					break;
					
					case 3:
						$this->setError('The file was only partially uploaded.');
					break;
					
					case 4:
						$this->setError('No file was uploaded.');
					break;
					
					case 5:
						$this->setError('The servers temporary folder is missing.');
					break;
					
					case 6:
						$this->setError('Failed to write to the temporary folder.');
					break;
				}	
				return false;
			}
			if (!in_array($_FILES[$field]['type'], $this->allowedMime)) {
				$this->setError('The file was not saved in an accepted image format.');
				return false;
			}
			if ((filesize($_FILES[$field]['tmp_name'])/1024) > $this->maxFileSize) {
				$this->setError('The file is too large (application).');
				return false;
			}
			$mime_type = $_FILES[$field]['type'];
			if (!is_dir($this->dir)) {
				if (!mkdir($this->dir)) {
					$this->setError('The folder for the file upload could not be created.');
					return false;
				}
			}
			if (!is_writable($this->dir)) {
				$this->setError('The supplied upload directory is not writable.');
				return false;
			}			
			$this->imageName = $newName . $this->getType($_FILES[$field]['type']);
			$file = $this->dir . '/' . $this->imageName;
			if (!copy($_FILES[$field]['tmp_name'], $file)) {
				$this->setError('The uploaded file could not be moved to the created directory');
				return false;
			}
			$this->lastUploadData = array( 'dir' => $this->dir,'file_name' => basename($_FILES[$field]['name']),'mime_type' => $mime_type);
			chmod($file, 0777);
			return true;
		}

		public function upload($field, $folder, $newName) 
		{
			$this->dir = $this->dir . $folder;
			if (!empty($_FILES[$field])) 
			{
				$filesCount = sizeof($_FILES[$field]['name']);
				$this->files2upload = $filesCount;
				for ($i = 0; $i < $filesCount; $i++) {
					if ($this->tryUpload($field, $i, $newName)) 
					{
						$this->uploadedFiles++;
					} 
				}
			} else 
			{
				$this->setError('No files supplied.');
			}
		}
		
}

?>