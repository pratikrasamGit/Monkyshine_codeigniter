
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_washer_bank_info_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_washer_bank_info';
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
     
     //rudra_washer_bank_info API Routes
	$t_name = 'auto_scripts/Rudra_washer_bank_info_apis/';    
	$route[$api_ver.'washer_bank_info/(:any)'] = $t_name.'rudra_rudra_washer_bank_info/$1';

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

    public function rudra_rudra_washer_bank_info($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'update') {
            $res = $this->rudra_save_data($_POST);
            /* } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST); */
        } elseif ($call_type == 'get') {
            $res = $this->rudra_get_data($_POST);
        } elseif ($call_type == 'paged_data') {
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

            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('registration_number', 'registration number', 'required');
            $this->form_validation->set_rules('account_number', 'account number', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $std = date('Y-m-d H:i:s');
                //Insert Codes goes here 
                $insert_array['fk_user_id'] = $user_id = $this->input->post('user_id', true);
                $update_array['registration_number'] = $insert_array['registration_number'] = $this->input->post('registration_number', true);
                $update_array['account_number'] = $insert_array['account_number'] = $this->input->post('account_number', true);

                $check_bank = $this->bankInfo($user_id = $user_id, $bank_id = "");
                if (!empty($check_bank)) {
                    $this->db->where('id', $check_bank['bank_id']);
                    $update = $this->db->update("$this->table", $update_array);
                    if ($update) {
                        $this->chk = 1;
                        $this->user_model->updateNotifications($user_id, $message = "Bank details has been updated successfully", $message_dn = "Bankoplysningerne er blevet opdateret");
                        $this->msg = getLangMessage($lang, $str = "Bank_details_has_been_updated_successfully");
                    } else {
                        $this->msg = getLangMessage($lang, $str = "Failed_to_update_bank_details_Please_try_again_later");
                    }
                    $updated_bank = $this->bankInfo($user_id = $user_id, $bank_id = $check_bank['bank_id']);
                    $this->return_data = $updated_bank;
                } else {
                    $this->db->insert("$this->table", $insert_array);
                    $new_id = $this->db->insert_id();
                    if ($new_id) {
                        $added_bank = $this->bankInfo($user_id = $user_id, $bank_id = $new_id);
                        $this->chk = 1;
                        $this->user_model->updateNotifications($user_id, $message = "Bank details has been added successfully", $message_dn = "Bankoplysninger er blevet tilføjet");
                        $this->msg = getLangMessage($lang, $str = "Bank_details_has_been_added_successfully");
                        $this->return_data = $added_bank;
                    } else {
                        $this->msg = getLangMessage($lang, $str = "Failed_to_add_bank_details_Please_try_again_later");
                    }
                }
            }
        }
    }

    public function bankInfo($user_id = "", $bank_id = "")
    {
        if ($bank_id != "") $this->db->where(['id' => $bank_id]);
        $this->db->where(['status' => "1", 'is_deleted' => '0', 'fk_user_id' => $user_id]);
        $bank_info = $this->db->get($this->table);

        $bank = [];
        if ($bank_info->num_rows() > 0) {
            $b = $bank_info->row();
            $bank = ["bank_id" => $b->id, 'washer_id' => $b->fk_user_id, 'registration_number' => $b->registration_number, 'account_number' => $b->account_number];
        }

        return $bank;
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('fk_user_id', 'fk_user_id', 'required');
            $this->form_validation->set_rules('registration_number', 'registration_number', 'required');
            $this->form_validation->set_rules('account_number', 'account_number', 'required');
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
                            'fk_user_id' => $this->input->post('fk_user_id', true),
                            'registration_number' => $this->input->post('registration_number', true),
                            'account_number' => $this->input->post('account_number', true),
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
                $this->return_data = $list_array = [];
            }
        }
    }

    public function rudra_delete_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('fk_user_id', 'fk_user_id', 'required');
            $this->form_validation->set_rules('registration_number', 'registration_number', 'required');
            $this->form_validation->set_rules('account_number', 'account_number', 'required');
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
            $this->form_validation->set_rules('user_id', 'ID', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id');
                $added_bank = $this->bankInfo($user_id, $bank_id = "");
                if (!empty($added_bank)) {
                    $this->chk = 1;
                    $this->msg = 'Bank details has been listed successfully';
                    $this->return_data = $added_bank;
                } else {
                    $this->msg = 'Bank records not found';
                }
            }
        }
    }

    public function rudra_paged_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            //$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $per_page = 50; // No. of records per page
                $page_number = $this->input->post('page_number', true);
                $page_number = ($page_number == 1 ? 0 : $page_number);
                $start_index = $page_number * $per_page;
                $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT $start_index , $per_page";
                $result = $this->db->query($query)->result();
                if (!empty($result)) {
                    $list = array();
                    foreach ($result as $res) {
                        $res->added_on_custom_date = date('d-M-Y', strtotime($res->added_at));
                        $res->added_on_custom_time = date('H:i:s A', strtotime($res->added_at));
                        $res->updated_on_custom_date = date('d-M-Y', strtotime($res->updated_at));
                        $res->updated_on_custom_time = date('H:i:s A', strtotime($res->updated_at));
                        $list[] = $res;
                    }
                    $this->chk = 1;
                    $this->msg = 'Paged Data';
                    $this->return_data = $list;
                } else {
                    $this->chk = 0;
                    $this->msg = 'No recond exist';
                }
            }
        }
    }
}
