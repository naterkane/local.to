<?php 
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
 /**
 * Uploader
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Uploader{

	/**
	 * File count
	 * @access private
	 * @var int
	 */
	private $count = 0;
	/**
	 * Directory path
	 * @access private
	 * @var string
	 */	
	private $dir;
	/**
	 * Image Name
	 * @access private
	 * @var string
	 */			
	private $imageName;
	/**
	 * Error message
	 * @access private
	 * @var string
	 */	
	private $errorMsg;
	/**
	 * Is there an error?
	 * @access private
	 * @var boolean
	 */	
	private $isError = false;
	/**
	 * Last uploaded data
	 * @access private
	 * @var array
	 */	
	private $lastUploadData;
	/**
	 * Allowed mime types
	 * @access public
	 * @var array
	 */	
	public $allowedMime = array( 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif');		
	/**
	 * Number of files to upload
	 * @access public
	 * @var int
	 */	
	public $files2upload = 0;
	/**
	 * Maximum file size in bytes
	 * @access public
	 * @var int
	 */	
	public $maxFileSize = 3584;
	/**
	 * Number of files to upload
	 * @access public
	 * @var int
	 */	
	public $uploadedFiles = 0;		

	/**
	 * Class contructor
	 * Sets directory path 
	 * @access public
	 */	
	public function __construct()
	{
		$this->dir = dirname(dirname(dirname(dirname(__FILE__)))) . '/webroot/uploads/';
		//ini_set('upload_tmp_dir',dirname(dirname(dirname(dirname(__FILE__)))).'/system/tmp/'); 
	}
	
	/**
	 * Get File Type
	 * @access private
	 * @param $type Full type
	 * @return string type	
	 */	
	private function getType($type) 
	{
		$imageName = str_replace('image/', '', $type);
		if ($imageName == 'jpeg') 
		{
			$imageName = 'jpg';
		}
		return '.' . $imageName;
	}

	/**
	 * Set an error
	 * 
	 * @access private
	 * @param $error Description of error
	 */	
	private function setError($error) 
	{
		$this->isError = true;
		$this->errorMsg = $error;
	}		

	/**
	 * Get information about the last upload
	 * 
	 * @access public
	 * @return array|null
	 */
	public function getLastUploadInfo() 
	{
		if(!is_array($this->lastUploadData)) 
		{
			$this->setError('No upload detected.');
			return null;
		} else 
		{
			return $this->lastUploadData;
		}
	}

	/**
	 * Get Mime type of file
	 * 
	 * @param $file Name of file
	 * @access public
	 * @return string
	 */
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
	
	/**
	 * Get the name of the image
	 * @access public
	 * @return string
	 */	
	public function getName() 
	{
		return $this->imageName;
	}

	/**
	 * Is there an error?
	 * @access public
	 * @return boolean
	 */
	public function isError()
	{
		return $this->isError;
	}

	/**
	 * Get the results of an upload in the form of an error or success message
	 * @access public
	 * @return string
	 */
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

	/**
	 * Was the upload a success?
	 * @access public
	 * @return boolean
	 */
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

	/**
	 * Try an upload
	 * @param $field Field name uploaded
	 * @param $fileId Deprecated
	 * @param $newName New name for the file		
	 * @todo remove $fieldId	
	 * @access public
	 * @return boolean
	 */
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

	/**
	 * Upload a file
	 * 
	 * @param $field Field name uploaded
	 * @param $folder folder name to save
	 * @param $newName New name for the file
	 * @access public
	 */
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