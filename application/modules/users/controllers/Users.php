<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

    public $a_settings;
    public $user_id;

    public function __construct()
    {

        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('plans/Plans_model');
        $this->load->model('plans/Subscriptions_model');
        $this->load->model('notifications/Notification_model');

        $this->user_id  = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
        $a_all_settings = setting_all();
        foreach ($a_all_settings as $s_key => $s_value) {
            $this->a_settings[$s_value->setting_type][$s_value->setting_id] = $s_value->setting_name;
        }
    }

    /**
     * This function is used to load login view page
     * @return Void
     */
    public function login()
    {

        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'profiles', 'refresh');
        }
        // echo $hash = password_hash('@90vcbose', PASSWORD_BCRYPT);
        $this->load->admin_template('login');
        // $this->auth_user();
    }

    /**
     * This function is used for user authentication ( Working in login process )
     * @return Void
     */
    public function auth_user($page = '')
    {

        $return = $this->User_model->auth_user();
        if (empty($return)) {

            $this->session->set_flashdata('messagePr', 'Invalid details');
            redirect(base_url() . 'cb-login', 'refresh');
        } else {

            if ($return == 'not_varified') {
                $this->session->set_flashdata('messagePr', 'This accout is not varified. Please contact to your admin..');
                redirect(base_url() . 'login', 'refresh');
            } else {

                $this->session->set_userdata('user_details', $return);
            }

            redirect(base_url() . 'profiles', 'refresh');
        }
    }

    /**
     * This function is used to registr user
     * @return Void
     */
    public function registration()
    {
        is_login();
        //Check if admin allow to registration for user
        if (CheckPermission()) {
            if ($this->input->post()) {

                if ($this->add_user()) {
                    // die();
                    $this->session->set_flashdata('messagePr', 'Successfully Registered..');
                }
                redirect(base_url() . '/profiles', 'refresh');
            } else {

                $a_all_plans = $this->Plans_model->get_plans();
                foreach ($a_all_plans as $p_key => $p_value) {
                    $a_plans[$p_value->plan_id] = $p_value->plan_name;
                }
                $this->load->admin_template('register', array('a_settings' => $this->a_settings, 'plans' => $a_plans));
            }
        } else {
            $this->session->set_flashdata('messagePr', 'Registration Not allowed..');
            redirect(base_url() . 'profiles', 'refresh');
        }
    }

    public function add_user()
    {
        $user_id = '';
        if ($this->input->post('register_submit')) {

            $postParams = $this->input->post();

            $user_id = $this->User_model->registerUser($postParams);

            if ($user_id > 0) {

                if ($this->Plans_model->add_user_plan($user_id, $postParams['plan'])) {

                    return true;
                }
            } else {
                $this->session->set_flashdata('messagePr', 'Registration failed !');
            }

        }
    }

    public function getplandata()
    {
        if ($this->input->post('plan_id') != '') {
            $plan_id    = $this->input->post('plan_id');
            $res        = $this->User_model->get_plan_data($plan_id);
            $a_responce = array();
            foreach ($res as $key => $value) {
                $a_responce[$value['setting_name']] = $value['feature_value'];
            }
            echo json_encode($a_responce);
            die;
        }
    }

    public function checkUserName()
    {
        if ($this->input->post('user_name')) {
            $check = $this->User_model->check_exists('cb_users', 'user_name', $this->input->post('username'));
            if (!$check) {
                echo json_encode("Aready Taken");
            } else {
                echo "true";
            }
        }
    }

    /**
     * This function is used to add and update users
     * @return Void
     */
    public function add_edit($id = '')
    {
        $data        = $this->input->post();
        $profile_pic = 'user.png';
        if ($this->input->post('users_id')) {
            $id = $this->input->post('users_id');
        }
        if (isset($this->session->userdata('user_details')[0]->users_id)) {
            if ($this->input->post('users_id') == $this->session->userdata('user_details')[0]->users_id) {
                $redirect = 'profile';
            } else {
                $redirect = 'userTable';
            }
        } else {
            $redirect = 'login';
        }
        if ($this->input->post('fileOld')) {
            $newname     = $this->input->post('fileOld');
            $profile_pic = $newname;
        } else {
            $data[$name] = '';
            $profile_pic = 'user.png';
        }
        foreach ($_FILES as $name => $fileInfo) {
            if (!empty($_FILES[$name]['name'])) {
                $newname     = $this->upload();
                $data[$name] = $newname;
                $profile_pic = $newname;
            } else {
                if ($this->input->post('fileOld')) {
                    $newname     = $this->input->post('fileOld');
                    $data[$name] = $newname;
                    $profile_pic = $newname;
                } else {
                    $data[$name] = '';
                    $profile_pic = 'user.png';
                }
            }
        }
        if ($id != '') {
            $data = $this->input->post();
            if ($this->input->post('status') != '') {
                $data['status'] = $this->input->post('status');
            }
            if ($this->input->post('users_id') == 1) {
                $data['user_type'] = 'admin';
            }
            if ($this->input->post('password') != '') {
                if ($this->input->post('currentpassword') != '') {
                    $old_row = getDataByid('users', $this->input->post('users_id'), 'users_id');
                    if (password_verify($this->input->post('currentpassword'), $old_row->password)) {
                        if ($this->input->post('password') == $this->input->post('confirmPassword')) {
                            $password         = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                            $data['password'] = $password;
                        } else {
                            $this->session->set_flashdata('messagePr', 'Password and confirm password should be same...');
                            redirect(base_url() . 'user/' . $redirect, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('messagePr', 'Enter Valid Current Password...');
                        redirect(base_url() . 'user/' . $redirect, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('messagePr', 'Current password is required');
                    redirect(base_url() . 'user/' . $redirect, 'refresh');
                }
            }
            $id = $this->input->post('users_id');
            unset($data['fileOld']);
            unset($data['currentpassword']);
            unset($data['confirmPassword']);
            unset($data['users_id']);
            unset($data['user_type']);
            if (isset($data['edit'])) {
                unset($data['edit']);
            }
            if ($data['password'] == '') {
                unset($data['password']);
            }
            $data['profile_pic'] = $profile_pic;
            $this->User_model->updateRow('users', 'users_id', $id, $data);
            $this->session->set_flashdata('messagePr', 'Your data updated Successfully..');
            redirect(base_url() . 'user/' . $redirect, 'refresh');
        } else {
            if ($this->input->post('user_type') != 'admin') {
                $data       = $this->input->post();
                $password   = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                $checkValue = $this->User_model->check_exists('users', 'email', $this->input->post('email'));
                if ($checkValue == false) {
                    $this->session->set_flashdata('messagePr', 'This Email Already Registered with us..');
                    redirect(base_url() . 'user/userTable', 'refresh');
                }
                $checkValue1 = $this->User_model->check_exists('users', 'name', $this->input->post('name'));
                if ($checkValue1 == false) {
                    $this->session->set_flashdata('messagePr', 'Username Already Registered with us..');
                    redirect(base_url() . 'user/userTable', 'refresh');
                }
                $data['status'] = 'active';
                if (setting_all('admin_approval') == 1) {
                    $data['status'] = 'deleted';
                }

                if ($this->input->post('status') != '') {
                    $data['status'] = $this->input->post('status');
                }
                //$data['token'] = $this->generate_token();
                $data['user_id']     = $this->user_id;
                $data['password']    = $password;
                $data['profile_pic'] = $profile_pic;
                $data['is_deleted']  = 0;
                if (isset($data['password_confirmation'])) {
                    unset($data['password_confirmation']);
                }
                if (isset($data['call_from'])) {
                    unset($data['call_from']);
                }
                unset($data['submit']);
                $this->User_model->insertRow('users', $data);
                redirect(base_url() . 'user/' . $redirect, 'refresh');
            } else {
                $this->session->set_flashdata('messagePr', 'You Don\'t have this autherity ');
                redirect(base_url() . 'user/registration', 'refresh');
            }
        }

    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function profiles()
    {
        is_login();
        $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : '';
        // $this->session->unset_userdata('user_details');
        $a_users    = $this->User_model->get_users('', $permission);

        $a_uf_plans = $this->Plans_model->get_plans('plan_id, plan_name', array('plan_status' => 1));

        foreach ($a_uf_plans as $key => $value) {
            $a_plans[$value->plan_id] = $value->plan_name;
        }

        $this->load->admin_template('profiles', array('settings' => $this->a_settings, 'userdata' => $a_users, 'plans' => $a_plans));
    }

    public function profile_detail($action, $user_id)
    {
        is_login();
        $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : '';
        // print_r($this->session->get_userdata()['user_details']);

        if (CheckPermission() || $permission == 2) {
            // $this->session->unset_userdata('user_deta ils');
            $action                      = $this->uri->segment('2');
            $user_id                     = $this->uri->segment('3');
            $noty_where['user_id']       = $user_id;
            $noty_where['triggerd_from'] = 3;
            $noty_where['map_id']        = 5;

            $b_edit          = ($action == 'view') ? false : true;

            $a_users         = array();
            $a_subscriptions = array();
            $a_user_features = array();
            $alrdy_notifyed_where  = array();
            $alrdy_notifyed  = array();
            $notifications   = array();

            $a_users = $this->User_model->get_users($user_id);
            
            /*echo "<pre>";
            print_r($a_users);
            echo "</pre>";*/

            $a_post['permission'] = $permission;
            $a_post['from']       = $this->user_id;
            $a_post['to']         = $user_id;

            if ($permission == 1) {

                switch ($a_users[0]->user_type) {

                    case 3:
                        /*Get all subscription of talent*/
                        $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);

                        /*Get all talent features*/
                        $a_feture_where['user_id'] = $user_id;
                        $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);

                        $alrdy_notifyed_where['user_id'] = $user_id;
                        break;
                    case 2:
                        /*Get all notification triggered from director*/
                        $alrdy_notifyed_where['triggerd_from'] = $user_id;
                        break;
                    default:
                        break;
                }

            } else if ($permission == 2) {

                /*Notification for profile visited*/
                if ($a_users[0]->user_type == 3) {

                    $a_post['where']['map_id'] = 1;
                    $this->Notification_model->manage_notifications($a_post);
                    unset($a_post['where']);
                }
                /*Get all subscription of talent*/
                $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);

                /*Get all talent features*/
                $a_feture_where['user_id'] = $user_id;
                $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);

                /*Get all notification triggered from director to a talent*/
                $alrdy_notifyed_where['user_id'] =  $user_id;
                $alrdy_notifyed_where['triggerd_from'] = $this->user_id;
            } else {

                /*Get all subscription*/
                $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);
                /*Get all talent features*/
                $a_feture_where['user_id'] = $user_id;
                $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);
                /*Get all notification of a talent*/
                $alrdy_notifyed_where['user_id'] = $user_id;
            }

            $check_exists = $this->Notification_model->get_notifications('', $alrdy_notifyed_where, '', '', $permission);

            $alrdy_notifyed = array();
            if (!empty($check_exists)) {

                $notifications = $this->Notification_model->render_notifications($check_exists);

                foreach ($check_exists as $key => $value) {
                    $alrdy_notifyed[$value['map_id']] = array('notify_id' => $value['notify_id'], 'triggerd_from' => $value['triggerd_from'], 'notification_on' => $value['notification_on'], 'notification_relation' => $value['notification_relation'], 'notification_status' => $value['notification_status']);
                }
            }

            $this->load->admin_template('profile-detail', array('action' => $b_edit, 'user_id' => $user_id, 'settings' => $this->a_settings, 'userdata' => $a_users[0], 'subscription' => $a_subscriptions, 'features' => $a_user_features, 'triggers' => $a_post, 'notifyed' => $alrdy_notifyed, 'notifications' => $notifications));
        } else {

            $this->session->set_flashdata('messagePr', 'You Don\'t have this autherity ');
            redirect(base_url() . 'profiles', 'refresh');
        }
    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function logout()
    {
        is_login();
        $this->session->unset_userdata('user_details');
        redirect(base_url() . 'cb-login', 'refresh');
    }
}
