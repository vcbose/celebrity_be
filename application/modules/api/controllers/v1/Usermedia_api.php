<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for settings  
*
*/
class Usermedia_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
    	$this->load->model("users/User_model");
    }

    /**
    * post method for user's media
    * @param json post params
    * @return json  api response
    */
    public function usermedia_post()
	{
		try{
			$postParams  	= $this->post();

			$imgNames 		= [];
			$userId 		= $postParams['user_id'];
			$userImageData 	= isset($postParams['user_image']) ? $postParams['user_image'] : [];
			$userVideoUrl 	= isset($postParams['user_video']) ? $postParams['user_video'] : [];			

			// Get image count from user plan
			$media_count = $this->User_model->get_media_plan_count($userId);

			// Base64 to image convertion
			if((is_array($userImageData) && !empty($userImageData)) 
				&& (isset($media_count['image']) && $media_count['image'] > 0)){

				if($media_count['image'] >= count($userImageData)){

					foreach ($userImageData as $key => $imgData) {
						
						$imgNames[] = $this->base64_to_image($userId, $imgData);
					}
				}else{
					throw new Exception("Image count is greater than plan count", 1);
				}
			}else{
				throw new Exception("Image data error", 1);
			}

			// Check video url count
			if($media_count['video'] < count($userVideoUrl)){
				throw new Exception("Video url count is greater than plan count", 1);
			}

			// Updating media information into user table
			$result = $this->User_model->update_media_info($userId, $imgNames, $userVideoUrl);

			if($result){
				$response 	= array('status'=>'success', 'message'=>'User media upload successfull');
				$this->response($response, parent::HTTP_OK);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>'error', 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for user's media
    * @param string get params
    * @return json  api response
    */
    public function usermedia_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$getParams 	 = $this->get();

			// Assign field values
			$fields 	 = 'cbu.user_id, user_name, photos, videos';

			if(isset($getParams['fields'])){
				$fields = $getParams['fields'];
				unset($getParams['fields']);
			}

			if(isset($getParams['offset'])){
				$offset = $getParams['offset'];
				unset($getParams['offset']);
			}
			if(isset($getParams['limit'])){
				$limit  = $getParams['limit'];
				unset($getParams['limit']);
			}

			$data 	  = $this->User_model->get_user_details($fields, $getParams, $limit, $offset, true);

			if($data){
				$response = array('status'=>'success', 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("Error on get user media", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>'error', 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Convert base64 data to image file
    * @param string base64 image data
    * @param string output file name
    * @return boolean result
    */
	private function base64_to_image($userId, $base64String)
	{
		$imgExt 	 	= $this->get_image_extension($base64String);

		$outputDir 		= USER_IMAGE_DIR.$userId.'/';
		// Get file count of the directory
		$fileCount 		= $this->get_file_count($outputDir);
		$outputFile 	= $userId.'_'. ++$fileCount .'.'.$imgExt;

	    // Check base64 string contain comma separation
	    if((strpos($base64String, ',') != -1) || (strpos($base64String, ';') != -1)){
	    	$imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));	
	    }else{
	    	$imageData = base64_decode($base64String);
	    }

	    // Check and create directory if not exists
	    if(!file_exists($outputDir)){
	    	mkdir($outputDir, 0777, true);
	    }

	    // Create file with base64 data
	    file_put_contents($outputDir.$outputFile, $imageData);

	    return $outputFile; 
	}

	/**
    * Get image extension
    * @param string base64 image data
    * @return string extension
    */
	private function get_image_extension($base64String)
	{
		// Default extensions for different mime types
		$mimeFilter = ['jpeg'=>'jpg', 'jpg'=>'jpg', 'png'=>'png'];

		$imgdata 	= base64_decode($base64String);
		$f 			= finfo_open();

		// Get mime type from the image data
		$mimeType 	= finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
		$extArr     = explode('/', $mimeType);

		return $mimeFilter[ $extArr[1] ];
	}

	/**
    * Get file count of given directory
    * @param string base64 image data
    * @return string extension
    */
	private function get_file_count($dirPath, $fileType = '*')
	{
		$iterator = new GlobIterator($dirPath.$fileType);

		return $iterator->count();
	}
}

?>