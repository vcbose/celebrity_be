<?php
class Notification_model extends CI_Model
{

    private $table = '';

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cb_plans';
        $this->load->model('plans/Plans_model');
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

    public function get_triggers($fields = null, $where = array(), $offset = null, $limit = null)
    {
        if ($fields) {
            $this->db->select($fields);
        }
        if (isset($where['map_id'])) {

            $where['id'] = $where['map_id'];
            unset($where['map_id']);
        }
        return $this->db->get_where('cb_notification_map', $where, $limit, $offset)->result_array();
    }
    /*
    Manage visits, intersts and interview notifaction from director
     */
    public function manage_notifications( $a_post = array() )
    {
        if (!empty($a_post) && isset($a_post['where']) && $a_post['permission'] == 2) {

            $a_return = array();

            /*Check requested trigger is in map table*/
            $a_triggers = $this->get_triggers('', $a_post['where']);

            /*This want to optimize in UI integration time*/
            $a_sub_where['user_id'] = $a_post['to'];
            $a_user_features        = $this->Plans_model->get_features('', $a_sub_where);

            if (!empty($a_triggers)) {

                /*Reset map_id from tbl value*/
                $map_id = $a_triggers[0]['id'];

                /*Check trigger notification is exists for user*/
                $a_noty_trigger['user_id']       = $a_post['to'];
                $a_noty_trigger['triggerd_from'] = $a_post['from'];
                $a_noty_trigger['map_id']        = $map_id;
                $check_exists                    = $this->get_notifications('', $a_noty_trigger);

                /*Exists then update else insert*/
                if (!empty($check_exists)) {

                    $action                   = 'visits';
                    $a_where['user_id']       = $a_post['to'];
                    $a_where['triggerd_from'] = $a_post['from'];
                    $a_where['map_id']        = $map_id;
                    $data['notification_on']  = date('Y-m-d H:i:s');
                    $notification_note        = array();

                    if (isset($check_exists[0]['notification_note']) && $check_exists[0]['notification_note'] != '') {
                        $notification_note = json_decode($check_exists[0]['notification_note'], true);
                    }

                    if ($a_post['where']['map_id'] == VISITS_MAP_ID && !empty($notification_note)) {

                        $action                    = 'visits';
                        $count                     = $notification_note['visits'] + 1;
                        $data['notification_note'] = json_encode(array('visits' => $count));
                    } elseif ($a_post['where']['map_id'] == INTERVIEW_MAP_ID) {

                        $action = 'interview';
                        if (!empty($a_user_features)) {

                            if (isset($a_user_features[INTERVIEW_ID])) {

                                /*Check interview status of plan*/
                                $interview                   = $a_user_features[INTERVIEW_ID]['Interview'];
                                $data['notification_status'] = ($interview == 0) ? 0 : 1;

                                if ((isset($a_post['form_data']) && !empty($a_post['form_data']))
                                    || (isset($a_post['interview_data']) && !empty($a_post['interview_data']))) {
                                    $a_interview_data = array();

                                    if (isset($a_post['form_data'])) {
                                        foreach ($a_post['form_data'] as $key => $fields) {
                                            $a_interview_data[$fields['name']] = $fields['value'];
                                        }

                                    } else if (isset($a_post['interview_data'])) {
                                        $a_interview_data = $a_post['interview_data'];
                                    }

                                    // Assign userid from post data
                                    $a_interview_data['user_id'] = $a_post['from'];

                                    // Schedule interview process
                                    $intrw_id = $this->schedule_interview($a_interview_data);

                                    if ($intrw_id != 0) {

                                        $b_status                      = true;
                                        $data['notification_relation'] = $intrw_id;
                                        $message                       = getCBResponse('SUC_INTRW_IN');
                                    } else {

                                        $b_status                    = false;
                                        $data['notification_status'] = 0;
                                        $message                     = getCBResponse('ER_INTRW_IN');
                                    }

                                }
                            }
                        }
                    }

                    $b_status = $this->updateRow('cb_user_notifications', $data, $a_where);

                    if ($b_status) {
                        $a_return = array('status' => true, 'msg' => 'You`ve already requested to follow user.', 'action' => $action);
                    }
                    return json_encode($a_return);

                } else {

                    $data['user_id']         = $a_post['to'];
                    $data['triggerd_from']   = $a_post['from'];
                    $data['map_id']          = $map_id;
                    $data['notification_on'] = date('Y-m-d H:i:s');
                    $message                 = 'Oops ! Something went wrong, please try after sometimes.';
                    $b_status                = false;

                    if ($a_post['where']['map_id'] == VISITS_MAP_ID) {

                        $action                    = 'visits';
                        $data['notification_note'] = json_encode(array('visits' => 1));
                        $message                   = getCBResponse('SUC_INTR_IN');
                        $b_status                  = true;
                    } else if ($a_post['where']['map_id'] == INTEREST_MAP_ID) {

                        $action = 'interest';
                        if (!empty($a_user_features)) {

                            if (isset($a_user_features[INTERSTS_ID])) {
                                /*Check interest status of plan*/
                                $interests                   = $a_user_features[INTERSTS_ID]['Interests'];
                                $data['notification_status'] = ($interests == 0) ? 0 : 1;
                                $message                     = getCBResponse('SUC_INTR_IN');
                                $b_status                    = true;
                            }
                        }
                    } else if ($a_post['where']['map_id'] == INTERVIEW_MAP_ID) {

                        $action   = 'interview';
                        $b_status = false;

                        if (!empty($a_user_features)) {

                            if (isset($a_user_features[INTERVIEW_ID])) {

                                /*Check interview status of plan*/
                                $interview                   = $a_user_features[INTERVIEW_ID]['Interview'];
                                $data['notification_status'] = ($interview == 0) ? 0 : 1;

                                if (isset($a_post['form_data']) && !empty($a_post['form_data'])
                                    || (isset($a_post['interview_data']) && !empty($a_post['interview_data']))) {

                                    $a_interview_data = array();

                                    if (isset($a_post['form_data'])) {
                                        foreach ($a_post['form_data'] as $key => $fields) {
                                            $a_interview_data[$fields['name']] = $fields['value'];
                                        }

                                    } else if (isset($a_post['interview_data'])) {
                                        $a_interview_data = $a_post['interview_data'];
                                    }

                                    // Assign userid from post data
                                    $a_interview_data['user_id'] = $a_post['from'];

                                    // Schedule interview process
                                    $intrw_id = $this->schedule_interview($a_interview_data);

                                    if ($intrw_id != 0) {

                                        $b_status                      = true;
                                        $data['notification_relation'] = $intrw_id;
                                        $message                       = getCBResponse('SUC_INTRW_IN');
                                    } else {

                                        $b_status                    = false;
                                        $data['notification_status'] = 0;
                                        $message                     = getCBResponse('ER_INTRW_IN');
                                    }
                                }
                            }
                        }
                    }

                    /*Insert to notification table if intial requirments completed*/
                    if ($b_status) {
                        $this->insertRow('cb_user_notifications', $data);
                    }

                    $a_return = array('status' => $b_status, 'msg' => $message, 'action' => $action);
                    return json_encode($a_return);
                }
            } else {

                $a_return = array('status' => false, 'msg' => getCBResponse('ER_REQUEST'));
                return json_encode($a_return);
            }
        } else {

            $a_return = array('status' => false, 'msg' => getCBResponse('ER_REQUEST'));
            return json_encode($a_return);
        }
    }

