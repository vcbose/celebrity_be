<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for settings  
*
*/
class Subscriptions_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
    	$this->load->model("plans/Plans_model");
    	$this->load->model("plans/Subscriptions_model");
    }

    /**
    * Get method for subscriptions
    * @param string get params
    * @return json  api response
    */
    public function subscriptions_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$getParams 	 = $this->get();
			
			// Verify user id param exists
			if(!isset($getParams['user_id'])){
				throw new Exception("Could not find user_id with the request", 1);
			}

			if(isset($getParams['fields'])){
				$fields = $getParams['fields'];
				unset($getParams['fields']);
			}

			$data 	  = $this->Subscriptions_model->get_user_subscriptions($getParams['user_id'], null, $fields);
			$response = array('status'=>true, 'data' => $data);
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=>false, 'message'=>'Unexpected error occurred');
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Get method for subscriptions
    * @param string get params
    * @return json  api response
    */
    public function features_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$getParams 	 = $this->get();
			
			$data 	  	 = $this->Plans_model->get_features($fields, $getParams, $offset, $limit);
			$response 	 = array('status'=>true, 'data' => $data);
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=>false, 'message'=>'Unexpected error occurred');
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}

?>