
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_user_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_user';
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
     
     //rudra_user API Routes
	$t_name = 'auto_scripts/Rudra_user_apis/';    
	$route[$api_ver.'user/(:any)'] = $t_name.'rudra_rudra_user/$1';

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

    public function rudra_rudra_user($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'entry') {
            $res = $this->rudra_user_entry($_POST);
        } elseif ($call_type == 'login') {
            $res = $this->rudra_user_login($_POST);
        } elseif ($call_type == 'forgot-password') {
            $res = $this->rudra_user_forgotPassword($_POST);
        } elseif ($call_type == 'reset-password') {
            $res = $this->rudra_user_resetPassword($_POST);
        } elseif ($call_type == 'update-profile' || $call_type == 'edit-profile') {
            $res = $this->rudra_save_data($call_type);
        } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST);
        } elseif ($call_type == 'view-profile') {
            $res = $this->rudra_get_data($_POST);
        } elseif ($call_type == 'paged_data') {
            $res = $this->rudra_paged_data($_POST);
        } elseif ($call_type == 'setting_list') {
            $res = $this->rudra_setting_list_data($_POST);
        } elseif ($call_type == 'change-password') {
            $res = $this->changePassword($_POST);
        } elseif ($call_type == 'delete-profile') {
            $res = $this->rudra_delete_data($_POST);
        } elseif ($call_type == 'notification') {
            $res = $this->notificationToggle($_POST);
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function rudra_user_entry()
    {
        if ($_POST && !empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $input = $this->input->post();

            $this->form_validation->set_rules('user_type', 'user type', 'required');
            $this->form_validation->set_rules('login_type', 'login type', 'required');
            // $this->form_validation->set_rules('fcm_token', 'fcm token', 'required');
            // $this->form_validation->set_rules('email', 'email', 'required');

            if ($input['login_type'] == "0") {
                $this->form_validation->set_rules('password', 'password', 'required');
            } else {
                $this->form_validation->set_rules('social_id', 'social id', 'required');
            }

            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $apple_identifier = (isset($input['apple_identifier']) && $input['apple_identifier'] != "") ? $input['apple_identifier'] : "";
                // common inputs
                $insert['login_type'] = (isset($input['login_type']) && $input['login_type'] != "") ? $input['login_type'] : "";
                $insert['email'] = (isset($input['email']) && $input['email'] != "") ? $input['email'] : "";
                $insert['password'] = (isset($input['password']) && $input['password'] != "") ? sha1($input['password']) : "";
                $insert['social_id'] = (isset($input['social_id']) && $input['social_id'] != "") ? $input['social_id'] : NULL;
                $update['fcm_token'] = $insert['fcm_token'] = (isset($input['fcm_token']) && $input['fcm_token'] != "") ? $input['fcm_token'] : NULL;
                $insert['apple_identifier'] = (isset($input['apple_identifier']) && $input['apple_identifier'] != "") ? $input['apple_identifier'] : NULL;
                $insert['name'] = (isset($input['name']) && $input['name'] != "") ? $input['name'] : NULL;
                $insert['surname'] = (isset($input['surname']) && $input['surname'] != "") ? $input['surname'] : NULL;
                // common inputs

                /* device information */
                $update['device_token'] = $insert['device_token'] = (isset($input['device_token']) && $input['device_token'] != "") ? $input['device_token'] : NULL;
                $update['app_version'] = $insert['app_version'] = (isset($input['app_version']) && $input['app_version'] != "") ? $input['app_version'] : NULL;
                $update['device_type'] = $insert['device_type'] = (isset($input['device_type']) && $input['device_type'] != "") ? $input['device_type'] : NULL;
                $update['device_company'] = $insert['device_company'] = (isset($input['device_company']) && $input['device_company'] != "") ? $input['device_company'] : NULL;
                $update['device_version'] = $insert['device_version'] = (isset($input['device_version']) && $input['device_version'] != "") ? $input['device_version'] : NULL;
                $update['device_location'] = $insert['device_location'] = (isset($input['device_location']) && $input['device_location'] != "") ? $input['device_location'] : NULL;
                $update['device_lat'] = $insert['device_lat'] = (isset($input['device_lat']) && $input['device_lat'] != "") ? $input['device_lat'] : NULL;
                $update['device_lang'] = $insert['device_lang'] = (isset($input['device_lang']) && $input['device_lang'] != "") ? $input['device_lang'] : NULL;
                $update['device_city'] = $insert['device_city'] = (isset($input['device_city']) && $input['device_city'] != "") ? $input['device_city'] : NULL;
                $update['device_country'] = $insert['device_country'] = (isset($input['device_country']) && $input['device_country'] != "") ? $input['device_country'] : NULL;
                /* device information */

                /* contact number */
                $insert['contact_number'] = (isset($input['contact_number']) && $input['contact_number'] != "") ? $input['contact_number'] : NULL;
                /* contact number */

                $phone_number = "";
                if (isset($insert['contact_number']) && $insert['contact_number'] != "") $phone_number = $insert['contact_number'];
                    
                if ($insert['login_type'] == "0") {
                    $check_user_exists = $this->user_model->checkNormalUserExists($email = $insert['email'], $phone_number);
                } else {
                    $check_user_exists = $this->user_model->checkSocialUserExists($social_id =  $insert['social_id'], $identifier =  $apple_identifier);
                }

                if ($insert['login_type'] != "0" && isset($check_user_exists) && !empty($check_user_exists)) {
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "logged_in_successfully");
                    $return_data = $this->user_model->userProfileInfo($user_id = $check_user_exists->id, $page = "1");
                    $this->return_data = $return_data;
                } else {
                    if ( empty($check_user_exists)) {
                        $insert['name'] = (isset($input['name']) && $input['name'] != "") ? $input['name'] : "";
                        $insert['user_type'] = (isset($input['user_type']) && $input['user_type'] != "") ? $input['user_type'] : "";
                        $insert['login_type'] = (isset($input['login_type']) && $input['login_type'] != "") ? $input['login_type'] : "";

                        if($insert['login_type'] == "3" && $apple_identifier != ""){
                            $check_user_exists = $this->user_model->checkSocialUserExists($social_id =  $insert['social_id'], $identifier =  $apple_identifier);
                            if(empty($check_user_exists)){
                                $this->db->insert("$this->table", $insert);
                                $new_id = $this->db->insert_id();
                            }else{
                                $this->db->where('id', $check_user_exists->id);
                                $device_update = $this->db->update($this->table, $insert);
                                $new_id = $check_user_exists->id;
                            }
                        }else{
                            $check_user_existsemail = $this->user_model->checkNormalUserExists($email = $insert['email'], $phone_number);
                            if(empty($check_user_existsemail)){
                                $this->db->insert("$this->table", $insert);
                                $new_id = $this->db->insert_id();
                            }else{
                                $this->db->where('id', $check_user_existsemail->id);
                                $device_update = $this->db->update($this->table, $insert);
                                $new_id = $check_user_existsemail->id;
                            }
                        }


                        if ($new_id) {
                            $this->chk = 1;
                            $this->msg = getLangMessage($lang, $str = "user_registration_completed");
                            $return_data = $this->user_model->userProfileInfo($user_id = $new_id, $page = "1");
                            $this->return_data = $return_data;
                        } else {
                            $this->msg = getLangMessage($lang, $str = "failed_to_register");
                        }
                    } else {
                        $this->msg = getLangMessage($lang, $str = "user_email_already_exists");
                        // 'User email or contact already exists, Please try login';
                    }
                }
            }
        }
    }

    public function rudra_user_login()
    {
        if ($_POST && !empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "");
                'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $email = (isset($input['email']) && $input['email'] != "") ? $input['email'] : "";
                $password = (isset($input['password']) && $input['password'] != "") ? $input['password'] : "";


                $check_user_exists = $this->user_model->checkUserExists($email, $password);
                if (isset($check_user_exists) && !empty($check_user_exists)) {
                    $update = [];
                    /* device information */
                    if (isset($input['device_token']) && $input['device_token'] != "") $update['device_token'] = $input['device_token'];
                    if (isset($input['app_version']) && $input['app_version'] != "") $update['app_version'] = $input['app_version'];
                    if (isset($input['device_type']) && $input['device_type'] != "") $update['device_type'] = $input['device_type'];
                    if (isset($input['device_company']) && $input['device_company'] != "") $update['device_company'] = $input['device_company'];
                    if (isset($input['device_version']) && $input['device_version'] != "") $update['device_version'] = $input['device_version'];
                    if (isset($input['device_location']) && $input['device_location'] != "") $update['device_location'] = $input['device_location'];
                    if (isset($input['device_lat']) && $input['device_lat'] != "") $update['device_lat'] = $input['device_lat'];
                    if (isset($input['device_lang']) && $input['device_lang'] != "") $update['device_lang'] = $input['device_lang'];
                    if (isset($input['device_city']) && $input['device_city'] != "") $update['device_city'] = $input['device_city'];
                    if (isset($input['device_country']) && $input['device_country'] != "") $update['device_country'] = $input['device_country'];
                    if (isset($input['fcm_token']) && $input['fcm_token'] != "") $update['fcm_token'] = $input['fcm_token'];

                    if (!empty($update)) {
                        $this->db->where('id', $check_user_exists->id);
                        $device_update = $this->db->update($this->table, $update);
                    }
                    /* device information */
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "logged_in_successfully");
                    $this->return_data = $this->user_model->userProfileInfo($user_id = $check_user_exists->id, $page = "1");
                } else {
                    $this->msg = getLangMessage($lang, $str = "invalid_email_password");
                }
            }
        }
    }

    public function rudra_user_forgotPassword()
    {
        if ($_POST && !empty($_POST)) {

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {

                $to_email = (isset($input['email']) && $input['email'] != "") ? $input['email'] : "";
                $code = substr(str_shuffle("0123456789"), 0, 6);
                $user = $this->user_model->checkNormalUserExists($email = $to_email, $phone_number = "");
                if (!empty($user)) {
                    $this->db->where(['id' => $user->id]);
                    $update = $this->db->update("$this->table", ['forgot_code' => $code, 'forgot_time' => date('Y-m-d H:i:s')]);
                    if ($update) {
                        $message = "Your reset code is: " . $code;
                        if ($_SERVER['HTTP_HOST'] != "localhost" && $to_email != "") $this->email_model->mail_utf8($to = $to_email, $from_user = "Monkyshine", $from_email = "monkyshine@team.com", $subject = 'Forgot Password Request - Monkyshine', $message);

                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "please_check_your_email_we_have_sent_a_verification_code_to_reset_your_password");
                        $this->return_data = ['user_id' => $user->id, 'email' => $user->email];
                    } else {
                        $this->msg = getLangMessage($lang, $str = "failed_to_change_password_please_try_again_later");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }

    public function rudra_user_resetPassword()
    {
        if ($_POST && !empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('otp', 'otp', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $otp = (isset($input['otp']) && $input['otp'] != "") ? $input['otp'] : "";
                $password = (isset($input['password']) && $input['password'] != "") ? $input['password'] : "";
                $confirm_password = (isset($input['confirm_password']) && $input['confirm_password'] != "") ? $input['confirm_password'] : "";

                if ($password == $confirm_password) {
                    $uppercase = preg_match('@[A-Z]@', $password);
                    $lowercase = preg_match('@[a-z]@', $password);
                    $number    = preg_match('@[0-9]@', $password);
                    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
                        $this->msg = getLangMessage($lang, $str = "please_enter_a_strong_password_password_must_be_at_least_8_characters_long_with_digit_upper_and_lower_case_characters");
                    } else {
                        $user = $this->user_model->checkIfUserExists($user_id);
                        if (!empty($user)) {
                            if ($user->forgot_code == $otp) {
                                $this->db->where(['id' => $user->id]);
                                $update = $this->db->update("$this->table", ['password' => sha1($password), 'forgot_code' => NULL, 'forgot_time' => NULL]);
                                if ($update) {
                                    $this->chk = 1;
                                    $this->msg = getLangMessage($lang, $str = "password_changed_successfully");
                                    $this->return_data = [];
                                } else {
                                    $this->msg = getLangMessage($lang, $str = "failed_to_change_password_please_try_again_later");
                                }
                            } else {
                                $this->msg = getLangMessage($lang, $str = "invalid_expired_otp");
                            }
                        } else {
                            $this->msg = getLangMessage($lang, $str = "user_not_found");
                        }
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "password_and_confirm_password_does_not_match");
                }
            }
        }
    }

    public function rudra_save_data($type)
    {
        if ($_POST && !empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($type == "update-profile") {
                $this->form_validation->set_rules('name', 'name', 'required');
                $this->form_validation->set_rules('surname', 'surname', 'required');
                $this->form_validation->set_rules('contact_email', 'contact_email', 'required');
                $this->form_validation->set_rules('contact_number', 'contact_number', 'required');
                $this->form_validation->set_rules('address', 'address', 'required');
                $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
                $this->form_validation->set_rules('city', 'city', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                /* update array */
                $user_id =  (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $check_user_exists = $this->user_model->checkIfUserExists($user_id);
                if (!empty($check_user_exists)) {
                    /* user info */
                    $user_info = $check_user_exists;
                    /* user info */
                    $update = [];

                    if (isset($input['name']) && $input['name'] != "") $update['name'] = $input['name'];
                    if (isset($input['surname']) && $input['surname'] != "") $update['surname'] = $input['surname'];
                    if (isset($input['contact_email']) && $input['contact_email'] != "") $update['contact_email'] = $input['contact_email'];
                    if (isset($input['contact_number']) && $input['contact_number'] != "") $update['contact_number'] = $input['contact_number'];
                    if (isset($input['lat']) && $input['lat'] != "") $update['lat'] = $input['lat'];
                    if (isset($input['lon']) && $input['lon'] != "") $update['lon'] = $input['lon'];
                    if (isset($input['address']) && $input['address'] != "") $update['address'] = $input['address'];
                    if (isset($input['zipcode']) && $input['zipcode'] != "") $update['zipcode'] = $input['zipcode'];
                    if (isset($input['city']) && $input['city'] != "") $update['city'] = $input['city'];
                    /* update array */

                    /* checked if email is already used by another user account or not */
                    $email_check = true;
                    /* if (isset($input['email'])) {
                        $check_email = $this->user_model->checkIfEMailExists($user_id, $email = $input['email']);
                        if (!empty($check_email)) $email_check = false;
                    } */
                    /* checked if email is already used by another user account or not */

                    /* checked if phone is already used by another user account or not */
                    $phone_check = true;
                    if (isset($input['contact_number'])) {
                        $check_contact = $this->user_model->checkIfPhoneExists($user_id, $phone = $input['contact_number']);
                        if (!empty($check_contact)) $phone_check = false;
                    }
                    /* checked if phone is already used by another user account or not */

                    if ($email_check == false) {
                        $this->msg = getLangMessage($lang, $str = "provided_email_is_used_by_another_account_please_use_a_different_email");
                    } elseif ($phone_check == false) {
                        $this->msg = getLangMessage($lang, $str = "provided_contact_is_used_by_another_account_please_use_a_different_contact_number");
                    } else {
                        if (!empty($update)) {
                            $this->db->where('id', $user_info->id);
                            $update_profile = $this->db->update($this->table, $update);
                            if ($update_profile) {
                                $this->chk = 1;
                                $this->msg = getLangMessage($lang, $str = "user_profile_updated_successfully");
                                $this->user_model->updateNotifications($user_id, $message = "profile updated successfully", $meddage_dn ="profil blev opdateret");
                                $this->return_data = $this->user_model->userProfileInfo($user_id = $user_info->id, $page = "1");
                            } else {
                                $this->msg = getLangMessage($lang, $str = "failed_to_update_user_profile_please_try_again_later");
                            }
                        } else {
                            $this->chk = 1;
                            $this->msg = getLangMessage($lang, $str = "user_profile_updated_successfully");
                            $this->return_data = $this->user_model->userProfileInfo($user_id = $user_info->id, $page = "1");
                        }
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('user_type', 'user_type', 'required');
            $this->form_validation->set_rules('login_type', 'login_type', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('surname', 'surname', 'required');
            $this->form_validation->set_rules('contact_number', 'contact_number', 'required');
            $this->form_validation->set_rules('lat', 'lat', 'required');
            $this->form_validation->set_rules('lon', 'lon', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('city', 'city', 'required');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('is_deleted', 'is_deleted', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $new_id = $pk_id = $this->input->post('id');
                $chk_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                if (!empty($chk_data)) {
                    $std = date('Y-m-d H:i:s');
                    //Update Codes goes here 
                    $updateArray =
                        array(
                            'user_type' => $this->input->post('user_type', true),
                            'login_type' => $this->input->post('login_type', true),
                            'email' => $this->input->post('email', true),
                            'password' => $this->input->post('password', true),
                            'name' => $this->input->post('name', true),
                            'surname' => $this->input->post('surname', true),
                            'contact_number' => $this->input->post('contact_number', true),
                            'lat' => $this->input->post('lat', true),
                            'lon' => $this->input->post('lon', true),
                            'address' => $this->input->post('address', true),
                            'zipcode' => $this->input->post('zipcode', true),
                            'city' => $this->input->post('city', true),
                            'status' => $this->input->post('status', true),
                            'is_deleted' => $this->input->post('is_deleted', true),
                        );

                    $this->db->where('id', $pk_id);
                    $this->db->update("$this->table", $updateArray);

                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "information_updated");
                    $this->return_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "record_not_found");
                }
            }
        }
    }

    public function rudra_setting_list_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "small_lists");
                $this->return_data = $list_array = [];
            }
        }
    }

    public function rudra_delete_data()
    {
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
                $user_id = $this->input->post('user_id');
                $user = $this->user_model->checkIfUserExists($user_id);
                if (!empty($user)) {
                    $this->db->where('id', $user->id);
                    $update = $this->db->update($this->table, ['is_deleted' => '1']);
                    if ($update) {
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "account_deleted_successfully");
                    } else {
                        $this->msg = getLangMessage($lang, $str = "failed_to_delete_user_profile_please_try_again_later");
                    }
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }

    public function rudra_get_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'ID', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                /* update array */
                $user_id =  (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $check_user_exists = $this->user_model->checkIfUserExists($user_id);
                if (!empty($check_user_exists)) {
                    /* user info */
                    $user_info = $check_user_exists;
                    $return_data = $this->user_model->userProfileInfo($user_id = $user_info->id, $page = "1");

                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "user_profile_info_listed_successfully");
                    $this->return_data = $return_data;
                } else {
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }

    public function changePassword()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'ID', 'required');
            // $this->form_validation->set_rules('old_password', 'old password', 'required');
            $this->form_validation->set_rules('new_password', 'new password', 'required');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                /* update array */
                $user_id =  (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $old_password =  (isset($input['old_password']) && $input['old_password'] != "") ? $input['old_password'] : "";
                $new_password =  (isset($input['new_password']) && $input['new_password'] != "") ? $input['new_password'] : "";
                $confirm_password =  (isset($input['confirm_password']) && $input['confirm_password'] != "") ? $input['confirm_password'] : "";
                $check_user_exists = $this->user_model->checkIfUserExists($user_id);
                if($old_password!=''){
                    if (!empty($check_user_exists)) {
                        $user_info = $check_user_exists;

                        if ($old_password != $new_password) {
                            $this->db->where(['id' => $user_info->id, 'password' => sha1($old_password)]);
                            $old = $this->db->get($this->table);
                            if ($old->num_rows() > 0) {
                                if ($confirm_password == $new_password) {
                                    $update_array['password'] = sha1($new_password);
                                    $this->db->where('id', $user_info->id);
                                    $update = $this->db->update($this->table, $update_array);
                                    if ($update) {
                                        $this->chk = 1;
                                        $this->msg = getLangMessage($lang, $str = "password_changed_successfully");
                                    } else {
                                        $this->msg = getLangMessage($lang, $str = "failed_to_change_your_password_please_try_again_later");
                                    }
                                } else {
                                    $this->msg = getLangMessage($lang, $str = "new_and_confirm_password_are_not_the_same");
                                }
                            } else {
                                $this->msg = getLangMessage($lang, $str = "wrong_old_password");
                            }
                        } else {
                            $this->msg = getLangMessage($lang, $str = "old_and_new_password_cannot_be_same");
                        }
                    } else {
                        $this->msg = getLangMessage($lang, $str = "user_not_found");
                    }
                }else{
                    $this->msg = getLangMessage($lang, $str = "enter_old_password");
                }
            }
        }
    }

    public function rudra_paged_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            //$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
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
                    $this->msg = getLangMessage($lang, $str = "paged_data");
                    $this->return_data = $list;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "no_records_found");
                }
            }
        }
    }

    public function notificationToggle()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('notification', 'notification', 'required');
            //$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                $user_id = (isset($input['user_id']) && $input['user_id'] != "" && is_numeric($input['user_id'])) ? $input['user_id'] : "";
                $notification = (isset($input['notification']) && $input['notification'] != "" && is_numeric($input['notification'])) ? $input['notification'] : "";

                $where = ['status' => '1', 'is_deleted' => '0', 'id' => $user_id];
                $this->db->where($where);
                $query = $this->db->get('rudra_user');
                if ($query->num_rows() > 0) {
                    $this->db->where($where);
                    $update = $this->db->update('rudra_user', ['notification_status' => $notification]);
                    if ($update) {
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "notification_has_been_updated_successfully");
                    } else {
                        $this->msg = getLangMessage($lang, $str = "problem_occurred_while_updating_notification_please_try_again_later");
                    }
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }
}