    /*
    Get all user notifications
    Params : @user_id of talent, @triggerd_from is director is @map_id type of notification
     */
    public function get_notifications($fields = null, $a_where = array(), $limit = null, $offset = null, $permission = 0)
    {
        if ($fields == null) {

            $fields =  "un.user_id, un.triggerd_from, un.notify_id, un.map_id, un.notification_on, un.notification_note, un.notification_relation, un.notification_status, 
            np.trigger, np.action, np.notification_type";    
        }

        $this->db->join('cb_notification_map AS np', 'np.id = un.map_id', 'left');

        if( !empty($a_where) ){

            if (isset($a_where['map_id'])) {
                $this->db->where('un.map_id =', $a_where['map_id']);
                unset($a_where['map_id']);
            }

            /*Talent search, will list director details*/
            if (isset($a_where['triggerd_from'])) {
                
                if($permission != 2){

                    $fields .= ', tu.user_id AS t_id, CONCAT(tu.first_name, " ", tu.middle_name, " ", tu.last_name) AS name ';
                    $this->db->join('cb_user_details AS tu', 'tu.user_id = un.user_id', 'left');
                    $this->db->group_by('tu.user_id, un.map_id');
                }
                
                $this->db->where('un.triggerd_from =', $a_where['triggerd_from']);
                unset($a_where['triggerd_from']);
            } 

            /*Admin / director search, it will list talents details*/
            if (isset($a_where['user_id'])) {

                $fields .= ', fu.user_id AS d_id, CONCAT(fu.first_name, " ", fu.middle_name, " ", fu.last_name) AS name ';
                $this->db->join('cb_user_details AS fu', 'fu.user_id = un.triggerd_from', 'left');
                $this->db->where('un.user_id =', $a_where['user_id']);
                $this->db->group_by('fu.user_id, un.map_id'); 
                unset($a_where['user_id']);
            }
        } else {

            $fields .= ', tu.user_id AS t_id, CONCAT(tu.first_name," ", tu.middle_name," ", tu.last_name) AS t_name ';
            $fields .= ', fu.user_id AS d_id, CONCAT(fu.first_name," ", fu.middle_name," ", fu.last_name) AS d_name ';

            $this->db->join('cb_user_details AS fu', 'fu.user_id = un.triggerd_from', 'left');
            $this->db->join('cb_user_details AS tu', 'tu.user_id = un.user_id', 'left');
            $this->db->group_by('tu.user_id, un.map_id');
        }

        $this->db->select($fields);
        $this->db->from('cb_user_notifications AS un');
        $this->db->order_by("un.notification_on", "DESC");
        return $this->db->get('cb_user_notifications', $limit, $offset)->result_array();
        // echo $this->db->last_query();die;
    }

