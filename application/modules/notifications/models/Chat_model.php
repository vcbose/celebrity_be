<?php
class Chat_model extends CI_Model {

	private $table  = '';
	public $user_id;
	
	function __construct(){
		
	  	parent::__construct();
	  	$this->table = 'cb_user_chats';
	  	$this->user_id = isset($this->session->get_userdata()['user_details'][0]->user_id)?$this->session->get_userdata()['user_details'][0]->user_id:'1';
	}

  	/**
   	  * This function is used to get users
   	  */
	public function getRow($table, $fields = null, $where = array(), $offset = null, $limit = null) {
		
		if($fields){
			$this->db->select($fields);
		}
		return $this->db->order_by('chat_id', 'ASC')->get_where($table, $where, $limit, $offset)->result_array();
    }

	/**
      * This function is used to Insert record in table  
      */
  	public function insertRow($table, $data){

	  	$this->db->insert($table, $data);
	  	return  $this->db->insert_id();
	}

	/**
      * This function is used to Update record in table  
      */
  	public function updateRow($table, $data, $a_where) {

  		// $this->db->where($col,$colVal);
		$this->db->update($table, $data, $a_where);
		
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
  	}

	/**
      * This function is used to Update record in table  
      */
  	public function replaceRow($table, $data) {

		$this->db->replace($table, $data);
		return true;
  	}

    /**
    * Submit chat
    */
    public function submit_chat( $chat_text = '', $chat_to = null, $chat_from = null){

    	$a_return  = array();
        $chat_from = ($chat_from) ? $chat_from : $this->user_id;

    	if( $chat_from == 1 ){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You are not logged in';
    		return $a_return;
    	}
    	
    	if(!$chat_text){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You haven\'t entered a chat message.';
    		return $a_return;
    	}
    
    	$chat['chat_from'] = $chat_from;
    	$chat['chat_to']   = $chat_to;
    	$chat['chat_text'] = $chat_text;
    	$chat['chat_on']   = date('Y-m-d H:i:s');
    	// The save method returns a MySQLi object
    	$insertID = $this->insertRow($this->table, $chat);
    	
    	if($insertID > 0 ){

    		$a_return['status'] = 1;
    		$a_return['msg'] = 'Successfull';
    		$a_return['insertID'] = $insertID;
    	} else {

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'Failed';
    		$a_return['insertID'] = $insertID;
    	}

    	return $a_return;
    }
    
    public function get_users(){

    }
    
    public function get_chats( $chat_to = null, $chat_from = null ){

    	$chats     = array();
        $chat_from = ($chat_from) ? $chat_from : $this->user_id;

    	if( $chat_from == 1 ){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You are not logged in';
    		return $a_return;
    	}
        
        $a_where = array('chat_from' => $chat_from, 'chat_to' => $chat_to, );     
		$this->db->where( $a_where );
    	$this->db->join('cb_user_details', 'cb_user_chats.chat_from = cb_user_details.user_id', 'left');
    	$result = $this->db->order_by('chat_id', 'ASC')->get('cb_user_chats')->result();

    	return array('chats' => $result);
    }

    /**
     * Get user's chats
     * @param 
     * @return 
     */
    public function get_user_chats($fields = null, $where = array(), $offset = null, $limit = null, $chatUserFlg = false)
    {
        if ($fields) {
            $this->db->select($fields);
        } else {
            // $this->db->select('chat_id, chat_from AS director_id, chat_to AS talent_id, chat_text AS message, chat_on, chat_lock');
        }
        // $chatCondition = ($chatUserFlg) ? 'chat_to' : 'chat_from';
        // $this->db->join('cb_user_details', 'cb_user_chats.chat_from = cb_user_details.user_id', 'left');
        $this->db->order_by('chat_id', 'ASC');
        if( !empty($where) ){
            $s_where = " chat_to = '{$where['chat_to']}' AND chat_from = '{$where['chat_from']}' OR chat_to = '{$where['chat_from']}' AND chat_from = '{$where['chat_to']}' ";
            $this->db->where($s_where);
        }

        return $this->db->get('cb_user_chats', $limit, $offset)->result_array();
    }

    /**
     * Get user's chats
     * @param 
     * @return 
     */
    public function get_chat_users($fields = null, $where = array(), $offset = null, $limit = null)
    {
        if ($fields) {
            
            $media_name = " CONCAT('".site_url()."assets/uploads/',cbd.user_id,'/', cbum.media_name ) AS dp_path ";
            $this->db->select('cbd.user_id, cbd.first_name, cbd.middle_name, cbd.last_name, cbd.display_name, cbd.email, cbd.dp, cuc.chat_on, cbum.dp, cbum.moderate_status, '. $media_name);
        } 

        if($where['user_type'] == 2){

            $this->db->join('cb_user_details AS cbd', 'cuc.chat_to = cbd.user_id', 'left');
            $this->db->group_by('chat_to');
        } else {

            $this->db->join('cb_user_details AS cbd', 'cuc.chat_from = cbd.user_id', 'left');
            $this->db->group_by('chat_from');
        }
        $this->db->join('cb_user_medias cbum', 'cbd.user_id = cbum.user_id AND cbum.dp = 1', 'LEFT OUTER');
        $this->db->order_by('chat_id', 'DESC');

        unset($where['user_id']);
        unset($where['user_type']);
        return $this->db->get_where('cb_user_chats AS cuc', $where, $limit, $offset)->result_array();
        // echo $this->db->last_query();die;
    }

    /**
     * Get user's chat count
     * @param 
     * @return 
     */
    public function get_chat_count($where = []){

        $this->db->select('count(DISTINCT chat_from) as count');
        $this->db->group_by('chat_from');
        $result = $this->db->get_where('cb_user_chats', $where)->result_array();

        if(is_array($result) && isset($result[0])){
            return $result[0];
        }else{
            return false;
        }
    }

}