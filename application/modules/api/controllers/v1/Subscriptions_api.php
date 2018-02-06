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
    	$this->load->model("plans/Subscriptions_model");
    	// $this->load->model("setting/Setting_model");
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

			$data 	  = $this->Subscriptions_model->get_subscriptions($fields, $getParams, $offset, $limit);
			$response = array('status'=>'success', 'data' => $data);
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=>'error', 'message'=>'Unexpected error occurred');
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}

?>