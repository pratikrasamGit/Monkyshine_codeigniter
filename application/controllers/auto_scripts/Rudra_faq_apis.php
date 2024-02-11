<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_faq_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_faq';
        $this->msg = 'input error';
        $this->return_data = array();
        $this->chk = 0;
        //$this->load->model('global_model', 'gm');
        $this->load->model('Rudra_user_rudra_model', 'user_model');
        $this->load->model('Email_model', 'email_model');
        $this->set_data();
    }

    public function set_data()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $this->json_output(200, array('status' => 200, 'api_status' => false, 'message' => 'Bad request.'));
            exit;
        }

        /*
        $api_key = $this->db->get_where($this->bdp.'app_setting',array('meta_key' =>'rudra_key'))->row();
        $api_password =  $this->input->post('api_key',true);
        if (MD5($api_key->meta_value) == $api_password) {

            $this->api_status = true;
          
        } else {
           
		json_encode(array('status' => 505,'message' => 'Enter YourgMail@gmail.com to get access.', 'data' => array() ));
		  exit;
		  
          
        }
        */
    }

    /***********************Page Route
     
     //rudra_faq API Routes
	$t_name = 'auto_scripts/Rudra_faq_apis/';    
	$route[$api_ver.'faq/(:any)'] = $t_name.'rudra_rudra_faq/$1';

     **********************************/
    function json_output($statusHeader, $response)
    {
        $ci = &get_instance();
        $ci->output->set_content_type('application/json');
        $ci->output->set_status_header($statusHeader);
        $ci->output->set_output(json_encode($response));
    }

    public function index()
    {
        $this->json_output(200, array('status' => 200, 'api_status' => false, 'message' => 'Bad request.'));
    }

    public function rudra_rudra_faq($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'put') {
            $res = $this->rudra_save_data($_POST);
        } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST);
        } elseif ($call_type == 'get') {
            $res = $this->rudra_get_data($_POST);
        } elseif ($call_type == 'list') {
            $res = $this->rudra_paged_data($_POST);
        } elseif ($call_type == 'setting_list') {
            $res = $this->rudra_setting_list_data($_POST);
        } elseif ($call_type == 'delete') {
            $res = $this->rudra_delete_data($_POST);
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function rudra_save_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('question', 'question', 'required');
            $this->form_validation->set_rules('answer', 'answer', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $std = date('Y-m-d H:i:s');
                //Insert Codes goes here 
                $updateArray =
                    array(
                        'question' => $this->input->post('question', true),
                        'answer' => $this->input->post('answer', true),
                    );

                $this->db->insert("$this->table", $updateArray);
                $new_id = $this->db->insert_id();

                $res = $this->db->get_where("$this->table", array('id' => $new_id))->row();
                //Format Data if required
                /*********
                 $res->added_on_custom_date = date('d-M-Y',strtotime($res->added_at));
                 $res->added_on_custom_time = date('H:i:s A',strtotime($res->added_at));
                 $res->updated_on_custom_date = date('d-M-Y',strtotime($res->updated_at));
                 $res->updated_on_custom_time = date('H:i:s A',strtotime($res->updated_at));
                 ************/
                $this->chk = 1;
                $this->msg = 'Data Stored Successfully';
                $this->return_data = $res;
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('question', 'question', 'required');
            $this->form_validation->set_rules('answer', 'answer', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $new_id = $pk_id = $this->input->post('id');
                $chk_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                if (!empty($chk_data)) {
                    $std = date('Y-m-d H:i:s');
                    //Update Codes goes here 
                    $updateArray =
                        array(
                            'question' => $this->input->post('question', true),
                            'answer' => $this->input->post('answer', true),
                        );

                    $this->db->where('id', $pk_id);
                    $this->db->update("$this->table", $updateArray);

                    $this->chk = 1;
                    $this->msg = 'Information Updated';
                    $this->return_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                } else {
                    $this->chk = 0;
                    $this->msg = 'Record Not Found';
                }
            }
        }
    }

    public function rudra_setting_list_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {


                $this->chk = 1;
                $this->msg = 'Small Lists';
                $this->return_data = $list_array =  [];
            }
        }
    }

    public function rudra_delete_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('question', 'question', 'required');
            $this->form_validation->set_rules('answer', 'answer', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $pk_id = $this->input->post('id');
                $chk_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                if (!empty($chk_data)) {

                    // $this->db->where('id',$pk_id);
                    // $this->db->delete("$this->table");
                    $this->chk = 1;
                    $this->msg = 'Information deleted';
                } else {
                    $this->chk = 0;
                    $this->msg = 'Record Not Found';
                }
            }
        }
    }

    public function rudra_get_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('id', 'ID', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $pk_id = $this->input->post('id');
                $res = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                if (!empty($res)) {
                    //Format Data if required
                    /*********
                    $res->added_on_custom_date = date('d-M-Y',strtotime($res->added_at));
                    $res->added_on_custom_time = date('H:i:s A',strtotime($res->added_at));
                    $res->updated_on_custom_date = date('d-M-Y',strtotime($res->updated_at));
                    $res->updated_on_custom_time = date('H:i:s A',strtotime($res->updated_at));
                     ************/
                    $this->chk = 1;
                    $this->msg = 'Data';
                    $this->return_data = $res;
                } else {
                    $this->chk = 0;
                    $this->msg = 'Error: ID not found';
                }
            }
        }
    }

    public function rudra_paged_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $page = $this->input->post('page_number', true);

                $limit = "25";
                $start = "0";
                if ($page > 1) $start = ($page - 1) * $limit;
                $query = "SELECT * FROM $this->table ORDER BY id ASC LIMIT $start , $limit";
                $result = $this->db->query($query);

                /* page count */
                $query_tot = "SELECT * FROM $this->table ORDER BY id DESC;";
                $result_tot = $this->db->query($query_tot);
                $total_result_count = $result_tot->num_rows();
                /* page count */
                if ($result->num_rows() > 0) {
                    $list['help_data'] = array();
                    foreach ($result->result() as $res) {
                        if($lang==1){
                            $qs = $res->question;
                            $ans = $res->answer;
                        }else{
                            $qs = $res->question_dk;
                            $ans = $res->answer_dk;
                        }
                        $list['help_data'][] = ['id' => $res->id, 'question' => $qs, 'answer' => $ans];
                    }

                    $total_pages = ceil($total_result_count / $limit);
                    $list['total_pages_available'] =  strval($total_pages);
                    $list['current_page'] = $page;
                    $list['results_per_page'] = $limit;

                    $this->chk = 1;
                    $this->msg = "Help data's listed successfully";
                    $this->return_data = $list;
                } else {
                    $this->chk = 0;
                    $this->msg = 'No help records found';
                }
            }
        }
    }
}
