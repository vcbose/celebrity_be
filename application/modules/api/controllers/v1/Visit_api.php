<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for Interests  
*
*/
class Visit_api extends REST_Controller {

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
    public function visits_post()
	{
		try{
			$a_post  	= $this->post();

	        if(is_array($a_post) && !empty($a_post) ){

	        	$a_post['permission'] 		= 2;
	        	$a_post['where']['map_id'] 	= VISITS_MAP_ID;

	            $j_response = $this->Notification_model->manage_notifications( $a_post );
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

			$response = array('status'=>true, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for interview
    * @param string get params
    * @return json  api response
    */
    public function visits_get()
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
			$whereData['map_id'] = VISITS_MAP_ID;

			$data 	  = $this->Notification_model->get_notifications($fields, $whereData, $limit, $offset);
			
			if($data){
				$response = array('status'=>true, 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("Error on get notifications", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}	

	public function visits_put()
	{
		echo json_encode(array('status'=>'put method'));
	}
}

?>