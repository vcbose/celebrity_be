<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Load rest controller extenstion
require_once APPPATH . '/libraries/REST_Controller.php';

/**
 *
 * API class for settings
 *
 */
class Plans_api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("plans/Plans_model");
        // $this->load->model("setting/Setting_model");
    }

    /**
     * Get method for plans
     * @param string get params
     * @return json  api response
     */
    public function plans_get()
    {
        try {
            $fields    = null;
            $offset    = null;
            $limit     = null;
            $getParams = $this->get();

            if (isset($getParams['fields'])) {
                $fields = $getParams['fields'];
                unset($getParams['fields']);
            }
            if (isset($getParams['offset'])) {
                $offset = $getParams['offset'];
                unset($getParams['offset']);
            }
            if (isset($getParams['limit'])) {
                $limit = $getParams['limit'];
                unset($getParams['limit']);
            }

            $data      = $this->Plans_model->get_plans($fields, $getParams, $offset, $limit);
            $plan_data = array();
            $plans     = array();
            if (!empty($data)) {

                foreach ($data as $key => $object) {
                    $plan_data[$object->plan_id]         = (array) $object;
                    $plan_data[$object->plan_id]['plan_features'] = json_decode(stripcslashes($object->plan_features));
                    // $plan_data[$object->plan_id]['plan_features'] = array_values( $plan_data[$object->plan_id]['plan_features'] );
                }
                $plans = array_values($plan_data);
            }
            
            $response = array('status' => true, 'data' => $plans);
            $this->response($response, parent::HTTP_OK);

        } catch (Exception $ex) {

            $response = array('status' => false, 'message' => 'Unexpected error occurred');
            $this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
