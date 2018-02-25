<?php
class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    	$this->table = 'cb_users';
    }

    /**
     * This function is used authenticate user at login
     */
    public function auth_user()
    {
        $email    = $this->input->post('email');
        $password = $this->input->post('password');

        $this->db->where(" is_deleted='0' AND (user_name='$email') ");
        $result = $this->db->get('cb_users')->result();

        if (!empty($result)) {
            if (password_verify($password, $result[0]->password)) {

                if ($result[0]->user_status != 1) {
                    return 'not_varified';
                }
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * This function is used to delete user
     * @param: $id - id of user table
     */
    public function delete($id = '')
    {
        $this->db->where('users_id', $id);
        $this->db->delete('users');
    }

    /**
     * This function is used to load view of reset password and varify user too
     */
    public function mail_varify()
    {
        $ucode = $this->input->get('code');
        $this->db->select('email as e_mail');
        $this->db->from('users');
        $this->db->where('var_key', $ucode);
        $query  = $this->db->get();
        $result = $query->row();
        if (!empty($result->e_mail)) {
            return $result->e_mail;
        } else {
            return false;
        }
    }

    /**
     * This function is used Reset password
     */
    public function ResetPpassword()
    {
        $email = $this->input->post('email');
        if ($this->input->post('password_confirmation') == $this->input->post('password')) {
            $npass            = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $data['password'] = $npass;
            $data['var_key']  = '';
            return $this->db->update('users', $data, "email = '$email'");
        }
    }

    /**
     * This function is used to select data form table
     */
    public function get_data_by($tableName = '', $value = '', $colum = '', $condition = '')
    {
        if ((!empty($value)) && (!empty($colum))) {
            $this->db->where($colum, $value);
        }
        $this->db->select('*');
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to check user is alredy exist or not
     */
    public function check_exists($table = '', $colom = '', $colomValue = '')
    {
        $this->db->where($colom, $colomValue);
        $res = $this->db->get($table)->row();
        if (!empty($res)) {return false;} else {return true;}
    }

    /**
     * This function is used to get users detail
     */
    public function get_users( $userID = null, $permission = 0 )
    {
        // $this->db->where('is_deleted', '0');
        if ( isset($userID) && $userID != null ) {
            $this->db->where('cb_users.user_id', $userID);
        } else {
            $this->db->where('cb_users.user_id !=', '1');
        }

        $this->db->select('cb_users.user_id AS u_id, cb_users.user_type, cb_users.created_on, cb_users.user_status, cb_user_details.*, cb_user_details_meta.*, cb_subscriptions.*');

        /*Apply permissions*/
        if( $permission != 0){
            $this->db->where( 'cb_users.user_type !=', $permission );
        }

        $this->db->join('cb_user_details', 'cb_users.user_id = cb_user_details.user_id', 'left');
        $this->db->join('cb_user_details_meta', 'cb_users.user_id = cb_user_details_meta.user_id', 'left');
        $this->db->join('cb_subscriptions', 'cb_users.user_id = cb_subscriptions.user_id', 'left');
        // $this->db->where('cb_subscriptions.subscription_status !=', 0);

        $this->db->order_by("u_id", "desc");
        $this->db->group_by("cb_users.user_id");
        $result = $this->db->get('cb_users')->result();
        // echo $this->db->last_query();
        return $result;
    }

    /**
     * This function is used to get users
     */
    public function get_user_details($fields = null, $where = array(), $offset = null, $limit = null, $usermediaFlg = false)
    {
        if ($fields) {

            if($usermediaFlg){
                $fields .= ", CONCAT('".USER_IMAGE_URL."',cbu.user_id,'/') AS photo_dir_url";
            }

            $this->db->select($fields);
        }

        $this->db->join('cb_user_details cbud', 'cbud.user_id = cbu.user_id', 'left');
        return $this->db->get_where('cb_users cbu', $where, $limit, $offset)->result();
    }

	/**
	 * This function is used to get plans
	 */
	public function get_user_meta($fields = null, $where = array(), $offset = null, $limit = null) {
		
		if($fields){

		} else {
			$this->db->select("cb_users.user_id, cb_users.user_name, cb_user_details.*, cb_plans.*, CONCAT('{', GROUP_CONCAT(CONCAT( '\"', cb_setting.setting_id,'\" : {\"', cb_setting.setting_name, '\":\"', cb_plan_meta.feature_value, '\"}')), '}') AS fetures " );
		}
		$this->db->join('cb_user_details', 'cb_user_details.user_id = '.$this->table.'.user_id', 'left');
		$this->db->join('cb_subscriptions', 'cb_subscriptions.user_id = '.$this->table.'.user_id AND cb_subscriptions.subscription_status = 1', 'left');
		$this->db->join('cb_plans', 'cb_plans.plan_id = cb_subscriptions.plan_id', 'left');
		$this->db->join('cb_plan_meta', 'cb_plan_meta.plan_id = cb_plans.plan_id', 'left');
		$this->db->join('cb_setting', 'cb_setting.setting_id = cb_plan_meta.feature_type', 'left');
		
		// User specific details
		if(isset($where['user_id'])){
			$this->db->where("cb_users.user_id", $where['user_id']);
			$this->db->group_by("cb_users.user_id");
			unset($where['user_id']);
		}
		return $this->db->get_where($this->table, $where, $limit, $offset)->result_array();
    }

    /**
     * This function is used to get email template
     */
    public function get_template($code)
    {
        $this->db->where('code', $code);
        return $this->db->get('templates')->row();
    }

    /**
     * User registration functionality
     */
    public function userRegistration($data)
    {

        $userData['user_name'] = $data['user_name'];
        $userData['password']  = password_hash($data['password'], PASSWORD_DEFAULT);
        $userData['user_type'] = $data['user_type'];
        unset($data['user_name']);
        unset($data['password']);
        unset($data['user_type']);

        $data['user_id'] = $this->insertRow('cb_users', $userData);

        if ($data['user_id'] > 0) {
            return $this->insertRow('cb_user_details', $data);
        } else {
            throw new Exception("User registration failed", 10);
        }
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
    public function insertRow($table, $data)
    {

        $this->db->insert($table, $data);
        return $this->db->insert_id();
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
     * This function is used to Update record in table
     */
    public function replaceRow($table, $data)
    {

        $this->db->replace($table, $data);
        return true;
    }

    public function get_plan_data($id)
    {
        //$this->db->select('cb_plans.*, cb_plan_meta.*')
        $query = "SELECT `cb_plan_meta`.*, `cb_plans`.*, `cb_setting`.* FROM `cb_plan_meta`
				LEFT JOIN `cb_plans` ON `cb_plans`.`plan_id` = `cb_plan_meta`.`plan_id`
				LEFT JOIN `cb_setting` ON `cb_setting`.`setting_id` = `cb_plan_meta`.`feature_type`
				WHERE `cb_plans`.`plan_id` = " . $id . " AND (`cb_setting`.`setting_name` = 'Images' OR `cb_setting`.`setting_name` = 'Videos')";
        $res = $this->db->query($query);

        return $res->result_array();
    }

    public function get_triggers($fields = null, $where = array(), $offset = null, $limit = null)
    {

        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get_where('cb_notification_map', $where, $limit, $offset)->result_array();
    }

    /**
     * This function is used authenticate user at login
     */
    public function check_user($username, $password)
    {
        $this->db->select('user_id, user_name, password, user_status');
        $this->db->where(" is_deleted='0' AND (user_name='$username') ");
        $result = $this->db->get('cb_users')->result_array();

        if (!empty($result)) {
            if (password_verify($password, $result[0]['password'])) {

                if ($result[0]['user_status'] != 1) {
                    return 'not_varified';
                }
                return $result[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * This function is used to Insert record in table
     */
    public function insert_token($userId, $accessToken)
    {
        if (!$this->check_exists('keys', 'user_id', $userId)) {

            $userData['key']      = $accessToken;
            $whereData['user_id'] = $userId;

            return $this->updateRow('keys', $userData, $whereData);

        } else {

            $userData['user_id'] = $userId;
            $userData['key']     = $accessToken;

            return $this->insertRow('keys', $userData);
        }
    }

    public function registerUser($post_data, $api_flag = false)
    {
        $user_id = '';

        if ($post_data['user_name'] && ($post_data['password'] == $post_data['confirm_password'])) {

            $user_data['user_name']     = $post_data['user_name'];
            $user_data['password']      = password_hash($post_data['password'], PASSWORD_DEFAULT);
            $user_data['created_on']    = date('y-m-d h:i:a');
            $user_data['user_status']   = 1;            
            $user_id                    = $this->insertRow('cb_users', $user_data);
        } else {
            return false;
        }

        if ($user_id != '') {

            if ($api_flag) {
                $file_names = $post_data['photos'];
            } else {
                $file_names = count($_FILES['photos']['name']) > 0 ? json_encode(cb_fileUpload('photos')) : '';
            }

            $videos_urls = ($post_data['videos'] && !empty($post_data['videos'])) ? json_encode($post_data['videos']) : '';

            $user_details['user_id']         = $user_id;
            $user_details['first_name']      = $post_data['first_name'];
            $user_details['middle_name']     = $post_data['middle_name'];
            $user_details['last_name']       = $post_data['last_name'];
            $user_details['display_name']    = $post_data['first_name'] . ' ' . $post_data['middle_name'] . ' ' . $post_data['last_name'];
            $dob                             = $post_data['dob'];
            $user_details['dob']             = date("Y-m-d", strtotime($dob));
            $user_details['gender']          = $post_data['gender'];
            $user_details['nationality']     = 'India';
            $user_details['state']           = $post_data['state'];
            $user_details['city']            = $post_data['city'];
            $user_details['location']        = '';
            $user_details['address']         = $post_data['address'];
            $user_details['phone']           = $post_data['phone_num'];
            $user_details['mobile']          = $post_data['mobile_num'];
            $user_details['email']           = $post_data['email'];
            $user_details['description']     = $post_data['description'];
            $user_details['talent_category'] = implode(',', $post_data['talent_category']);
            $user_details['tags_interest']   = '';
            $user_details['photos']          = $file_names;
            $user_details['videos']          = $videos_urls;
            $user_details['links']           = '';
            $user_details['experience']      = '';
            $user_details['subscription_id'] = '';
            $user_details['modified_on']     = date('y-m-d h:i:a');
            $user_details['modified_by']     = '';

            $this->insertRow('cb_user_details', $user_details);

            if ($post_data['register_type'] == 'talent' && $post_data['talent_category']) {

                $talentFlag   = false;
                $talentFilter = [4, 5, 6];

                foreach ($post_data['talent_category'] as $key => $value) {
                    $user_meta[] = array(
                        'user_id'    => $user_id,
                        'meta_name'  => 'talent',
                        'meta_value' => $value,
                    );

                    // Checking talents for user details meta table
                    if (in_array($value, $talentFilter)) {
                        $talentFlag = true;
                    }
                }
                $this->db->insert_batch('cb_user_meta', $user_meta);

                if ($talentFlag) {

                    $user_meta_details['user_id']   = $user_id;
                    $user_meta_details['hair']      = $post_data['hair_colour'];
                    $user_meta_details['eye']       = $post_data['eye_colour'];
                    $user_meta_details['colour']    = $post_data['body_colour'];
                    $user_meta_details['body_type'] = $post_data['body_type'];
                    $user_meta_details['weight']    = $post_data['weight'];
                    $user_meta_details['height']    = $post_data['hight'];

                    $this->insertRow('cb_user_details_meta', $user_meta_details);
                }
            }

            return $user_id;

        } else {

            return false;
        }
    }

    /**
     * Get media plan count
     * @param integer userId
     * @return boolean result
     */
    public function get_media_plan_count($userId)
    {
        $media_count = [];

        $this->db->select('(CASE `cbpm`.`feature_type` 
                            WHEN '.FEATURE_TYPE_IMAGE_ID.' THEN "image" 
                            WHEN '.FEATURE_TYPE_VIDEO_ID.' THEN "video" END) AS media_type, 
                        `cbpm`.`feature_value` AS media_count');
        $this->db->join('cb_plan_meta cbpm', 'cbpm.plan_id = cbs.plan_id', 'left');
        $this->db->where('cbs.user_id=', $userId);
        $this->db->where_in('cbpm.feature_type', array(FEATURE_TYPE_IMAGE_ID, FEATURE_TYPE_VIDEO_ID));
        $this->db->group_by('cbpm.feature_type');
        $result = $this->db->get('cb_subscriptions cbs')->result_array();
        
        if(is_array($result) && !empty($result)){

            foreach ($result as $key => $value) {
                
                if($value['media_type'] == 'image'){
                    $media_count['image'] = $value['media_count'];
                }

                if($value['media_type'] == 'video'){
                    $media_count['video'] = $value['media_count'];
                }
            }
        }

        return $media_count;
    }

    /**
    * Update method for user's media
    * @param integer userId
    * @param array image names
    * @param string video url
    * @return boolean result
    */
    public function update_media_info($userId, $imgNames, $userVideoUrl)
    {
        $updateData['photos'] = (!empty($imgNames))     ? implode(',', $imgNames)     : '';
        $updateData['videos'] = (!empty($userVideoUrl)) ? implode(',', $userVideoUrl) : '';
        $whereData['user_id'] = $userId;

        return $this->updateRow('cb_user_details', $updateData, $whereData);
    }

    /**
     * Get media plan count
     * @param integer userId
     * @return boolean result
     */
    public function get_highlight_users($fields = null, $where = array(), $offset = null, $limit = null)
    {
        // Check specified fields list
        if ($fields) {
            $this->db->select($fields);
        }

        $this->db->join('cb_user_details cbud', 'cbud.user_id = cbs.user_id', 'left');
        // $this->db->join('cb_user_details_meta cbum', 'cbum.user_id = cbud.user_id', 'left');
        $this->db->join('cb_plan_meta cbpm', 'cbpm.plan_id = cbs.plan_id', 'left');
        $this->db->where('cbpm.feature_type', HIGHLIGHT_USER_ID);
        $this->db->where('cbpm.feature_value', 1);
        // $result = $this->db->get('cb_subscriptions cbs')->result_array();

        return $this->db->get_where('cb_subscriptions cbs', $where, $limit, $offset)->result_array();
    }
}
