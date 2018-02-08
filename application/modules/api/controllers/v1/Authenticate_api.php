<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for authentication  
*
*/
class Authenticate_api extends REST_Controller {

	protected $userDetails;

	public function __construct()
    {
    	parent::__construct();
    	$this->load->model("users/User_model");
    }

    /**
    * post method for users
    * @param json post params
    * @return json  api response
    */
    public function auth_post()
	{
		try{

			die('entering');

			$userId   	 = $this->userDetails['user_id'];
			$userName 	 = $this->userDetails['user_name'];

			// Generate access token
			$accessToken = $this->generate_token($userId, $userName);

			// Insert access token into keys table
			$this->insert_access_token($userId, $accessToken);

			$response 	 = array('status'=>'success', 'message'=>'Authentication successfull', 'access_token'=>$accessToken);
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
						
			$response = array('status'=>'error', 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
     * Check if the user is logged in
     *
     * @override _check_login
     * @access protected
     * @param string $username The user's name
     * @param bool|string $password The user's password
     * @return bool
     */
	protected function _check_login($username = NULL, $password = FALSE)
	{
		if ((empty($username)) || ($password === FALSE)){
            return FALSE;
        }

		$result = $this->User_model->check_user($username, $password);

		echo 'result<pre>';
		print_r($result);
		die;

		if($result){

			$this->userDetails = $result;
			return TRUE;
		}else
			return FALSE;
	}

	/**
     * Generate access token
     *
     * @access protected
     * @param integer $userid The user's id
     * @param string $username The user's name
     * @return string
     */
	protected function generate_token($userId, $username)
	{
		$staticString = 'CEL';
		$currentTIme  = time();
		$accesToken   = md5($staticString.$userId.$username.$currentTIme);

		return $accesToken;
	}


	/**
     * Insert access token
     *
     * @access protected
     * @param integer $userid The user's id
     * @param string $accesToken The user's key
     * @return string
     */
	protected function insert_access_token($userId, $accesToken)
	{
		return $this->User_model->insert_token($userId, $accesToken);
	}
}

?>