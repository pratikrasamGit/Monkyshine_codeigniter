
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_wash_comments_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_wash_comments';
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
     
     //rudra_wash_comments API Routes
	$t_name = 'auto_scripts/Rudra_wash_comments_apis/';    
	$route[$api_ver.'wash-comment/(:any)'] = $t_name.'rudra_rudra_wash_comments/$1';

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

    public function rudra_rudra_wash_comments($param1)
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
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function rudra_save_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash_id', 'required');
            $this->form_validation->set_rules('comment', 'comment', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();

                $update_array['fk_user_id'] = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $update_array['fk_wash_id'] = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";
                $update_array['comment'] = (isset($input['comment']) && $input['comment'] != "") ? $input['comment'] : "";
                //Insert Codes goes here 
                $this->db->insert("$this->table", $update_array);
                $new_id = $this->db->insert_id();

                // $res =$this->commentInfo
                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "your_comment_added_successfully");
                // $this->return_data = ;
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('fk_user_id', 'fk_user_id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash_id', 'required');
            $this->form_validation->set_rules('comment', 'comment', 'required');
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
                            'wash_id' => $this->input->post('wash_id', true),
                            'comment' => $this->input->post('comment', true),
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
            $this->form_validation->set_rules('wash_id', 'wash_id', 'required');
            $this->form_validation->set_rules('comment', 'comment', 'required');
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
            $this->form_validation->set_rules('wash_id', 'wash id', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            //$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $wash_id = $this->input->post('wash_id', true);
                $user_id = $this->input->post('user_id', true);
                $page = $this->input->post('page_number', true);
                $limit = 25;
                $start = "0";
                if ($page > 1) $start = ($page - 1) * $limit;
                $query = "SELECT `wc`.*, `u`.`name` FROM `" . $this->table . "` `wc` LEFT JOIN `rudra_user` `u` ON `wc`.`fk_user_id` = `u`.`id` WHERE `wc`.`fk_wash_id` = '" . $wash_id . "' AND `wc`.`status` = '1' AND `wc`.`is_deleted` = '0' ORDER BY `wc`.`id` ASC LIMIT " . $start . " , " . $limit . ";";
                $result = $this->db->query($query);

                $query1 = "SELECT `wc`.*, `u`.`name` FROM `" . $this->table . "` `wc` LEFT JOIN `rudra_user` `u` ON `wc`.`fk_user_id` = `u`.`id` WHERE `wc`.`fk_wash_id` = '" . $wash_id . "' AND `wc`.`status` = '1' AND `wc`.`is_deleted` = '0' ORDER BY `wc`.`id` ASC ";
                $result1 = $this->db->query($query1);
                $total_result_count = $result1->num_rows();

                $res['data'] = [];
                if ($result->num_rows() > 0) {
                    foreach ($result->result() as $row) {
                        $r['comment_id'] = $row->id;
                        $r['wash_id'] = $row->fk_wash_id;
                        $r['user_id'] = $row->fk_user_id;
                        $r['icon'] = ($row->fk_user_id == $user_id) ? base_url('app_assets/images/admin/user_icon.png') : base_url('app_assets/images/admin/admin_icon.png');
                        $r['name'] = $row->name;
                        $r['comment'] = $row->comment;
                        $r['created_at'] = date('d-m-Y h:i A', strtotime($row->created_at));

                        $res['data'][] = $r;
                    }

                    if (!empty($res['data'])) {
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "wash_comments_listed_successfully");
                    } else {
                        $this->msg = getLangMessage($lang, $str = "no_comments_found");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "no_comments_found");
                }

                $total_pages = ceil($total_result_count / $limit);
                $res['total_pages_available'] =  strval($total_pages);
                $res['current_page'] = $page;
                $res['results_per_page'] = strval($limit);

                $this->return_data = $res;

                /* if (!empty($result)) {
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
                } */
            }
        }
    }
}
