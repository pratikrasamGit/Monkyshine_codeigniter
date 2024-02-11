
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_chat_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_chat';
        $this->upload_table = 'rudra_chat_uploads';
        $this->user_table = 'rudra_user';
        $this->msg = 'input error';
        $this->return_data = array();
        $this->chk = 0;
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
     
     //rudra_chat API Routes
	$t_name = 'auto_scripts/Rudra_chat_apis/';    
	$route[$api_ver.'chat/(:any)'] = $t_name.'rudra_rudra_chat/$1';

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

    public function rudra_rudra_chat($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'new') {
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
        } elseif ($call_type == 'check_unread') {
            $res = $this->rudra_check_unread($_POST);
        } elseif ($call_type == 'read') {
            $res = $this->rudra_read($_POST);
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function rudra_save_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            // $this->form_validation->set_rules('fk_to_id', 'fk_to_id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if (empty($_FILES))
                $this->form_validation->set_rules('message', 'message', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                //Insert Codes goes here 
                $user_id = $this->input->post('user_id', true);
                $insert_array['fk_from_id'] = $user_id;
                $insert_array['fk_to_id'] = "1";
                $insert_array['message'] = $this->input->post('message');
                $datetime = new DateTime();
                $insert_array['created_at']  = $datetime->format( 'Y-m-d H:i:s' );
                $this->db->insert("$this->table", $insert_array);
                $new_id = $this->db->insert_id();

                if ($new_id == true && isset($_FILES['file_upload']) && !empty($_FILES['file_upload'])) {
                    /* chat upload */
                    $path = "app_assets/images/chat/";
                    $config = array(
                        'upload_path'   => $path,
                        'allowed_types' => 'jpg|jpeg|gif|png',
                        'overwrite'     => 1,
                    );
                    $this->load->library('upload', $config);

                    $upload_insert = array();
                    $files = $_FILES['file_upload'];
                    foreach ($files['name'] as $key => $image) {
                        $_FILES['images[]']['name'] = $files['name'][$key];
                        $_FILES['images[]']['type'] = $files['type'][$key];
                        $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
                        $_FILES['images[]']['error'] = $files['error'][$key];
                        $_FILES['images[]']['size'] = $files['size'][$key];

                        $string = str_replace(' ', '_', $image);

                        $fileName = $new_id . '_' . $string;
                        $config['file_name'] = $fileName;

                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('images[]')) {
                            $images['fk_chat_id'] = $new_id;
                            $images['file_name'] = $fileName;
                            $datetime = new DateTime();
                            $images['created_at']  = $datetime->format( 'Y-m-d H:i:s' );
                            $upload_insert[] = $images;
                        } else {
                            $a = $this->upload->display_errors();
                        }
                    }

                    if (isset($upload_insert) && !empty($upload_insert)) {
                        $this->db->insert_batch("$this->upload_table", $upload_insert);
                        $uploaded = $this->db->insert_id();
                    }
                }
                /* chat upload */

                $list = $this->user_model->chatData($user_id, $page = "1", $new_id);
                
                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "message_sent_successfully");
                $this->return_data = $list;
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('fk_from_id', 'fk_from_id', 'required');
            $this->form_validation->set_rules('fk_to_id', 'fk_to_id', 'required');
            $this->form_validation->set_rules('message', 'message', 'required');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('is_deleted', 'is_deleted', 'required');
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
                            'fk_from_id' => $this->input->post('fk_from_id', true),
                            'fk_to_id' => $this->input->post('fk_to_id', true),
                            'message' => $this->input->post('message', true),
                            'status' => $this->input->post('status', true),
                            'is_deleted' => $this->input->post('is_deleted', true),
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

            $this->form_validation->set_rules('fk_from_id', 'fk_from_id', 'required');
            $this->form_validation->set_rules('fk_to_id', 'fk_to_id', 'required');
            $this->form_validation->set_rules('message', 'message', 'required');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('is_deleted', 'is_deleted', 'required');
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
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $page = $this->input->post('page_number', true);
                $user_id = $this->input->post('user_id', true);
                $list = $this->user_model->chatData($user_id, $page);

                if (!empty($list)) {
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "chats_listed_successfully");
                    $this->return_data = $list;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "no_chats_found");
                }
            }
        }
    }

    public function rudra_check_unread(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id', true);
                $query2 = "SELECT count(id) as count FROM " . $this->table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res2 = $this->db->query($query2)->row();
        
                if ($res2->count > 0) {
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "data_found");
                    $this->return_data = true;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "no_records_found");
                    $this->return_data = false;
                }
            }
        }

    }

    public function rudra_read(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id', true);
                $query2 = "SELECT count(id) as count FROM " . $this->table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res2 = $this->db->query($query2)->row();

                
        
                if ($res2->count > 0) {
                    $updateArray = array('is_read'=>'1');

                    $this->db->where('fk_to_id', $user_id);
                    $this->db->update($this->table, $updateArray); 

                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "information_updated");
                    $this->return_data = true;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "no_records_found");
                    $this->return_data = false;
                }
            }
        }

    }
}
