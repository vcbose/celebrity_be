<?php
class Plans_model extends CI_Model {

	private $table  = '';

	function __construct(){            
		
	  	parent::__construct();
	  	$this->table = 'cb_plans';
	}
	
    /**
     * This function is used to get users
     */
    public function getRow($table, $fields = null, $where = array(), $offset = null, $limit = null)
    {

        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get_where($table, $where, $limit, $offset)->result_array();
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
    public function updateRow($table, $data, $a_where)
    {
        // $this->db->where($col,$colVal);
        $this->db->update($table, $data, $a_where);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

	/**
	 * This function is used to get plans
	 */
	public function get_plans($fields = null, $where = array(), $offset = null, $limit = null) {
		
		if($fields){
			$this->db->select($fields);
		}
		return $this->db->get_where($this->table, $where, $limit, $offset)->result();
    }

    public function get_plan_data( $id )
    {
        $this->db->select('cb_plans.*, cb_plan_meta.*, cb_setting.*');
        $this->db->join('cb_plan_meta', 'cb_plan_meta.plan_id = cb_plans.plan_id', 'left');
        $this->db->join('cb_setting', 'cb_setting.setting_id = cb_plan_meta.feature_type AND cb_setting.setting_status', 'left');

        $plan_where = "`cb_plans`.`plan_id` = " . $id . " AND (`cb_setting`.`setting_name` = 'Images' OR `cb_setting`.`setting_name` = 'Videos')";
        $this->db->where($plan_where, null, FALSE);

        return $this->db->get($this->table)->result_array();
    }

  	public function add_user_plan($user_id, $plan, $current_subscription = 0)
  	{
  		$plan_data['user_id']           = $user_id;
        $plan_data['plan_id']           = $plan;

        $a_where = array("plan_id" => $plan_data['plan_id']);
        $a_all_plans                    = $this->Plans_model->get_plans('', $a_where);

        $duration = $a_all_plans[0]->plan_duration;
        $duration_term = $a_all_plans[0]->plan_duration_in;

        $date = date('Y-m-d');
        $ends_on = date('Y-m-d', strtotime($date. " + {$duration} {$duration_term}"));
        $time = date('H:i:s');

        /*Update previouse subscription*/
        if($current_subscription != 0){
            $check_exits = $this->updateRow( 'cb_subscriptions', array('subscription_status' => 0), array('user_id' => $user_id, 'subscription_id' => $current_subscription) );
        }

        $plan_data['purchased_on']        = $date.' '.$time;
        $plan_data['started_on']          = $date.' '.$time;
        $plan_data['ends_on']             = $ends_on.' '.$time;
        $plan_data['subscription_status'] = 1;

        $result = $this->insertRow('cb_subscriptions', $plan_data);

        return $result;
  	}

  	public function get_features($fields = null, $where = array(), $offset = null, $limit = null, $filter = true)
  	{
  		$a_fetuers = array();
  		$a_fetuers_list = array();

        if($fields == null){
      		$this->db->select('cb_plan_meta.*, cb_setting.*');
        } else {
            $this->db->select( $fields );
        }

  		$this->db->join('cb_plan_meta', 'cb_plan_meta.plan_id = cb_plans.plan_id', 'left');
  		$this->db->join('cb_setting', 'cb_setting.setting_id = cb_plan_meta.feature_type AND cb_setting.setting_status', 'right');

  		if( isset($where['user_id']) && $where['user_id'] != 0){

  			$this->db->join('cb_subscriptions', 'cb_subscriptions.plan_id = cb_plans.plan_id AND cb_subscriptions.subscription_status = 1', 'left');
  			$this->db->join('cb_users', 'cb_users.user_id = cb_subscriptions.user_id', 'left');
  			$this->db->where('cb_users.user_id =', $where['user_id']);
  			$this->db->where('cb_users.user_id =', $where['user_id']);
  			unset($where['user_id']);
  		}

        if (isset($where['plan_id']) && $where['plan_id'] != 0){
            $this->db->where($this->table.'.plan_id =', $where['plan_id']);
        }
  		
  		$a_fetuers = $this->db->get($this->table, $limit, $offset)->result_array();

        if($filter){

      		foreach ($a_fetuers as $key => $features) {
      		    $a_fetuers_list[$features['feature_type']] = array($features['setting_name'] => $features['feature_value']);
      		}

            return $a_fetuers_list;
        } else {

            return $a_fetuers;
        }
  	}
}