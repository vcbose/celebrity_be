<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for Interests  
*
*/
class Interview_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
        $this->load->model('notifications/Notification_model');
        $this->load->model('users/User_model');
    }

    /**
    * Post method for notifications
    * @param string post params
    * @return json  api response
    */
    public function interviews_post()
	{
		try{
			$a_post  	= $this->post();

	        if(is_array($a_post) && !empty($a_post) ){

	        	$a_post['permission'] 		= 2;
	        	$a_post['where']['map_id'] 	= INTERVIEW_MAP_ID;
	        	if( isset($a_post['from']) && isset($a_post['to']) ){

		        	if( isset($a_post['interview_data']) && !empty($a_post['interview_data']) ){

		        		$a_post['interview_data']['user_id'] = $a_post['to'];
		            	$j_response = $this->Notification_model->manage_notifications( $a_post );
		        	} else {
		        		throw new Exception("Invalid request missing interview details", 1);
		        	}
	        	} else {

	        		throw new Exception("Invalid request missing user information", 1);
	        	}

	        } else {
	            throw new Exception("Post data processing error", 1);
	        }
			
			if($j_response){
				$this->response(json_decode($j_response), parent::HTTP_OK);
			}else{
				throw new Exception( getCBResponse('ER_INTRW_IN'), 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Post method for notifications
    * @param string post params
    * @return json  api response
    */
    public function interviews_put()
	{
		try{
			$a_post  	= $this->get_put_data();

	        if(is_array($a_post) && !empty($a_post) ){

	        	$a_post['permission'] 		= 2;
	        	$a_post['where']['map_id'] 	= INTERVIEW_MAP_ID;
	        	if( isset($a_post['from']) && isset($a_post['to']) ){

		        	if( isset($a_post['interview_data']) && !empty($a_post['interview_data']) ){
		        		if( isset($a_post['interview_data']['interview_id']) && $a_post['interview_data']['interview_id'] != '' ){

		        			$a_post['interview_data']['user_id'] = $a_post['to'];
		            		$j_response = $this->Notification_model->manage_notifications( $a_post );
		            	} else {

		            		throw new Exception("Can't update details, interview id is missing", 1);
		            	}
		        	} else {

		        		throw new Exception("Invalid request missing interview details", 1);
		        	}
	        	} else {

	        		throw new Exception("Invalid post request missing user information", 1);
	        	}

	        } else {
	            throw new Exception("Post data processing error", 1);
	        }
			
			if($j_response){
				$this->response(json_decode($j_response), parent::HTTP_OK);
			}else{
				throw new Exception( getCBResponse('ER_INTRW_IN'), 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for interview
    * @param string get params
    * @return json  api response
    */
    public function interviews_get()
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
			$whereData['map_id'] = INTERVIEW_MAP_ID;

			/*Check user type*/
			if(!empty($whereData) && count($whereData) > 1){

				$user_id = (isset($whereData['user_id']) && $whereData['user_id'] != '')?$whereData['user_id']:0;
				if($user_id == 0){
					throw new Exception("Inavalid user id!", 1);
				}

				$a_user_data = $this->User_model->getRow('cb_users', 'user_type', array('user_id' => $user_id) );
				$user_type = isset($a_user_data[0]['user_type'])?$a_user_data[0]['user_type']:0;
				if($user_type == 0){
					throw new Exception("OOPs! Can't identify user type, please contact administrator", 1);
				} else if ($user_type == 2) {
					
					unset($whereData['user_id']);
					$whereData['triggerd_from'] = $user_id;
				}
			} else {
				throw new Exception("Invalid request!", 1);
			}

			$data 	  = $this->Notification_model->get_notifications($fields, $whereData, $limit, $offset);
			
			if($data){
				$response = array('status'=> true, 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("No interviews scheduled now!", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}	

	/**
    * Get method for interview
    * @param string get params
    * @return json  api response
    */
    public function interview_count_get()
	{
		try{			
			$getParams 	 = $this->get();
						
			// Assign conditions for get notifications
			$whereData 			 = $getParams;
			$whereData['map_id'] = INTERVIEW_MAP_ID;

			/*Check user type*/
			if(!empty($whereData) && count($whereData) > 1){

			} else {
				throw new Exception("Invalid request!", 1);
			}
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