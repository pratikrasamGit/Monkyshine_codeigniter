
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_user_registered_car_numbers_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_user_registered_car_numbers';
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


        /* $api_key = $this->db->get_where($this->bdp . 'app_setting', array('meta_key' => 'rudra_key'))->row();
        $api_password =  $this->input->post('api_key', true);
        if (MD5($api_key->meta_value) == $api_password) {

            $this->api_status = true;
        } else {

            json_encode(array('status' => 505, 'message' => 'Enter YourgMail@gmail.com to get access.', 'data' => array()));
            exit;
        } */
    }

    /***********************Page Route
     
     //rudra_user_registered_car_numbers API Routes
	$t_name = 'auto_scripts/Rudra_user_registered_car_numbers_apis/';    
	$route[$api_ver.'user_registered_car_numbers/(:any)'] = $t_name.'rudra_rudra_user_registered_car_numbers/$1';

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

    public function rudra_rudra_user_registered_car_numbers($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'new') {
            $res = $this->rudra_save_data($_POST);
        } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST);
        } elseif ($call_type == 'list') {
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

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $update_array['registration_number'] = $registration_number = (isset($input['registration_number']) && $input['registration_number'] != "") ? $input['registration_number'] : "";
                $update_array['name'] = (isset($input['name']) && $input['name'] != "") ? $input['name'] : "";
                $update_array['brand'] = (isset($input['brand']) && $input['brand'] != "") ? $input['brand'] : "";
                $update_array['model'] = (isset($input['model']) && $input['model'] != "") ? $input['model'] : "";
                $update_array['color'] = (isset($input['color']) && $input['color'] != "") ? $input['color'] : "";
                $update_array['description'] = (isset($input['description']) && $input['description'] != "") ? $input['description'] : "";
                $update_array['car_size'] = (isset($input['car_size']) && $input['car_size'] != "") ? $input['car_size'] : "";

                $check = $this->user_model->checkNumberHasRegistered($user_id, $registration_number);
                if (empty($check)) {
                    $update_array['fk_user_id'] = $this->input->post('user_id', true);
                    // $update_array['registration_number'] = $this->input->post('registration_number', true);
                    $this->db->insert("$this->table", $update_array);
                    $new_id = $this->db->insert_id();
                    if ($new_id) {
                        $added_numbers = $this->user_model->getUserRegisteredCarNumbers($user_id, $id = $new_id);
                        $data = [];
                        foreach ($added_numbers as $key => $reg_no) {
                            $data[] = ['reg_no_id' => $reg_no->id, 'registration_numbers' => $reg_no->registration_number];
                        }
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "car_number_registered_successfully");
                        $this->user_model->updateNotifications($user_id, $message = "New car number " . $update_array['registration_number'] . " registered", $message_dn = "Nyt bilnummer " . $update_array['registration_number'] . " registreret");
                        $this->return_data = $data;
                    } else {
                        $this->msg = getLangMessage($lang, $str = "failed_to_register_car_number_please_try_again_later");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "provided_registration_number_already_exists");
                }
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('reg_no_id', 'registration number id', 'required');
            $this->form_validation->set_rules('registration_number', 'registration number', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();

                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $reg_no_id = (isset($input['reg_no_id']) && $input['reg_no_id'] != "") ? $input['reg_no_id'] : "";
                $registration_number = (isset($input['registration_number']) && $input['registration_number'] != "") ? $input['registration_number'] : "";
                $update_array['name'] = (isset($input['name']) && $input['name'] != "") ? $input['name'] : "";
                $update_array['brand'] = (isset($input['brand']) && $input['brand'] != "") ? $input['brand'] : "";
                $update_array['model'] = (isset($input['model']) && $input['model'] != "") ? $input['model'] : "";
                $update_array['color'] = (isset($input['color']) && $input['color'] != "") ? $input['color'] : "";
                $update_array['description'] = (isset($input['description']) && $input['description'] != "") ? $input['description'] : "";
                $update_array['car_size'] = (isset($input['car_size']) && $input['car_size'] != "") ? $input['car_size'] : "";

                $check_reg_number = $this->user_model->checkIfCarRegistrationNumberExists($user_id, $reg_no_id, $registration_number);
                if (empty($check_reg_number)) {
                    $update_array['registration_number'] = $registration_number;
                    $this->db->where('id', $reg_no_id);
                    $update = $this->db->update("$this->table", $update_array);
                    if ($update) {
                        $added_numbers = $this->user_model->getUserRegisteredCarNumbers($user_id, $id = $reg_no_id);
                        $data = [];
                        foreach ($added_numbers as $key => $reg_no) {
                            $data[] = ['reg_no_id' => $reg_no->id, 'registration_numbers' => $reg_no->registration_number];
                        }
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "car_number_updated_successfully");
                        $this->return_data = $data;
                    } else {
                        $this->msg = getLangMessage($lang, $str = "failed_to_update_car_registration_number_please_try_again_later");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "provided_registration_number_already_exists");
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
            $this->form_validation->set_rules('car_id', 'car_id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $car_id = $this->input->post('car_id');
                $chk_data = $this->db->get_where("$this->table", array('id' => $car_id))->row();
                if (!empty($chk_data)) {

                    $this->db->where('id', $car_id);
                    $update = $this->db->update($this->table, ['is_deleted' => '1']);

                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "car_deleted");
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "record_not_found");
                }
            }
        }
    }

    public function rudra_get_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();

                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $reg_no_id = (isset($input['reg_no_id']) && $input['reg_no_id'] != "") ? $input['reg_no_id'] : "";
                $added_numbers = $this->user_model->getUserRegisteredCarNumbers($user_id, $reg_no_id);

                if (!empty($added_numbers)) {
                    $data = [];
                    foreach ($added_numbers as $key => $reg_no) {
                        $data[] = [
                            'reg_no_id' => $reg_no->id,
                            'car_size' => (isset($reg_no->car_size) && $reg_no->car_size != "") ? $reg_no->car_size : "",
                            'car_size_definition' => (isset($reg_no->car_size) && $reg_no->car_size != "") ? CarSizeType($reg_no->car_size) : "",
                            'registration_number' => (isset($reg_no->registration_number) && $reg_no->registration_number != "") ? $reg_no->registration_number : "",
                            'name' => (isset($reg_no->name) && $reg_no->name != "") ? $reg_no->name : "",
                            'brand' => (isset($reg_no->brand) && $reg_no->brand != "") ? $reg_no->brand : "",
                            'model' => (isset($reg_no->model) && $reg_no->model != "") ? $reg_no->model : "",
                            'color' => (isset($reg_no->color) && $reg_no->color != "") ? $reg_no->color : "",
                            'description' => (isset($reg_no->description) && $reg_no->description != "") ? $reg_no->description : "",
                        ];
                    }
                    $this->chk = 1;
                    $this->msg = 'User registered car numbers listed successfully';
                    $this->return_data = $data;
                } else {
                    $this->msg = 'No Records Found';
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
