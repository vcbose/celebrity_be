<?php
class Subscriptions_model extends CI_Model {

	private $table  = '';
	
	function __construct(){
		
	  	parent::__construct();
	  	$this->table = 'cb_subscriptions';
	}
	
	/**
	 * This function is used to get subscriptions
	 */
	public function get_subscriptions($fields = null, $where = array(), $offset = null, $limit = null) {
		
		if($fields){
			$this->db->select($fields);
		}
		return $this->db->get_where($this->table, $where, $limit, $offset)->result();
    }

  	public function get_user_subscriptions( $userID, $status = null )
  	{
  		$this->db->where('is_deleted', '0'); 
  		                 
		if(isset($userID) && $userID !='') {
			$this->db->where('cb_subscriptions.user_id', $userID); 
		} 

		$this->db->join('cb_users', 'cb_users.user_id = cb_subscriptions.user_id', 'left');
		$this->db->join('cb_user_details', 'cb_user_details.user_id = cb_subscriptions.user_id', 'left');
		$this->db->join('cb_plans', 'cb_plans.plan_id = cb_subscriptions.plan_id', 'left');

		if(isset($status) && $status != null) {
			$this->db->where('cb_subscriptions.subscription_status !=', $status);
		}
		return $this->db->get($this->table)->result();
  	}
  	
  	public function check_user_balance($user_id, $trigger, $action, $type)
  	{
  		
  	}
}