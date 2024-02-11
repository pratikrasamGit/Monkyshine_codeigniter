
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_booking_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_booking';
        $this->table_wash_types = 'rudra_wash_types';
        $this->table_wash_extra_types = 'rudra_wash_extra_types';
        $this->table_registered_car_numbers = 'rudra_user_registered_car_numbers';
        $this->table_wash_comments = 'rudra_wash_comments';
        $this->table_qa_uploads = 'rudra_qa_uploads';
        $this->table_user = 'rudra_user';
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
     
     //rudra_booking API Routes
	$t_name = 'auto_scripts/Rudra_booking_apis/';
	$route[$api_ver.'booking/(:any)'] = $t_name.'rudra_rudra_booking/$1';

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

    public function rudra_rudra_booking($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'new') {
            $res = $this->rudra_save_data($_POST);
        } elseif ($call_type == 'check-wash-rates') {
            $res = $this->checkWashRates($_POST);
        } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST);
        } elseif ($call_type == 'get') {
            $res = $this->rudra_get_data($_POST);
        } elseif ($call_type == 'list' || $call_type == 'user-completed') {
            $res = $this->rudra_paged_data($call_type);
        } elseif ($call_type == 'view-detail') {
            $res = $this->viewBookingDetails($_POST);
        } elseif ($call_type == 'previous-jobs') {
            $res = $this->viewPreviousJobs($_POST);
        } elseif ($call_type == 'setting_list') {
            $res = $this->rudra_setting_list_data($_POST);
        } elseif ($call_type == 'delete') {
            $res = $this->rudra_delete_data($_POST);
        } elseif ($call_type == 'generate-payment-token') {
            $res = $this->generatePaymentCode($_POST);
        } elseif ($call_type == 'get-time-slots') {
            $res = $this->timeSlots($_POST);
        } elseif ($call_type == 'find-jobs') {
            $res = $this->findJobs($_POST);
        } elseif ($call_type == 'accept-wash' || $call_type == 'finish-wash') {
            $res = $this->acceptWash($call_type);
        } elseif ($call_type == 'chosen-extra-wash') {
            $res = $this->extraWashTypesList($_POST);
        } elseif ($call_type == 'qa-upload') {
            $res = $this->qualityAssuranceUpload($_POST);
        } elseif ($call_type == 'cancel') {
            $res = $this->cancelBooking($_POST);
        } elseif ($call_type == 'check_coupon') {
            $res = $this->check_coupon($_POST);
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function getExtraCharges($zip_code)
    {
        // $dark_green = ['2159', '2900', '2400', '2700', '2720', '2500', '2450'];
        // $medium_green = ['2100', '2200', '2300'];

        $extra = "0";
        // if ($zip_code != "") {
        //     if (in_array($zip_code, $dark_green)) {
        //         $extra = "70"; //dark green
        //     } elseif (($zip_code >= 1800 && $zip_code <= 2000) || in_array($zip_code, $medium_green)) {
        //         $extra = "50"; //medium green
        //     } elseif (($zip_code >= 1000 && $zip_code <= 1799)) {
        //         $extra = "30"; //light green
        //     }
        // }

        // $chk_data = $this->db->get_where("rudra_zipcodes", array('zipcode' => $zip_code))->row();
        $this->db->like('zipcode', $zip_code);
        $chk_data = $this->db->get('rudra_zipcodes');
        if ($chk_data->num_rows() > 0) {
            $res = $chk_data->row();
            $extra = $res->fee;
        }else{
            $this->db->like('zipcode', '-');
            $this->db->where('status', '1');
            $this->db->where('is_deleted', '0');
            $chk_data = $this->db->get('rudra_zipcodes');
            $result = $chk_data->result();

            if (!empty($result)) {
                foreach ($result as $res) {
                        $zip = explode(' ',$res->zipcode);
                        $zip = explode('-',$zip[0]);

                        for ($i = (int)$zip[0]; $i <= (int)$zip[1] ; $i++) { 
                            if($zip_code == $i){

                                $extra = $res->fee;
                            
                            }
                        }
                }
            }
        }
        return $extra;
    }

    public function calculateRates($zip_code, $wash_id, $ew_type, $lang, $seat_clean_count)
    {
        $return_data = [];
        /* extra_charges */
        $extra_charges = $this->getExtraCharges($zip_code);
        /* extra_charges */

        /* wash amount */
        $total_wash_amount = "0";
        $return_data['wash_type'] = [];
        if ($wash_id != "") {
            $this->db->where('status', "1");
            $this->db->where('is_deleted', "0");
            $this->db->where('id', $wash_id);
            $wash_type = $this->db->get("$this->table_wash_types");
            if ($wash_type->num_rows() > 0) {
                $wt = $wash_type->row();
                $wt_data["wash_id"] = (isset($wt->id) && $wt->id != "") ? $wt->id : "";
                $wt_data["wash_icon"] = (isset($wt->icon) && $wt->icon != "") ? base_url('app_assets/images/wash-icons/' . $wt->icon) : "";
                if($lang==1){
                    $wt_data["wash_name"] = (isset($wt->wash_name) && $wt->wash_name != "") ? $wt->wash_name : "";
                }else{
                    $wt_data["wash_name"] = (isset($wt->wash_name_dn) && $wt->wash_name_dn != "") ? $wt->wash_name_dn : "";
                }
                $total_wash_amount += $wt_data["wash_amount"] = (isset($wt->amount) && $wt->amount != "") ? $wt->amount : "";
                $return_data['wash_type'] = $wt_data;
            }
        }
        $return_data['total_wash_amount'] = strval(number_format($total_wash_amount, '2'));
        /* wash amount */

        /* extra washes */
        $total_extra_wash_amount = "0";
        $return_data['extra_wash_type'] = [];
        $ewt_data = [];
        if (is_array($ew_type) && !empty($ew_type)) {
            $this->db->where('status', "1");
            $this->db->where('is_deleted', "0");
            $this->db->where_in('id', $ew_type);
            $extra_wash_type = $this->db->get("$this->table_wash_extra_types");
            if ($extra_wash_type->num_rows() > 0) {
                
                foreach ($extra_wash_type->result() as $key => $ewt) {
                    $total_extra_wash_amount += $ewt->amount;
                    if($lang==1){
                       $extra_name = $ewt->extra_name;
                    }else{
                        $extra_name = $ewt->extra_name_dn;
                    }
                    $ewt_data[] =  ['extra_wash_id' => $ewt->id, 'extra_wash_extra_name' => $extra_name, 'extra_wash_amount' => $ewt->amount, 'is_seat_clean' => (int)$ewt->is_seat_clean];
                }
                
            }
        }


        
        if($seat_clean_count > 0){
            $total_extra_wash_amount += (89*$seat_clean_count);
            if($lang==1){
                $seat_name = "Seat cleaner (".$seat_clean_count.")";
            }else{
                $seat_name = "Sæderens (".$seat_clean_count.")";
            }
            $seat_amount = ($seat_clean_count * 89);
            $seat_amount = number_format((float)$seat_amount, 2, '.', '');
            $ewt_data[] = ['extra_wash_extra_name' => $seat_name, 'extra_wash_amount' => $seat_amount, 'is_seat_clean' => 1];
        }

        $return_data['extra_wash_type'] = $ewt_data;
        
        $return_data['total_extra_wash_amount'] = strval(number_format($total_extra_wash_amount, '2'));

        $net_amount = $total_wash_amount + $total_extra_wash_amount;
        $return_data['net_amount'] = strval(number_format($net_amount, '2'));
        $return_data['extra_charges'] = $extra_charges = strval(number_format($extra_charges, '2'));

        /* vat */
        $vat_percentage = $this->user_model->vat_percentage();
        $sub_total = $net_amount + $extra_charges;
        $return_data['vat_percentage'] = $vat_percentage;
        $vat_amount = ($sub_total * $vat_percentage) / 100;
        $return_data['vat'] = strval($vat_amount);
        /* vat */

        // $total = $sub_total + $vat_amount;
        $total = $sub_total;
        $return_data['total'] = strval(number_format($total, '2'));
        /* extra washes */

        return $return_data;
    }

    public function extraWashTypesList()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $wash_id = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";

                $return = [];
                if ($user_id != "" &&  $wash_id != "") {
                    $this->db->where('user_id', $user_id);
                    $this->db->where('id', $wash_id);
                    $bookings = $this->db->get($this->table);
                    if ($bookings->num_rows() > 0) {
                        $book = $bookings->row();

                        $info['extra_wash_type_ids'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? $book->extra_wash_type_ids : "";
                        $info['extra_wash_type_ids_array'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? json_decode($book->extra_wash_type_ids) : [];
                        $info['extra_wash_type_ids_definition'] = [];
                        if (is_array($info['extra_wash_type_ids_array']) && !empty($info['extra_wash_type_ids_array'])) {
                            $this->db->where_in('id', $info['extra_wash_type_ids_array']);
                            $extras = $this->db->get($this->table_wash_extra_types);
                            $added_extras =  [];
                            if ($extras->num_rows() > 0) {
                                foreach ($extras->result() as $key => $e) {
                                    if($lang==1){
                                        $extra_name = $e->extra_name;
                                     }else{
                                         $extra_name = $e->extra_name_dn;
                                     }
                                    $added_extras[] = ['id' => $e->id, 'name' => $extra_name, 'amount' => $e->amount, 'is_seat_clean' => (int)$e->is_seat_clean];
                                }
                                $return['extra_wash_type_ids_definition'] = $added_extras;
                            }
                        }
                    }
                }
                if (!empty($return)) {
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "extra_wash_rates_listed_successfully");
                } else {
                    $this->msg = getLangMessage($lang, $str = "no_washes_found");
                }
                $this->return_data = $return;
            }
        }
    }

    public function checkWashRates()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('wash_type_id', 'wash id', 'required');
            $this->form_validation->set_rules('extra_wash_type_ids', 'extra wash type ids', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();

                $zip_code = (isset($input['zipcode']) && $input['zipcode'] != "") ? strval($input['zipcode']) : "";
                $wash_id = (isset($input['wash_type_id']) && $input['wash_type_id'] != "") ? $input['wash_type_id'] : "";
                $extra_wash_type_ids = (isset($input['extra_wash_type_ids']) && $input['extra_wash_type_ids'] != "") ? $input['extra_wash_type_ids'] : "";
                $ew_type = [];
                if ($extra_wash_type_ids != "") {
                    $ew_type = json_decode($extra_wash_type_ids);
                }
             
                $seat_clean_count = (isset($input['seat_clean_count']) && $input['seat_clean_count'] != "") ? $input['seat_clean_count'] : 0;

                $return_data = $this->calculateRates($zip_code, $wash_id, $ew_type, $lang, $seat_clean_count);

                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "wash_rates_listed_successfully");
                $this->return_data = $return_data;
            }
        }
    }

    public function rudra_save_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('latitude', 'latitude', 'required');
            $this->form_validation->set_rules('longitude', 'longitude', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('registration_number_id', 'registration number id', 'required');
            $this->form_validation->set_rules('wash_type_id', 'wash type id', 'required');
            $this->form_validation->set_rules('extra_wash_type_ids', 'extra_wash_type_ids', 'required');
            $this->form_validation->set_rules('date', 'date', 'required');
            $this->form_validation->set_rules('time', 'time', 'required');
            // $this->form_validation->set_rules('notes', 'notes', 'required');
            $this->form_validation->set_rules('transaction_id', 'transaction id', 'required');
            $this->form_validation->set_rules('payment_method', 'payment method', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                //Insert Codes goes here 
                $insert_array['user_id'] = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";

                $insert_array['latitude'] = (isset($input['latitude']) && $input['latitude'] != "") ? $input['latitude'] : "";
                $insert_array['longitude'] = (isset($input['longitude']) && $input['longitude'] != "") ? $input['longitude'] : "";
                $insert_array['address'] = (isset($input['address']) && $input['address'] != "") ? $input['address'] : "";
                $insert_array['fk_car_reg_id'] = (isset($input['registration_number_id']) && $input['registration_number_id'] != "") ? $input['registration_number_id'] : "";

                $insert_array['wash_date'] = (isset($input['date']) && $input['date'] != "") ? $input['date'] : "";
                $insert_array['wash_time'] = (isset($input['time']) && $input['time'] != "") ? $input['time'] : "";
                $insert_array['notes'] = (isset($input['notes']) && $input['notes'] != "") ? $input['notes'] : "";
                $insert_array['transaction'] = (isset($input['transaction_id']) && $input['transaction_id'] != "") ? $input['transaction_id'] : "";
                $insert_array['pay_method'] = (isset($input['payment_method']) && $input['payment_method'] != "") ? $input['payment_method'] : "";
                // $insert_array['wash_status'] = (isset($input['wash_status']) && $input['wash_status'] != "") ? $input['wash_status'] : "";

                /* $insert_array['status'] = (isset($input['status']) && $input['status'] != "") ? $input['status'] : "";
                $insert_array['is_deleted'] = (isset($input['is_deleted']) && $input['is_deleted'] != "") ? $input['is_deleted'] : ""; */

                $zip_code = $insert_array['zipcode'] = (isset($input['zipcode']) && $input['zipcode'] != "") ? $input['zipcode'] : "";
                $insert_array['wash_id'] = $wash_id = (isset($input['wash_type_id']) && $input['wash_type_id'] != "") ? $input['wash_type_id'] : "";
                $insert_array['extra_wash_type_ids'] = $extra_wash_type_ids = (isset($input['extra_wash_type_ids']) && $input['extra_wash_type_ids'] != "") ? $input['extra_wash_type_ids'] : "";
                $ew_type = [];
                if ($extra_wash_type_ids != "") {
                    $ew_type = json_decode($extra_wash_type_ids);
                }

                $seat_clean_count = 0;
                $insert_array['seat_clean_count'] = $seat_clean_count = (isset($input['seat_clean_count']) && $input['seat_clean_count'] != "") ? $input['seat_clean_count'] : 0;

                $return_data = $this->calculateRates($zip_code, $wash_id, $ew_type, $lang, $seat_clean_count);
                $insert_array['wash_amount'] = (isset($return_data['total_wash_amount']) && $return_data['total_wash_amount'] != "") ? $return_data['total_wash_amount'] : "";
                $insert_array['extra_wash_amount'] = (isset($return_data['total_extra_wash_amount']) && $return_data['total_extra_wash_amount'] != "") ? $return_data['total_extra_wash_amount'] : "";
                $insert_array['vat_percentage'] = (isset($return_data['vat_percentage']) && $return_data['vat_percentage'] != "") ? $return_data['vat_percentage'] : "";
                $insert_array['vat'] = (isset($return_data['vat']) && $return_data['vat'] != "") ? $return_data['vat'] : "";
                $insert_array['extra_charges'] = (isset($return_data['extra_charges']) && $return_data['extra_charges'] != "") ? $return_data['extra_charges'] : "";
                $insert_array['total_pay'] = (isset($return_data['total']) && $return_data['total'] != "") ? $return_data['total'] : "";
                $insert_array['wash_type_json'] = (isset($return_data['wash_type']) && $return_data['wash_type'] != "") ? json_encode($return_data['wash_type']) : NULL;
                $insert_array['extra_wash_type_json'] = (isset($return_data['extra_wash_type']) && $return_data['extra_wash_type'] != "") ? json_encode($return_data['extra_wash_type']) : NULL;

                $insert_array['booking_id'] = $this->generateRandomString($length = 7);

                $insert_array['coupon_code'] = (isset($input['coupon_code']) && $input['coupon_code'] != "") ? $input['coupon_code'] : "";
                $insert_array['coupon_percent'] = (isset($input['coupon_percent']) && $input['coupon_percent'] != "") ? $input['coupon_percent'] : "";
                $insert_array['coupon_amount'] = (isset($input['coupon_amount']) && $input['coupon_amount'] != "") ? $input['coupon_amount'] : "";

                $datetime = new DateTime();
                $insert_array['created_at']  = $datetime->format( 'Y-m-d H:i:s' );
                $this->db->insert("$this->table", $insert_array);
                $new_id = $this->db->insert_id();
                $res = $this->bookingInfo($user_id = $insert_array['user_id'], $booking_id = $new_id, $page = "1",$filter_where = "", $washer_id = "",$lang);

                if($lang==1){
                    $messagestr = "Your booking #" . $insert_array['booking_id'] . "is created";
                }else{
                    $messagestr = "Din reservation #" . $insert_array['booking_id'] . "er oprettet";
                }

                sendPushNotifications($sending_ids = [strval($user_id)], $message = $messagestr, $userid=$user_id);

                $this->chk = 1;
                $this->user_model->updateNotifications($user_id, $message = "New booking #" . $insert_array['booking_id'] . " created", $message_dn = "Ny booking #" . $insert_array['booking_id'] . " oprettet");
                $this->msg = getLangMessage($lang, $str = "booking_completed_successfully");
                $this->return_data = $res;
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('latitude', 'latitude', 'required');
            $this->form_validation->set_rules('longitude', 'longitude', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('fk_car_reg_id', 'fk_car_reg_id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash_id', 'required');
            $this->form_validation->set_rules('extra_wash_type_ids', 'extra_wash_type_ids', 'required');
            $this->form_validation->set_rules('wash_date', 'wash_date', 'required');
            $this->form_validation->set_rules('wash_time', 'wash_time', 'required');
            $this->form_validation->set_rules('notes', 'notes', 'required');
            $this->form_validation->set_rules('wash_status', 'wash_status', 'required');
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
                            'user_id' => $this->input->post('user_id', true),
                            'zipcode' => $this->input->post('zipcode', true),
                            'latitude' => $this->input->post('latitude', true),
                            'longitude' => $this->input->post('longitude', true),
                            'address' => $this->input->post('address', true),
                            'fk_car_reg_id' => $this->input->post('fk_car_reg_id', true),
                            'wash_id' => $this->input->post('wash_id', true),
                            'extra_wash_type_ids' => $this->input->post('extra_wash_type_ids', true),
                            'wash_date' => $this->input->post('wash_date', true),
                            'wash_time' => $this->input->post('wash_time', true),
                            'notes' => $this->input->post('notes', true),
                            'wash_status' => $this->input->post('wash_status', true),
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

            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('latitude', 'latitude', 'required');
            $this->form_validation->set_rules('longitude', 'longitude', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('fk_car_reg_id', 'fk_car_reg_id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash_id', 'required');
            $this->form_validation->set_rules('extra_wash_type_ids', 'extra_wash_type_ids', 'required');
            $this->form_validation->set_rules('wash_date', 'wash_date', 'required');
            $this->form_validation->set_rules('wash_time', 'wash_time', 'required');
            $this->form_validation->set_rules('notes', 'notes', 'required');
            $this->form_validation->set_rules('wash_status', 'wash_status', 'required');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('is_deleted', 'is_deleted', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $pk_id = $this->input->post('id');
                $chk_data = $this->db->get_where("$this->table", array('id' => $pk_id))->row();
                if (!empty($chk_data)) {

                    // $this->db->where('id',$pk_id);
                    // $this->db->delete("$this->table");
                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "information_deleted");
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
            $this->form_validation->set_rules('id', 'ID', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
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
                    $this->msg = getLangMessage($lang, $str = "data");
                    $this->return_data = $res;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "record_not_found");
                }
            }
        }
    }

    public function rudra_paged_data($call_type)
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('page_number', 'Page number', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();

                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $wash_id = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";
                $page = (isset($input['page_number']) && $input['page_number'] != "") ? $input['page_number'] : "";
                /* filter */
                $from_date = (isset($input['from_date']) && $input['from_date'] != "") ? date('Y-m-d', strtotime($input['from_date'])) : "";
                $to_date = (isset($input['to_date']) && $input['to_date'] != "") ? date('Y-m-d', strtotime($input['to_date'])) : "";
                $vehicle_ids = (isset($input['vehicle_ids']) && $input['vehicle_ids'] != "") ? json_decode($input['vehicle_ids']) : [];
                /* filter */

                if ($call_type == "user-completed")
                    $filter_where = " AND `b`.`user_id` = '" . $user_id . "' AND `b`.`wash_status` = '3' ";
                else
                    $filter_where = " AND `b`.`user_id` = '" . $user_id . "' AND `b`.`wash_status` != '3' ";
                if (is_array($vehicle_ids) && !empty($vehicle_ids)) {
                    $ids = implode(",", $vehicle_ids);
                    $filter_where .= " AND `b`.`fk_car_reg_id` IN (" . $ids . ") ";
                }
                // if ($from_date != "" && $to_date != ""){
                //      $filter_where .= " AND (DATE(`b`.`created_at`) >= '" . $from_date . "' AND DATE(`b`.`created_at`) <= '" . $to_date . "') ";
                // }else{
                //     $filter_where .= " AND DATE(`b`.`created_at`) > '".date('Y-m-d')."' ";
                // }

                if ($from_date != "" && $to_date != "") {
                    $filter_where .= " AND (DATE(`b`.`wash_date`) >= '" . $from_date . "' AND DATE(`b`.`wash_date`) <= '" . $to_date . "') ";
                } else {
                    $filter_where .= " AND DATE(`b`.`wash_date`) >= '" . date('Y-m-d') . "' ";
                }

                $list = $this->bookingInfo($user_id, $booking_id = $wash_id, $page, $filter_where, $washer_id = "",$lang);

                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "booked_wash_listed_successfully");
                $this->return_data = $list;
            }
        }
    }

    public function findJobs()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            /* $this->form_validation->set_rules('latitude', 'latitude', 'required');
            $this->form_validation->set_rules('longitude', 'longitude', 'required'); */
            // $this->form_validation->set_rules('zipcode', 'zipcode', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                /* $latitude = (isset($input['latitude']) && $input['latitude'] != "") ? $input['latitude'] : "";
                $longitude = (isset($input['longitude']) && $input['longitude'] != "") ? $input['longitude'] : "";*/
                $zipcode = (isset($input['zipcode']) && $input['zipcode'] != "") ? $input['zipcode'] : "";
                // $date = (isset($input['date']) && $input['date'] != "") ? date('Y-m-d', strtotime($input['date'])) : "";
                $start_date = (isset($input['start_date']) && $input['start_date'] != "") ? date('Y-m-d', strtotime($input['start_date'])) : date('Y-m-d');
                $end_date = (isset($input['end_date']) && $input['end_date'] != "") ? date('Y-m-d', strtotime($input['end_date'])) : "";
                $time = (isset($input['time']) && $input['time'] != "") ? date('H:i:s', strtotime($input['time']))  : "";
                $wash_type = (isset($input['wash_type']) && $input['wash_type'] != "") ? $input['wash_type'] : "";

                /* $zip = $this->getNearestZipcode($zipcode, $miles = "50");
                $z = (!empty($zip)) ? $zip : [$zipcode]; */
                $page = (isset($input['page_number']) && $input['page_number'] != "") ? $input['page_number'] : "1";

                // $filter_where = " AND `b`.`zipcode` IN (" . implode(',', $z) . ") AND `b`.`wash_status` = '1' ";
                $filter_where = "";
                if ($zipcode != "") $filter_where .= " AND `b`.`zipcode` ='" . $zipcode . "' ";
                // if ($date != "") $filter_where .= " AND `b`.`wash_date` ='" . $date . "' ";
                if ($start_date != "") $filter_where .= " AND `b`.`wash_date` >='" . $start_date . "' ";
                if ($end_date != "") $filter_where .= " AND `b`.`wash_date` <='" . $end_date . "' ";
                if ($time != "") $filter_where .= " AND `b`.`wash_time` ='" . $time . "' ";
                if ($wash_type != "") $filter_where .= " AND `b`.`wash_id` ='" . $wash_type . "' ";

                $filter_where .= " AND `b`.`wash_status` = '1' ";
                $list = $this->bookingInfo($user_id = "", $booking_id = "", $page, $filter_where, $washer_id = "",$lang);
                if(!empty($list['bookings'])){
                    $this->chk = 1;
                }
                $this->msg = getLangMessage($lang, $str = "booked_wash_listed_successfully");
                $this->return_data = $list;
            }
        }
    }

    public function getNearestZipcode($zipcode, $miles = "50")
    {
        $zip = [];
        if ($zipcode != "") {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://app.zipcodebase.com/api/v1/radius?code=' . $zipcode . '&country=DK&unit=miles&radius=' . $miles);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            $headers = array();
            $headers[] = 'Apikey: 7d4aa010-84f3-11ec-80d5-1114b4245dc4';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                // echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $z = json_decode($result, true);

            if (is_array($z) && isset($z['results']) && !empty($z['results'])) {
                foreach ($z['results'] as $key => $value) {
                    $zip[] = (isset($value['code']) && $value['code'] != "") ? $value['code'] : "";
                }
            }
        }

        return $zip;
    }

    public function viewBookingDetails()
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
                $washer_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";

                $filter_where = " AND `b`.`wash_status` = '2' AND `b`.`wash_date` >= '".date('Y-m-d')."' ";
                $list = $this->bookingInfo($user_id = "", $booking_id = "", $page = "1", $filter_where, $washer_id,$lang);
                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "booked_wash_listed_successfully");
                $this->return_data = $list;
            }
        }
    }

    public function qualityAssuranceUpload()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('wash_id', 'wash id', 'required');
            // $this->form_validation->set_rules('', 'images', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                $booking_id = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";

                $path = "app_assets/images/qa_uploads/";
                $config = array(
                    'upload_path'   => $path,
                    'allowed_types' => 'jpg|jpeg|gif|png',
                    'overwrite'     => 1,
                );
                $this->load->library('upload', $config);

                $images = array();

                $files = isset($_FILES['images']) ? $_FILES['images'] : [];
                if (isset($_FILES['images']['name']) && count($_FILES['images']['name']) <= 10) {
                    foreach ($_FILES['images']['name'] as $key => $image) {
                        $_FILES['images']['name'] = $files['name'][$key];
                        $_FILES['images']['type'] = $files['type'][$key];
                        $_FILES['images']['tmp_name'] = $files['tmp_name'][$key];
                        $_FILES['images']['error'] = $files['error'][$key];
                        $_FILES['images']['size'] = $files['size'][$key];

                        $fileName =  $booking_id . '_' . $image;
                        $config['file_name'] = $fileName;
                        $this->upload->initialize($config);

                        if ($this->upload->do_upload('images')) {
                            $this->upload->data();
                            $images[] = ['fk_booking_id' => $booking_id, 'file_name' => $fileName, 'file_path' => $path];
                        }
                    }

                    $update = false;
                    if (!empty($images)) {
                        $update = $this->db->insert_batch($this->table_qa_uploads, $images);
                    }

                    if ($update) {
                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "quality_assurance_files_uploaded_successfully");
                    } else {
                        $this->msg = getLangMessage($lang, $str = "problem_occurred_while_uploading_please_try_again_later");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "maximum_file_upload_is_10");
                }
            }
        }
    }

    public function acceptWash($call_type)
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $booking_id = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";

                $check_bookings = [];
                if ($call_type == "finish-wash")
                    $check_bookings = $this->user_model->checkWashAccepted($id = (int)$booking_id, $washer_id = (int)$user_id);
                elseif ($call_type == "accept-wash")
                    $check_bookings = $this->user_model->checkBookingExists((int)$booking_id);


                if (!empty($check_bookings)) {
                    /* update array */
                    if ($call_type == "accept-wash")
                        $update_array['washer_id'] = $user_id;

                    $update_array['wash_status'] = ($call_type == "finish-wash") ? "3" : "2";
                    /* update array */
                    $this->db->where(['id' => $booking_id]);
                    $update = $this->db->update('rudra_booking', $update_array);

                    if ($call_type == "finish-wash") {
                        $path = "app_assets/images/qa_uploads/";
                        $config = array(
                            'upload_path'   => $path,
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'overwrite'     => 1,
                        );
                        $this->load->library('upload', $config);

                        $images = array();
                        $files = isset($_FILES['images']) ? $_FILES['images'] : [];
                        if (isset($_FILES['images']['name']) && count($_FILES['images']['name']) <= 5) {
                            foreach ($_FILES['images']['name'] as $key => $image) {
                                $_FILES['images']['name'] = $files['name'][$key];
                                $_FILES['images']['type'] = $files['type'][$key];
                                $_FILES['images']['tmp_name'] = $files['tmp_name'][$key];
                                $_FILES['images']['error'] = $files['error'][$key];
                                $_FILES['images']['size'] = $files['size'][$key];

                                $fileName =  $booking_id . '_' . $image;
                                $config['file_name'] = $fileName;
                                $this->upload->initialize($config);

                                if ($this->upload->do_upload('images')) {
                                    $this->upload->data();
                                    $images[] = ['fk_booking_id' => $booking_id, 'file_name' => $fileName, 'file_path' => $path];
                                }
                            }

                            // $update = false;
                            if (!empty($images)) {
                                $file_update = $this->db->insert_batch($this->table_qa_uploads, $images);
                            }

                            
                        }
                        
                    }

                    if ($call_type == "accept-wash"){
                        if($lang==1){
                            $messagestr = "Your wash for booking ID #".$check_bookings->booking_id." has been accepted";
                            $messagestr2 = "You accepted wash for booking ID #".$check_bookings->booking_id;
                        }else{
                            $messagestr = "Din vask til booking-id #".$check_bookings->booking_id." er blevet accepteret";
                            $messagestr2 = "Du accepterede vask til booking-id #".$check_bookings->booking_id;
                        }
                        sendPushNotifications($sending_ids = [strval($check_bookings->user_id)], $message = $messagestr, $userid=$check_bookings->user_id);
                        sendPushNotifications($sending_ids = [strval($user_id)], $message = $messagestr2, $userid=$user_id);
                    }

                    if ($update) {
                        $this->chk = 1;
                        $this->msg = ($call_type == "accept-wash") ? getLangMessage($lang, $str = "wash_accepted_successfully") : getLangMessage($lang, $str = "wash_finished_successfully");
                        $this->return_data = [];
                    } else {
                        $this->msg = getLangMessage($lang, $str = 'failed_to_update_wash_please_try_again_later');
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "no_records_found");
                }
            }
        }
    }

    public function viewPreviousJobs()
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
                $input = $this->input->post();
                $selected_date = (isset($input['selected_date']) && $input['selected_date'] != "") ? date('Y-m-d', strtotime($input['selected_date'])) : "";
                $user_id = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $start_time = (isset($input['start_time']) && $input['start_time'] != "") ? $input['start_time'] : "";
                $end_time = (isset($input['end_time']) && $input['end_time'] != "") ? $input['end_time'] : "";
                $package = (isset($input['package']) && $input['package'] != "") ? $input['package'] : "";
                $page = (isset($input['page_number']) && $input['page_number'] != "") ? $input['page_number'] : "1";

                $filter_where = " AND `b`.`wash_status` = '3' AND `b`.`washer_id` = '" . $user_id . "' ";
                if ($package != "") $filter_where .= " AND `b`.`wash_id` = '" . $package . "' ";
                if ($selected_date != "") $filter_where .= " AND DATE(`b`.`created_at`) = '" . $selected_date . "' ";
                if ($start_time != "" && $end_time != "") $filter_where .= " AND (TIME(`b`.`wash_time`) >= '" . $start_time . "' AND TIME(`b`.`wash_time`) <= '" . $end_time . "') ";
                $list = $this->bookingInfo($user_id = "", $booking_id = "", $page, $filter_where, $washer_id = "",$lang);

                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "booked_wash_listed_successfully");
                $this->return_data = $list;
            }
        }
    }

    public function generateRandomString($length = 7)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function bookingInfo($user_id = "", $booking_id = "", $page = "1", $filter_where = "", $washer_id = "", $lang ="1")
    {
        $limit = "25";
        $start = "0";
        if ($page > 1) $start = ($page - 1) * $limit;

        $where = "WHERE `b`.`status` = '1' AND `b`.`is_deleted` = '0' ";

        if ($user_id != "") $where .= " AND `b`.`user_id` = '" . $user_id . "' ";

        if ($washer_id != "") $where .= " AND `b`.`washer_id` = '" . $washer_id . "' ";

        if ($booking_id != "") $where .= " AND `b`.`id` = '" . $booking_id . "' ";

        if ($filter_where != "") $where .= " " . $filter_where . " ";

        $sql = "SELECT `b`.*, `reg_cars`.`registration_number` `registered_car_number`,  `reg_cars`.`name` `car_name`,  `reg_cars`.`brand` `car_brand`,  `reg_cars`.`model` `car_model`, `reg_cars`.`color` `car_color`, `reg_cars`.`car_size`, `reg_cars`.`description` `car_description`, `wt`.`wash_name` `wash_type_name`, `wt`.`wash_name_dn` `wash_type_name_dn`, u.name, u.contact_number FROM `rudra_booking` AS `b` LEFT JOIN `rudra_user_registered_car_numbers` AS `reg_cars` ON `b`.`fk_car_reg_id` = `reg_cars`.`id` LEFT JOIN `rudra_wash_types` AS `wt` ON `b`.`wash_id` = `wt`.`id` LEFT JOIN `rudra_user` `u` ON `b`.`user_id` = `u`.`id` " . $where . " ORDER BY `b`.`created_at` DESC LIMIT $start, $limit";
        $bookings = $this->db->query($sql);

        $sql1 = "SELECT `b`.*, `reg_cars`.`registration_number` `registered_car_number`,  `reg_cars`.`name` `car_name`,  `reg_cars`.`brand` `car_brand`,  `reg_cars`.`model` `car_model`, `reg_cars`.`color` `car_color`, `reg_cars`.`car_size`, `reg_cars`.`description` `car_description`, `wt`.`wash_name` `wash_type_name`, `wt`.`wash_name_dn` `wash_type_name_dn` FROM `rudra_booking` AS `b` LEFT JOIN `rudra_user_registered_car_numbers` AS `reg_cars` ON `b`.`fk_car_reg_id` = `reg_cars`.`id` LEFT JOIN `rudra_wash_types` AS `wt` ON `b`.`wash_id` = `wt`.`id` LEFT JOIN `rudra_user` `u` ON `b`.`user_id` = `u`.`id` " . $where . " ORDER BY `b`.`created_at` DESC";
        $bookings_count = $this->db->query($sql1);
        $total_result_count = $bookings_count->num_rows();

        $return['bookings'] = [];
        $wash_status = ['1' => getLangMessage($lang, $str = "pending"), '2' => getLangMessage($lang, $str = "approved"), '3' => getLangMessage($lang, $str = "done")];
        if ($bookings->num_rows() > 0) {
            foreach ($bookings->result() as $key => $book) {
                $info['wash_id'] = (isset($book->id) && $book->id != "") ? $book->id : "";
                $info['user_id'] = (isset($book->user_id) && $book->user_id != "") ? $book->user_id : "";
                $info['user_name'] = (isset($book->name) && $book->name != "") ? $book->name : "";
                $info['phone'] = (isset($book->contact_number) && $book->contact_number != "") ? $book->contact_number : "";
                $info['booking_id'] = (isset($book->booking_id) && $book->booking_id != "") ? "#" . $book->booking_id : "";
                $info['zipcode'] = (isset($book->zipcode) && $book->zipcode != "") ? $book->zipcode : "";
                $info['latitude'] = (isset($book->latitude) && $book->latitude != "") ? $book->latitude : "";
                $info['longitude'] = (isset($book->longitude) && $book->longitude != "") ? $book->longitude : "";
                $info['address'] = (isset($book->address) && $book->address != "") ? $book->address : "";

                /* vehicle info */
                $info['car_id'] = (isset($book->fk_car_reg_id) && $book->fk_car_reg_id != "") ? $book->fk_car_reg_id : "";
                $info['car_number'] = (isset($book->registered_car_number) && $book->registered_car_number != "") ? $book->registered_car_number : "";
                $info['car_name'] = (isset($book->car_name) && $book->car_name != "") ? $book->car_name : "";
                $info['car_brand'] = (isset($book->car_brand) && $book->car_brand != "") ? $book->car_brand : "";
                $info['car_model'] = (isset($book->car_model) && $book->car_model != "") ? $book->car_model : "";
                $info['car_color'] = (isset($book->car_color) && $book->car_color != "") ? $book->car_color : "";
                $info['car_description'] = (isset($book->car_description) && $book->car_description != "") ? $book->car_description : "";
                $info['car_size'] = (isset($book->car_size) && $book->car_size != "") ? $book->car_size : "";
                $info['car_size_definition'] = (isset($book->car_size) && $book->car_size != "") ? CarSizeType($id = $book->car_size) : "";
                // $info['vehicle_info'] = $vehicle_info;
                /* vehicle info */

                $info['wash_type_id'] = (isset($book->wash_id) && $book->wash_id != "") ? $book->wash_id : "";
                if($lang==1){
                    $info['wash_type_name'] = (isset($book->wash_type_name) && $book->wash_type_name != "") ? $book->wash_type_name : "";
                }else{
                    $info['wash_type_name'] = (isset($book->wash_type_name_dn) && $book->wash_type_name_dn != "") ? $book->wash_type_name_dn : "";
                }
                $info['extra_wash_type_ids'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? $book->extra_wash_type_ids : "";
                $info['extra_wash_type_ids_array'] = (isset($book->extra_wash_type_ids) && $book->extra_wash_type_ids != "") ? json_decode($book->extra_wash_type_ids) : [];
                $info['extra_wash_type_ids_definition'] = [];
                $added_extras =  [];
                if (is_array($info['extra_wash_type_ids_array']) && !empty($info['extra_wash_type_ids_array'])) {
                    $this->db->where_in('id', $info['extra_wash_type_ids_array']);
                    $extras = $this->db->get($this->table_wash_extra_types);
                    
                    if ($extras->num_rows() > 0) {
                        foreach ($extras->result() as $key => $e) {
                            if($lang==1){
                                $extra_name = $e->extra_name;
                             }else{
                                 $extra_name = $e->extra_name_dn;
                             }
                            $added_extras[] = ['id' => $e->id, 'name' => $extra_name, 'amount' => $e->amount, 'is_seat_clean' => (int)$e->is_seat_clean];
                        }
                        
                    }
                }
                $seatc = (int)$book->seat_clean_count;
                if($seatc > 0){
                    if($lang==1){
                        $seat_name = "Seat cleaner (".$seatc.")";
                    }else{
                        $seat_name = "Sæderens (".$seatc.")";
                    }
                    $seat_amount = ($seatc * 89);
                    $seat_amount = number_format((float)$seat_amount, 2, '.', '');
                    $added_extras[] = ['name' => $seat_name, 'amount' => $seat_amount, 'is_seat_clean' => 1];
                }
                $info['extra_wash_type_ids_definition'] = $added_extras;
                $info['seat_clean_count'] = (isset($book->seat_clean_count) && $book->seat_clean_count != "") ? $book->seat_clean_count : "";
                $info['wash_date'] = (isset($book->wash_date) && $book->wash_date != "") ? $book->wash_date : "";
                // $info['wash_date_a'] = (isset($book->wash_date) && $book->wash_date != "") ? date('F d, Y', strtotime($book->wash_date)) : "";
                $info['wash_date_a'] = (isset($book->wash_date) && $book->wash_date != "") ? date('Y-m-d', strtotime($book->wash_date)) : "";
                $info['wash_time'] = (isset($book->wash_time) && $book->wash_time != "") ? $book->wash_time : "";
                $info['wash_time_a'] = (isset($book->wash_time) && $book->wash_time != "") ? date('H:i', strtotime($book->wash_time)) : "";
                $info['notes'] = (isset($book->notes) && $book->notes != "") ? $book->notes : "";
                $info['wash_status_id'] = (isset($book->wash_status) && $book->wash_status != "") ? $book->wash_status : "";
                $info['wash_status_definition'] = (isset($wash_status[$book->wash_status]) && $wash_status[$book->wash_status] != "") ? $wash_status[$book->wash_status] : getLangMessage($lang, $str = "pending");
                $info['extra_charges'] = (isset($book->extra_charges) && $book->extra_charges != "") ? $book->extra_charges : "";
                $info['vat'] = (isset($book->vat) && $book->vat != "") ? $book->vat : "";
                $info['vat_percentage'] = (isset($book->vat_percentage) && $book->vat_percentage != "") ? $book->vat_percentage : "";
                // $info['wash_type_json'] = (isset($book->wash_type_json) && $book->wash_type_json != "") ? $book->wash_type_json : "";
                $info['wash_amount'] = (isset($book->wash_amount) && $book->wash_amount != "") ? $book->wash_amount : "";
                // $info['extra_wash_type_json'] = (isset($book->extra_wash_type_json) && $book->extra_wash_type_json != "") ? $book->extra_wash_type_json : "";
                $info['extra_wash_amount'] = (isset($book->extra_wash_amount) && $book->extra_wash_amount != "") ? $book->extra_wash_amount : "";
                $info['total_pay'] = (isset($book->total_pay) && $book->total_pay != "") ? $book->total_pay : "";
                // $info['status'] = (isset($book->status) && $book->status != "") ? $book->status : "";
                // $info['is_deleted'] = (isset($book->is_deleted) && $book->is_deleted != "") ? $book->is_deleted : "";
                // $info['created_at'] = (isset($book->created_at) && $book->created_at != "") ? date('F d, Y', strtotime($book->created_at)) : "";
                $info['created_at'] = (isset($book->created_at) && $book->created_at != "") ? date('Y-m-d', strtotime($book->created_at)) : "";
                // $info['updated_at'] = (isset($book->updated_at) && $book->updated_at != "") ? $book->updated_at : "";

                $info['comments'] = [];
                $this->db->where(['status' => '1', 'is_deleted' => '0', 'fk_wash_id' => $info['wash_id']]);
                $wash_comments = $this->db->get($this->table_wash_comments);
                if ($wash_comments->num_rows() > 0) {
                    $comments = [];
                    foreach ($wash_comments->result() as $key => $c) {
                        $commented_by = ($user_id == $c->fk_user_id) ? "You" : "Washer";
                        $comments[] = ['comment_id' => $c->id, 'commented_by' => $commented_by, 'comment' => $c->comment];
                    }
                    $info['comments'] = $comments;
                }


                /* qa_uploads */
                $this->db->where(['fk_booking_id' => $info['wash_id'], 'status' => '1', 'is_deleted' => '0']);
                $qa_uploads = $this->db->get($this->table_qa_uploads);
                $info['qa_uploads'] = [];
                if ($qa_uploads->num_rows() > 0) {
                    foreach ($qa_uploads->result() as $key => $qa) {
                        $info['qa_uploads'][] = base_url($qa->file_path . $qa->file_name);
                    }
                }
                /* qa_uploads */

                $return['bookings'][] = $info;
            }
        }

        $total_pages = ceil($total_result_count / $limit);
        $return['total_pages_available'] =  strval($total_pages);
        $return['current_page'] = $page;
        $return['results_per_page'] = $limit;

        return $return;
    }

    public function generatePaymentCode()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'id', 'required');
            $this->form_validation->set_rules('amount', 'amount', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->security->xss_clean($this->input->post());
                /* required */
                $user_id = (isset($input['user_id']) && $input['user_id'] != "" && is_numeric(($input['user_id']))) ? $input['user_id'] : "";
                $amount = (isset($input['amount']) && $input['amount'] != "" && is_numeric(($input['amount']))) ? $input['amount'] * 100 : "";
                if ($user_id != "" && $amount != "" && $amount > 0) {
                    $this->db->where('id', $user_id);
                    $user_info = $this->db->get($this->table_user);
                    if ($user_info->num_rows() > 0) {
                        $user = $user_info->row();
                        require APPPATH . 'third_party/vendor/autoload.php';
                        \Stripe\Stripe::setApiKey('sk_test_51KJN90EN1JJG4aRLbwq2AJwxhwvkMVwbD55bhjiEdotnVZa6MdihitYkAP1MW5QmuIP1yzDxlxvD3XRZExEQiUc100GEuzVAJD');
                        // Use an existing Customer ID if this is a returning customer.
                        $customer = \Stripe\Customer::create(
                            [
                                'name' => $user->name,
                                'address' => [
                                    'line1' => $user->address,
                                    'postal_code' => '',
                                    'city' => $user->device_city,
                                    'state' => '',
                                    'country' => $user->device_country,
                                ],
                            ]
                        );

                        $ephemeralKey = \Stripe\EphemeralKey::create(
                            ['customer' => $customer->id],
                            ['stripe_version' => '2020-08-27']
                        );

                        $paymentIntent = \Stripe\PaymentIntent::create([
                            'amount' => $amount,
                            'currency' => 'usd',
                            'description' => 'Car Wash Bookings',
                            'customer' => $customer->id,
                            'automatic_payment_methods' => [
                                'enabled' => 'true',
                            ]
                        ]);

                        $paymentIntent->amount = $paymentIntent->amount / 100;
                        $result = array(
                            'paymentIntent' => $paymentIntent->client_secret,
                            'ephemeralKey' => $ephemeralKey->secret,
                            'customer' => $customer->id,
                            'payment_method_types' => ['card'],
                            'publishableKey' => 'pk_test_51KJN90EN1JJG4aRLiyznrwZE91SFIk8d7JU5TCEeeN7iW8v6tkzjbpEgd5LMgF30wJIbcoRQ0RiHzONOL3QxBUlJ00TFn0dxHk'
                        );

                        $this->chk = 1;
                        $this->msg = getLangMessage($lang, $str = "token_generate_successfully");
                        $this->return_data = $result;
                    } else {
                        $this->msg = getLangMessage($lang, $str = "user_not_found");
                    }
                } else {
                    $this->msg = $this->param_on_null;
                }
            }
        } else {
            $this->msg = getLangMessage($lang, $str = "");
            $this->invalid_request;
        }
        // $this->json_output(200, array('status' => 200, 'message' => $this->msg, getLangMessage($lang, $str="");'api_status' => $this->check, 'data' => $this->return_data));
    }

    public function timeSlots()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('date', 'date', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->security->xss_clean($this->input->post());
                $date_input = (isset($input['date']) && $input['date'] != "") ? date('d-m-Y', strtotime($input['date'])) : date('d-m-Y');

                $start_date = date('Y-m-d', strtotime("previous sunday"));
                $end_date = date('Y-m-d', strtotime("next saturday"));

                $return = [];
                // $week_days = $this->getDatesFromRange($start_date, $end_date);
                $week_days = [$date_input];

                $future = $past = [];
                foreach ($week_days as $key => $date) {
                    $timings['date'] = $date;
                    if (date('d-m-Y', strtotime($date)) >= date('d-m-Y')) {
                        // $future[] = $date;
                        $timings['date_flag'] = "1";
                    } else {
                        // $past[] = $date;
                        $timings['date_flag'] = "0";
                    }
                    // $slots = $this->getTimeSlot($interval = "120", $date = date('d-m-Y', strtotime($date)));
                    $slots = $this->getTimeSlot($interval = "120", $start_time = "08:00", $end_time = "20:00", $date = date('d-m-Y', strtotime($date)));
                    $timings['slots'] = $slots;

                    $return[] = $timings;
                }
                $this->chk = 1;
                $this->msg = getLangMessage($lang, $str = "slot_timings_listed_successfully");
                $this->return_data = $return;
            }
        }
    }

    public function getDatesFromRange($start, $end, $format = 'd-m-Y')
    {
        // Declare an empty array
        $array = array();
        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        // Use loop to store date into array
        foreach ($period as $date) {
            $array[] = $date->format($format);
        }
        // Return the array elements
        return $array;
    }

    public function getTimeSlot($interval, $start_time, $end_time, $date)
    {
        $start = new DateTime($start_time);
        $a = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $time = [];
        while (strtotime($startTime) <= strtotime($endTime)) {
            $start = $startTime;
            $end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
            $startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));

            // $cur_t = '13:00';
            $new_date = new DateTime('2021-12-29');
            $new_date->setTime(14, 55);
            $current_time = date('H:i');

            if (strtotime($startTime) <= strtotime($endTime)) {
                $flag = 0;
                if (strtotime(date('d-m-Y')) < strtotime($date)) {
                    $flag = 1;
                } else if (strtotime(date('d-m-Y')) == strtotime($date)) {
                    if ($start > $current_time) {
                        $flag = 1;
                    }
                }

                $time[] = [
                    'start' => $start,
                    'end' => $end,
                    'flag' => strval($flag),
                    'dk_now' => $current_time
                    /* 'c1' => date('d-m-Y'),
                    'g1' => $date,
                    'c' => strtotime(date('d-m-Y')),
                    'g' => strtotime($date),
                    'now' => $cur_t */
                ];
            }
        }

        return $time;
    }

    public function cancelBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('booking_id', 'booking id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->security->xss_clean($this->input->post());
                $user_id = (isset($input['user_id']) && $input['user_id'] != "" && is_numeric(($input['user_id']))) ? $input['user_id'] : "";
                $booking_id = (isset($input['booking_id']) && $input['booking_id'] != "" && is_numeric(($input['booking_id']))) ? $input['booking_id'] : "";

                $check_user = $this->db->query("SELECT `id`, `user_type` FROM `rudra_user` WHERE `status` = '1' AND `is_deleted` = '0' AND `id` = '" . $user_id . "';");
                if ($check_user->num_rows() > 0) {
                    $user = $check_user->row();

                    $check_wash = $this->db->query("SELECT * FROM `rudra_booking` WHERE `id` = '" . $booking_id . "' AND ( `user_id` = '" . $user->id . "' OR `washer_id` = '" . $user->id . "' ) AND `status` = '1' AND `is_deleted` = '0' AND `wash_status` != '3' AND `cancellation` = '0'");
                    if ($check_wash->num_rows() > 0) {
                        $wash = $check_wash->row();
                        if (isset($user->user_type) && $user->user_type == "2") {
                            $washdate = (int)strtotime($wash->wash_date." ".$wash->wash_time);
                            $current = (int)strtotime(date("Y-m-d H:i:s"));
                            $timediff = ($washdate-$current);
                            if ($timediff > (2*86400)) {
                                $this->db->where(['id' => $booking_id]);
                                $update = $this->db->update('rudra_booking', ['wash_status' => '1']);

                                // $updateArray =
                                //         array(
                                //         'wash_status' => '1',
                                //         'washer_id' => "" );
                                // $this->db->where('id', $booking_id);
                                // $this->db->update('rudra_booking', $updateArray);      

                                $this->db->set('washer_id', null);
                                $this->db->where('id', $booking_id);
                                $this->db->update('rudra_booking');

                                if ($update) {
                                    $this->msg = getLangMessage($lang, $str = "booking_cancelled_successfully");
                                    $this->chk = 1;
                                    if($lang==1){
                                        $messagestr = "Your booking ID: #" . $wash->booking_id . " has been cancelled by the washer. Wash is back to pending now.";
                                        $messagestr2 = "Your booking ID: #" . $wash->booking_id . " has been cancelled successfully.";
                                    }else{
                                        $messagestr = "Dit booking ID: #" . $wash->booking_id . " din reservation er blevet annulleret af vaskemaskinen. Vask er tilbage til afventende nu.";
                                        $messagestr2 = "Dit booking ID: #". $wash->booking_id ." er blevet annulleret.";
                                    }
                                    sendPushNotifications($sending_ids = [strval($wash->user_id)], $message = $messagestr, $userid=$wash->user_id);
                                    sendPushNotifications($sending_ids = [strval($user->id)], $message = $messagestr2, $userid=$user->id);
                                } else {
                                    $this->msg = getLangMessage($lang, $str = "problem_occurred_while_cancelling_booking_please_try_again_later");
                                }
                            } else {
                                $this->msg = getLangMessage($lang, $str = "the_wash_cannot_be_cancelled_as_there_is_less_than_48_hours_to_go_if_you_want_to_cancel_the_wash_anyway_please_contact_us_by_email_or_phone");
                            }
                        } else {
                            $washdate = (int)strtotime($wash->wash_date." ".$wash->wash_time);
                            $current = (int)strtotime(date("Y-m-d H:i:s"));
                            $timediff = ($washdate-$current);
                            if ($timediff > 86400) {
                                $this->db->where(['id' => $booking_id]);
                                $update = $this->db->update('rudra_booking', ['is_deleted' => '1', 'status' => '0', 'cancellation' => '1']);
                                $this->db->set('washer_id', null);
                                $this->db->where('id', $booking_id);
                                $this->db->update('rudra_booking');
                                if ($update) {
                                    $this->msg = getLangMessage($lang, $str = "booking_cancelled_successfully");
                                    $this->chk = 1;
                                    if($lang==1){
                                        $messagestr = "User has cancelled your wash for the booking ID: #" . $wash->booking_id;
                                        $messagestr2 = "Your booking ID: #" . $wash->booking_id . " has been cancelled successfully.";
                                    }else{
                                        $messagestr = "Brugeren har annulleret din vask til booking ID: #" . $wash->booking_id;
                                        $messagestr2 = "Dit booking ID: #". $wash->booking_id ." er blevet annulleret.";
                                    }
                                    sendPushNotifications($sending_ids = [strval($wash->washer_id)], $message = $messagestr, $userid=$wash->washer_id);
                                    sendPushNotifications($sending_ids = [strval($user->id)], $message = $messagestr2, $userid=$user->id);
                                } else {
                                    $this->msg = getLangMessage($lang, $str = "problem_occurred_while_cancelling_booking_please_try_again_later");
                                }
                            } else {
                                $this->msg = getLangMessage($lang, $str = "the_wash_cannot_be_cancelled_as_there_is_less_than_24_hours_to_go_if_you_want_to_cancel_the_wash_anyway_please_contact_us_by_email_or_phone");
                            }
                        }
                    } else {
                        $this->msg = getLangMessage($lang, $str = "wash_not_found_cancelled");
                    }
                } else {
                    $this->msg = getLangMessage($lang, $str = "user_not_found");
                }
            }
        }
    }

    public function check_coupon()
    {

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'User ID', 'required');
            $this->form_validation->set_rules('coupon_code', 'Coupon code', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id');
                $coupon_code = $this->input->post('coupon_code');
                $std = date('Y-m-d H:i:s');
                $res = $this->db->get_where("rudra_coupons", array('code' => $coupon_code, 'status' => '1', 'is_deleted' => '0'))->row();

                $info['coupon_flag'] = '0';
                $info['offer_percent'] = '0';
                // $info['offer_amount'] = '0';
                if (!empty($res)) {
                    $info['offer_percent'] = $res->offered;
                    // $wash = $this->db->get_where("rudra_wash_types", array('id' => $wash_id, 'status'=>'1', 'is_deleted'=>'0'))->row();

                    // if(!empty($wash)){

                    //     $amount = $wash->amount;
                    //     $info['offer_amount'] = $amount - ($amount * ( $res->offered / 100));
                    //     $info['offer_amount'] = (string) $info['offer_amount'];
                    // }

                    if ($res->expiry >= $std) {
                        $result = $this->db->get_where("$this->table", array('coupon_code' => $coupon_code, 'user_id' => $user_id, 'status' => '1', 'is_deleted' => '0'))->row();
                        if (empty($result)) {
                            $info['coupon_flag'] = '1';
                            $this->chk = 1;
                            $this->msg = getLangMessage($lang, $str = "data_found");
                        } else {
                            $this->chk = 0;
                            $this->msg = getLangMessage($lang, $str = "coupon_has_already_been_used");
                        }
                    } else {
                        $this->msg = getLangMessage($lang, $str = "coupon_expired");
                    }

                    // $this->chk = 1;
                    // $this->msg =getLangMessage($lang, $str=""); 'Data';
                    $this->return_data = $info;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "invalid_coupon");
                }
            }
        }
    }
}