    public function check_notification_map($fields = null, $a_where = array(), $limit = null, $offset = null)
    {

        $a_settings = settings();
        if ($fields) {
            $this->db->select($fields);
        }
        $a_notification_map = $this->db->get_where('np', $a_where, $limit, $offset)->result_array();
        foreach ($a_notification_map as $key => $value) {
            $a_notification['trigger'][] = $a_settings['notification'][$value['trigger']]['trigger'];
        }
    }
    /*
    Get interview of director
    Params : @user_id of director, @interview_id as
     */
    public function check_interview_exists($a_where, $fields = '', $limit = null, $offset = null)
    {
        if ($fields) {
            $this->db->select($fields);
        }

        if (!empty($a_where)) {
            
            if (isset($a_where['to'])) {
                $this->db->join('cb_user_notifications', 'cb_user_notifications.notification_relation = cb_user_interview.id', 'left');
                $this->db->where('cb_user_notifications.user_id =', $a_where['to']);
                unset($a_where['to']);
            }

            if (isset($a_where['user_id'])) {
                $this->db->where('cb_user_interview.user_id =', $a_where['user_id']);
            }

            if (isset($a_where['id'])) {
                $this->db->where('cb_user_interview.id =', $a_where['id']);
            }
        }
        return $this->db->get('cb_user_interview', $limit, $offset)->result_array();
    }
    /*
    Inser or update interview details in interview master table
     */
    public function schedule_interview($a_post = array())
    {
        $a_insert_intw = array();
        $intrw_id      = 0;

        if (!empty($a_post) && isset($a_post['user_id'])) {

            $a_insert_intw['user_id']           = isset($a_post['user_id']) ? $a_post['user_id'] : '';
            $a_insert_intw['intrw_subject']     = isset($a_post['intrw_subject']) ? $a_post['intrw_subject'] : '';
            $a_insert_intw['intrw_on']          = isset($a_post['intrw_on']) ? $a_post['intrw_on'] : '';
            $a_insert_intw['intrw_location']    = isset($a_post['intrw_location']) ? $a_post['intrw_location'] : '';
            $a_insert_intw['intrw_description'] = isset($a_post['intrw_description']) ? $a_post['intrw_description'] : '';
            $a_insert_intw['intrw_due']         = isset($a_post['intrw_due']) ? $a_post['intrw_due'] : '';
            $a_insert_intw['oganizer_name']     = isset($a_post['oganizer_name']) ? $a_post['oganizer_name'] : '';
            $a_insert_intw['oganizer_contact']  = isset($a_post['oganizer_contact']) ? $a_post['oganizer_contact'] : '';
            $a_insert_intw['oganizer_website']  = isset($a_post['oganizer_website']) ? $a_post['oganizer_website'] : '';
            $a_insert_intw['added_on']          = date('Y-m-d H:i:s');
            $a_insert_intw['modified_on']       = date('Y-m-d H:i:s');
            $a_insert_intw['intrw_status']      = 1;
        }

        if (!empty($a_insert_intw) && (isset($a_post['interview_id']) && $a_post['interview_id'] != '')) {

            $a_updt_where['id'] = $a_post['interview_id'];
            unset($a_insert_intw['added_on']);

            if ($this->updateRow('cb_user_interview', $a_insert_intw, $a_updt_where)) {
                $intrw_id = $a_updt_where['id'];
            }
            return $intrw_id;
        } else {

            $intrw_id = $this->insertRow('cb_user_interview', $a_insert_intw);
        }
        return $intrw_id;
    }

