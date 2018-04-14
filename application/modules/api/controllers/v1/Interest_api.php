<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for Interests  
*
*/
class Interest_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
        $this->load->model('notifications/Notification_model');
    }

    /**
    * Post method for notifications
    * @param string post params
    * @return json  api response
    */
    public function interests_post()
	{
		try{
			$a_post  	= $this->post();

	        if(is_array($a_post) && !empty($a_post) ){

	        	$a_post['permission'] 		= 2;
	        	$a_post['where']['map_id'] 	= INTEREST_MAP_ID;

	            $j_response = $this->Notification_model->manage_notifications( $a_post );
	        } else {
	            throw new Exception("Post data processing error", 1);
	        }
			
			if($j_response){
				// $response 	= array('status'=>true, 'message'=>'User media upload successfull');
				$this->response(json_decode($j_response), parent::HTTP_OK);
			}else{
				throw new Exception("Notification processing error", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for interest
    * @param string get params
    * @return json  api response
    */
    public function interests_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$getParams 	 = $this->get();
			
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

			// Assign conditions for get notifications
			$whereData 			 = $getParams;
			$whereData['map_id'] = INTEREST_MAP_ID;

			$data 	  = $this->Notification_model->get_notifications($fields, $whereData, $limit, $offset);
			
			if($data){
				$response = array('status'=> true, 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("No activities found on this request!", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Get method for interest
    * @param string get params
    * @return json  api response
    */
    public function notification_count_get()
	{
		try{			
			$getParams 	 = $this->get();
						
			// Assign conditions for get notifications
			$whereData 			 = $getParams;
			// $whereData['map_id'] = INTEREST_MAP_ID;
			$data 	  = $this->Notification_model->get_notification_count($whereData);

			if($data !== false){
				$response = array('status'=> true, 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("No activities found on this request!", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function interests_put()
	{
		echo json_encode(array('status'=>'put method'));
	}
}

?>