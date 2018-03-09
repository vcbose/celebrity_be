<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plans extends MY_Controller {

    function __construct() {

        parent::__construct(); 
        $this->load->model('users/User_model');
        $this->load->model('Plans_model');
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index()
    {
        $plan_id                     = $this->uri->segment('2');

        if(isset($plan_id) && $plan_id != ''){

            $a_plans_features = $this->Plans_model->get_features('cb_plans.*, cb_plan_meta.*, cb_setting.*', array('plan_id' => $plan_id), null, null, true);
            $this->load->admin_template('plans-features', '', array('features' => $a_plans_features));
        } else {

            $a_uf_plans = $this->Plans_model->get_plans('', array('plan_status' => 1));
            $this->load->admin_template('plans', '', array('plans' => $a_uf_plans));
        }
        
        
    }

    public function add_plans()
    {
        $this->load->admin_template('login');
    }

    public function getplandata()
    {
        if ($this->input->post('plan_id') != '') {
            $plan_id    = $this->input->post('plan_id');
            $res        = $this->Plans_model->get_plan_data($plan_id);
            $a_responce = array();
            foreach ($res as $key => $value) {
                $a_responce[$value['setting_name']] = $value['feature_value'];
            }
            echo json_encode($a_responce);
            die();
        }
    }

    public function subscriptions( $user_id = NULL )
    {
        $user_id = $this->uri->segment('2');
        $a_subscription = $this->Plans_model->get_user_subscriptions( $user_id );

        $this->load->admin_template('subscriptions', '', array('user_id' => $user_id,'subscription' => $a_subscription));
    }
}
