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
    * Create method for user's media
    * @param json post params
    * @return json  api response
    */
    public function usermedia_post()
	{
		try{
			$postParams  	= $this->post();

			$imgNames 		= [];
			$userId 		= isset($postParams['user_id'])    ? $postParams['user_id'] 	: 0;
			$userImageData 	= isset($postParams['user_image']) ? $postParams['user_image']  : [];
			$userVideoUrl 	= isset($postParams['user_video']) ? $postParams['user_video']  : [];

			if(!$userId){
				throw new Exception("Provided user id is invalid", 1);
			}

			// Get image count from user plan
			$planMediaCount = $this->User_model->get_media_plan_count($userId);

			if(isset($planMediaCount['image']) || $planMediaCount['image'] > 0){
				
				$existImageCount 	 = $this->get_file_count(USER_IMAGE_DIR.$userId.'/');
				$availableImageCount = $planMediaCount['image'] - $existImageCount;

				if($availableImageCount == 0){
					throw new Exception("Could not upload images, all available images are uploaded", 1);
				}

			}else{
				throw new Exception("User plan not support image upload", 1);
			}

			// Base64 to image convertion
			if(is_array($userImageData) && !empty($userImageData)){

				if($availableImageCount >= count($userImageData)){

					foreach ($userImageData as $key => $imgData) {

						$imgNames[] = $this->base64_to_image($userId, $imgData);
					}
				}else{

					if($availableImageCount == 0){
						$message = "Could not upload images, all available images are uploaded";
					}else{
						$message = "Image count is greater than available images, only ".$availableImageCount." available";
					}

					throw new Exception($message, 1);
				}
			}

			if(isset($planMediaCount['video']) && $planMediaCount['video'] > 0){

				$existingVideoCount  = $this->User_model->getVideoCount($userId);
				$availableVideoCount = $planMediaCount['video'] - $existingVideoCount;

				// Check video url count
				if($availableVideoCount < count($userVideoUrl)){

					if($availableVideoCount == 0){
						$message = "Could not upload videos, all available videos are uploaded";
					}else{
						$message = "Video count is greater than available videos, only ".$availableVideoCount." available";
					}

					throw new Exception($message, 1);
				}
			}else{
				throw new Exception("User plan not support video upload", 1);
			}

			// Updating media information into user table
			$result = $this->User_model->update_media_info($userId, $imgNames, $userVideoUrl);

			if($result){
				$response 	= array('status'=> true, 'message'=>'User media upload successfull');
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("User media upload failed", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Update method for user's media
    * @param json post params
    * @return json  api response
    */
    public function usermedia_put()
	{
		try{
			$resImage 		= true;
			$resVideo 		= true;
			$putParams  	= $this->get_put_data();
			
			$userId 		= isset($putParams['user_id'])    	   ? $putParams['user_id'] 	       : 0;
			$oldImageName 	= isset($putParams['old_image_name'])  ? $putParams['old_image_name']  : null;
			$newUserImage 	= isset($putParams['new_user_image'])  ? $putParams['new_user_image']  : null;
			$oldVideoIndx 	= isset($putParams['old_video_indx'])  ? $putParams['old_video_indx']  : null;
			$newUserVideo 	= isset($putParams['new_user_video'])  ? $putParams['new_user_video']  : null;
			
			if($userId > 0){

				if($oldImageName && $newUserImage){

					$resImage = $this->base64_to_image($userId, $newUserImage, $oldImageName);
				}

				if(($oldVideoIndx >= 0) && $newUserVideo){

					$resVideo = $this->User_model->updateVideoUrl($userId, $newUserVideo, $oldVideoIndx);
				}

				if($resImage && $resVideo){
					$response 	= array('status'=>true, 'message'=>'User media update successfull');
					$this->response($response, parent::HTTP_OK);
				}else{
					throw new Exception("User media update failed", 1);
				}
				
			}else{
				throw new Exception("Provided user id is invalid", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Read method for user's media
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
				$response = array('status'=>true, 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("Error on get user media", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Convert base64 data to image file
    * @param string base64 image data
    * @param string output file name
    * @return boolean result
    */
	private function base64_to_image($userId, $base64String, $outputFileName = null)
	{
		$outputDir 		= USER_IMAGE_DIR.$userId.'/';

		// Check base64 string contain comma separation
		if((strpos($base64String, ',') != -1) || (strpos($base64String, ';') != -1)){
			$base64String   = preg_replace('#^data:image/\w+;base64,#i', '', $base64String);
		}

		// Create output file name
		if(!$outputFileName){
			$outputFileName = $this->create_image_name($userId, $outputDir, $base64String);
		}

		// Decode base64 data to image data
		$imageData = base64_decode($base64String);

		// Create file with base64 data
		$result = file_put_contents($outputDir.$outputFileName, $imageData);

		// Check file creation status
		if($result) return $outputFileName;	
		else return false;
	}

	/**
    * Get image name
    * @param string base64 image data
    * @return string extension
    */
	private function create_image_name($userId, $outputDir, $base64String)
	{
		$fileCount      = 0;
		$imgExt 	 	= $this->get_image_extension($base64String);

		// Get file count of the directory
		if(file_exists($outputDir)){
			$fileCount     	= $this->get_file_count($outputDir);  

		}else{
			mkdir($outputDir, 0777, true);
		}

		$outputFileName 	= $userId.'_'. ++$fileCount .'.'.$imgExt;

		return $outputFileName;
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
		try{

			$count = 0;
			foreach ( glob( $dirPath.$fileType ) as $file) {
				$count++;
			}

			return $count;

		}catch(Exception $ex){
			
			return 0;			
		}
	}

	/**
    * Get put requested data
    * @return array put data
    */
	private function get_put_data()
	{
		return json_decode(file_get_contents("php://input"), true);
	}
}

?>