    public function render_notifications( $all_notifications = array() )
    {
        $notifaction_response = array();

        if (!empty($all_notifications)) {

            $settings_notification = settings(array('setting_type' => 'notification'));
            $settings_features     = settings(array('setting_type' => 'feature_types'));
            
            foreach ($all_notifications as $key => $notification) {

                if (isset($settings_notification['notification'][$notification['trigger']])) {

                    $trigger = isset($settings_notification['notification'][$notification['trigger']]['trigger']) ? $settings_notification['notification'][$notification['trigger']]['trigger'] : '';
                    $action  = isset($settings_notification['notification'][$notification['action']]['action']) ? $settings_notification['notification'][$notification['action']]['action'] : '';
                    $type    = isset($settings_notification['notification'][$notification['notification_type']]['notification_type']) ? $settings_notification['notification'][$notification['notification_type']]['notification_type'] : '';

                    if (isset($settings_features['feature_types'][$trigger])) {
                        $feature_type = $settings_features['feature_types'][$trigger];
                    } else {
                        $feature_type = 'unknown';
                    }

                    $notifaction_response[$key]['feature']             = $feature_type;
                    $notifaction_response[$key]['trigger']             = $trigger;
                    $notifaction_response[$key]['action']              = $action;
                    $notifaction_response[$key]['type']                = $type;
                    $notifaction_response[$key]['from']                = $notification['triggerd_from'];
                    
                    if (isset($notification['t_name'])) 
                    {
                        $notifaction_response[$key]['t_name']          = $notification['t_name'];
                    } 
                    
                    if (isset($notification['d_name'])) 
                    {
                        $notifaction_response[$key]['d_name']          = $notification['d_name'];
                    } 
                    
                    if (isset($notification['name']))  
                    {
                        $notifaction_response[$key]['name']            = $notification['name'];
                    }
                    
                    $notifaction_response[$key]['to']                  = $notification['user_id'];
                    $notifaction_response[$key]['notification_on']     = $notification['notification_on'];
                    $notifaction_response[$key]['notification_note']   = $notification['notification_note'];
                    $notifaction_response[$key]['notification_status'] = $notification['notification_status'];
                }

            }
        }
        return $notifaction_response;
    }
}
