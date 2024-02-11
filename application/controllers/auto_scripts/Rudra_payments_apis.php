<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rudra_payments_apis extends CI_Controller
{
    private $api_status = false;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->bdp = $this->db->dbprefix;
        $this->table = 'rudra_payments';
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
     
     //rudra_payments API Routes
	$t_name = 'auto_scripts/Rudra_payments_apis/';    
	$route[$api_ver.'payments/(:any)'] = $t_name.'rudra_rudra_payments/$1';

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

    public function rudra_rudra_payments($param1)
    {
        $call_type = $param1;
        $res = array();
        if ($call_type == 'confirmation') {
            $res = $this->rudra_save_data($_POST);
        } elseif ($call_type == 'update') {
            $res = $this->rudra_update_data($_POST);
        } elseif ($call_type == 'view-receipt') {
            $res = $this->rudra_get_data($_POST);
        } elseif ($call_type == 'list') {
            $res = $this->rudra_paged_data($_POST);
        } elseif ($call_type == 'setting_list') {
            $res = $this->rudra_setting_list_data($_POST);
        } elseif ($call_type == 'delete') {
            $res = $this->rudra_delete_data($_POST);
        } elseif ($call_type == 'get_transaction_id') {
            $res = $this->rudra_get_transaction_id($_POST);
        } elseif ($call_type == 'retrieve_payment') {
            $res = $this->retrieve_payment($_POST);
        } elseif ($call_type == 'mobilepay_init') {
            $res = $this->initiate($_POST);
        } elseif ($call_type == 'mobilepay_status') {
            $res = $this->status($_POST);
        } elseif ($call_type == 'mobilepay_capture') {
            $res = $this->mobilepay_capture($_POST);
        } elseif ($call_type == 'mobilepay_reserve') {
            $res = $this->mobilepay_reserve($_POST);
        }

        $this->json_output(200, array('status' => 200, 'message' => $this->msg, 'data' => $this->return_data, 'chk' => $this->chk));
    }

    public function rudra_save_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('user_id', 'user id', 'required');
            $this->form_validation->set_rules('wash_id', 'wash id', 'required');
            $this->form_validation->set_rules('pay_type', 'pay_type', 'required');
            $this->form_validation->set_rules('paid', 'paid', 'required');
            $this->form_validation->set_rules('bank_account_id', 'bank account id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $input = $this->input->post();
                $ins_array['fk_user_id'] = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                $ins_array['fk_booking_id'] = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";
                $ins_array['pay_type'] = (isset($input['pay_type']) && $input['pay_type'] != "") ? $input['pay_type'] : "";
                $ins_array['paid'] = (isset($input['paid']) && $input['paid'] != "") ? $input['paid'] : "";
                $ins_array['fk_bank_account_id'] = (isset($input['bank_account_id']) && $input['bank_account_id'] != "") ? $input['bank_account_id'] : "";
                /* transaction */
                $ins_array['transaction_id'] = (isset($input['transaction_id']) && $input['transaction_id'] != "") ? $input['transaction_id'] : NULL;
                $ins_array['request'] = (isset($input['request']) && $input['request'] != "") ? $input['request'] : NULL;
                /* transaction */

                /* $this->db->where(['fk_booking_id' => $ins_array['fk_booking_id']]);
                $check = $this->db->get($this->table);
                if ($check->num_rows() == 0) {
                } */
                $datetime = new DateTime();
                $ins_array['created_at']  = $datetime->format( 'Y-m-d H:i:s' );

                $this->db->insert($this->table, $ins_array);
                $new_id = $this->db->insert_id();
                if ($new_id) {
                    // $res = $this->db->get_where("$this->table", array('id' => $new_id))->row();
                    $this->chk = 1;
                    $this->user_model->updateNotifications($user_id = $ins_array['fk_user_id'], $message = "Booking payment completed.", $message_dn = "Booking betaling gennemført.");
                    $this->msg = getLangMessage($lang, $str = "payment_history_stored_successfully");
                } else {
                    $this->msg = getLangMessage($lang, $str = "failed_to_store_payment_history");
                }
            }
        }
    }

    public function rudra_update_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');

            $this->form_validation->set_rules('id', 'id', 'required');
            $this->form_validation->set_rules('fk_booking_id', 'fk_booking_id', 'required');
            $this->form_validation->set_rules('date_time', 'date_time', 'required');
            $this->form_validation->set_rules('pay_type', 'pay_type', 'required');
            $this->form_validation->set_rules('paid', 'paid', 'required');
            $this->form_validation->set_rules('fk_bank_account_id', 'fk_bank_account_id', 'required');
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
                            'fk_booking_id' => $this->input->post('fk_booking_id', true),
                            'date_time' => $this->input->post('date_time', true),
                            'pay_type' => $this->input->post('pay_type', true),
                            'paid' => $this->input->post('paid', true),
                            'fk_bank_account_id' => $this->input->post('fk_bank_account_id', true),
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

            $this->form_validation->set_rules('fk_booking_id', 'fk_booking_id', 'required');
            $this->form_validation->set_rules('date_time', 'date_time', 'required');
            $this->form_validation->set_rules('pay_type', 'pay_type', 'required');
            $this->form_validation->set_rules('paid', 'paid', 'required');
            $this->form_validation->set_rules('fk_bank_account_id', 'fk_bank_account_id', 'required');
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
            $this->form_validation->set_rules('pay_id', 'ID', 'required');
            $this->form_validation->set_rules('booking_id', 'Booking id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = getLangMessage($lang, $str = "input_error_please_check_params");
                $this->return_data = $this->form_validation->error_array();
            } else {
                $pk_id = $this->input->post('pay_id', true);
                $booking_id = $this->input->post('booking_id', true);
                $result = $this->db->query("SELECT `rp`.*, `rb`.`booking_id` `rb_booking_id`, `rb`.`fk_car_reg_id`, `rb`.`extra_charges`, `rb`.`wash_amount`, `rb`.`extra_wash_amount`, `rb`.`total_pay`, `rb`.`wash_date`, `rb`.`wash_time`, `rb`.`wash_status`, `rb`.`vat_percentage`, `rb`.`vat`, `rb`.`address`, `rb`.`zipcode`, `rcn`.`registration_number`, `rcn`.`name` `car_name`, `rcn`.`brand` `car_brand`, `rcn`.`model` `car_model`, `rcn`.`color` `car_color`, `rcn`.`description` `car_description`, `u`.`city`, `u`.`email` `user_email`, `u`.`name` `user_name` FROM `rudra_payments` `rp` LEFT JOIN `rudra_booking` `rb` ON `rp`.`fk_booking_id` = `rb`.`id` LEFT JOIN `rudra_user_registered_car_numbers` `rcn` ON `rb`.`fk_car_reg_id` = `rcn`.`id` LEFT JOIN `rudra_washer_bank_info` `b` ON `rp`.`fk_bank_account_id` = `b`.`id`  LEFT JOIN `rudra_user` `u` ON `rb`.`user_id` = `u`.`id` WHERE `rp`.`id` = '" . $pk_id . "' AND `rp`.`fk_booking_id` = '" . $booking_id . "' AND `rp`.`status` = '1' AND `rp`.`is_deleted` = '0';");

                if ($result->num_rows() > 0) {
                    $p = $result->row();
                    $pay['pay_id'] = (isset($p->id) && $p->id != "") ? $p->id : "";
                    $pay['booking_id'] = (isset($p->fk_booking_id) && $p->fk_booking_id != "") ? $p->fk_booking_id : "";
                    $pay['booking_id_definition'] = (isset($p->fk_booking_id) && $p->fk_booking_id != "") ? '#' . $p->rb_booking_id : "";
                    $pay['date'] = (isset($p->wash_date) && $p->wash_date != "") ? date('d,M Y', strtotime($p->wash_date)) : "";
                    $pay['time'] = (isset($p->wash_time) && $p->wash_time != "") ? date('h:i A', strtotime($p->wash_time)) : "";
                    $pay['reg_id'] = (isset($p->fk_car_reg_id) && $p->fk_car_reg_id != "") ? $p->fk_car_reg_id : "";
                    $pay['reg_id_definition'] = (isset($p->registration_number) && $p->registration_number != "") ? $p->registration_number : "";
                    $pay['car_name'] = (isset($p->car_name) && $p->car_name != "") ? $p->car_name : "";
                    $pay['car_brand'] = (isset($p->car_brand) && $p->car_brand != "") ? $p->car_brand : "";
                    $pay['car_model'] = (isset($p->car_model) && $p->car_model != "") ? $p->car_model : "";
                    $pay['car_color'] = (isset($p->car_color) && $p->car_color != "") ? $p->car_color : "";
                    $pay['car_description'] = (isset($p->car_description) && $p->car_description != "") ? $p->car_description : "";

                    $pay['wash_amount'] =  (isset($p->wash_amount) && $p->wash_amount != "") ? $p->wash_amount : "0.00";
                    $pay['extra_wash_amount'] = (isset($p->extra_wash_amount) && $p->extra_wash_amount != "") ? $p->extra_wash_amount : "0.00";
                    $pay['extra_charges'] = (isset($p->extra_charges) && $p->extra_charges != "") ? $p->extra_charges : "0.00";
                    $wash_amount = $pay['wash_amount'] + $pay['extra_wash_amount'] + $pay['extra_charges'];
                    $pay['vat_amount'] = (isset($p->vat) && $p->vat != "") ? $p->vat : "0.00";
                    $pay['total_pay'] = (isset($p->total_pay) && $p->total_pay != "") ? $p->total_pay : "";
                    $pay['address'] = (isset($p->address) && $p->address != "") ? $p->address : "";
                    $pay['city'] = (isset($p->city) && $p->city != "") ? $p->city : "";
                    $pay['zipcode'] = (isset($p->zipcode) && $p->zipcode != "") ? $p->zipcode : "";
                    $pay['user_email'] = (isset($p->user_email) && $p->user_email != "") ? $p->user_email : "";
                    $pay['user_name'] = (isset($p->user_name) && $p->user_name != "") ? $p->user_name : "";

                    /* $pay['invoice'] = '<table cellpadding="1" cellspacing="1" style="width:500px"> <tbody> <tr> <th colspan="2">Booking ID : ' . $pay['booking_id'] . '</th> </tr> <tr> <td>' . $pay['date'] . '</td> <td>3:38 pm</td> </tr> <tr> <td> ' . $pay['address'] . '<br /> ' . $pay['city'] . '<br />' . $pay['zipcode'] . '</td> <td></td> </tr> <tr> <td colspan="2">1 Car</td> </tr> <tr> <td colspan="2">' . $pay['reg_id_definition'] . '</td> </tr> <tr> <th colspan="2"><strong>Total</strong></th> </tr> <tr> <td>Wash Amount</td> <td>' . $wash_amount . '</td> </tr> <tr> <td>VAT</td> <td>' . $pay['extra_charges_vat'] . '</td> </tr> <tr> <td>Total</td> <td>' . $pay['total_pay'] . '</td> </tr> </tbody> </table>'; */

                    /* sending invoice email */
                    $send_mail = $this->input->post('send_mail');
                    if ($send_mail == "1") {
                        $this->sendInvoice($pay);
                    }
                    /* sending invoice email */

                    $this->chk = 1;
                    $this->msg = getLangMessage($lang, $str = "receipt_data_listed_successfully");
                    $this->return_data = $pay;
                } else {
                    $this->chk = 0;
                    $this->msg = getLangMessage($lang, $str = "record_not_found");
                }
            }
        }
    }

    public function sendInvoice($pay)
    {
        if (isset($pay['user_email']) && $pay['user_email'] != "") {
            $this->load->library('email');

            $from_email = "monkyshine@team.com";
            // $to_email = "webodevmern@gmail.com";
            $to_email = $pay['user_email'];

            $booking_id = (isset($pay['booking_id_definition']) && $pay['booking_id_definition'] != "") ? $pay['booking_id_definition'] : "";
            $subject = "Hi " . $pay['user_name'] . "! Your invoice for booking ID - " . $booking_id;

            $body = $this->load->view('invoice', $pay, true);

            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );

            $this->email->initialize($config);

            $this->email->to($to_email);
            $this->email->from($from_email, "Monkyshine Team");
            $this->email->subject($subject);
            $this->email->message($body);
            $mail = $this->email->send();
        }
    }

    public function rudra_paged_data()
    {
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            //$this->form_validation->set_rules('page_number', 'Page Number: default 1', 'required');
            
            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id', true);
                $page = $this->input->post('page_number', true);
                $limit = "25";
                $start = "0";
                if ($page > 1) $start = ($page - 1) * $limit;

                $result = $this->db->query("SELECT `rp`.*, `rb`.`booking_id` `rb_booking_id`, `rb`.`fk_car_reg_id`, `rcn`.`registration_number` FROM `rudra_payments` `rp` LEFT JOIN `rudra_booking` `rb` ON `rp`.`fk_booking_id` = `rb`.`id` LEFT JOIN `rudra_user_registered_car_numbers` `rcn` ON `rb`.`fk_car_reg_id` = `rcn`.`id` LEFT JOIN `rudra_washer_bank_info` `b` ON `rp`.`fk_bank_account_id` = `b`.`id` WHERE `rp`.`fk_user_id` = '" . $user_id . "' AND `rp`.`status` = '1' AND `rp`.`is_deleted` = '0' ORDER BY `rp`.`id` DESC LIMIT " . $start . ", " . $limit . "; ");

                $result_count = $this->db->query("SELECT `rp`.*, `rb`.`booking_id` `rb_booking_id`, `rb`.`fk_car_reg_id`, `rcn`.`registration_number` FROM `rudra_payments` `rp` LEFT JOIN `rudra_booking` `rb` ON `rp`.`fk_booking_id` = `rb`.`id` LEFT JOIN `rudra_user_registered_car_numbers` `rcn` ON `rb`.`fk_car_reg_id` = `rcn`.`id` LEFT JOIN `rudra_washer_bank_info` `b` ON `rp`.`fk_bank_account_id` = `b`.`id` WHERE `rp`.`fk_user_id` = '" . $user_id . "' AND `rp`.`status` = '1' AND `rp`.`is_deleted` = '0' ORDER BY `rp`.`id` DESC; ");
                $total_result_count = $result_count->num_rows();

                $list['payments'] = array();
                if ($result->num_rows() > 0) {
                    $payment = $result->result();
                    foreach ($payment as $res) {
                        $data['pay_id'] = $res->id;
                        $data['user_id'] = $res->fk_user_id;
                        $data['booking_id'] = $res->fk_booking_id;
                        $data['booking_id_definition'] = '#' . $res->rb_booking_id;
                        $data['reg_no'] = $res->fk_car_reg_id;
                        $data['reg_no_definition'] = $res->registration_number;
                        $data['date'] = date('d-m-Y', strtotime($res->date_time));
                        $data['time'] = date('h:i:s A', strtotime($res->date_time));
                        $data['amount'] = $res->paid;
                        $data['pay_type'] = ($res->pay_type == "1") ? getLangMessage($lang, $str = "credit_card") : "venmo";

                        $list['payments'][] = $data;
                    }
                }

                $total_pages = ceil($total_result_count / $limit);
                $list['total_pages_available'] =  strval($total_pages);
                $list['current_page'] = $page;
                $list['results_per_page'] = $limit;

                $this->chk = 1;
                $this->msg = 'Payments history listed successfully';
                $this->return_data = $list;
            }
        }
    }


    public function rudra_get_transaction_id(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('user_id', 'User Id', 'required');
            // $this->form_validation->set_rules('wash_id', 'Booking wash Id', 'required');
            $this->form_validation->set_rules('amount', 'Total amount', 'required');
            
            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {

                $user_id = $this->input->post('user_id', true);
                // $booking_id = $this->input->post('wash_id', true);
                $amount = $this->input->post('amount', true)*100;

                
                // $transaction_id =  time().uniqid(mt_rand(),true);
                $payload = array (
                    'checkout' => 
                    array (
                      'integrationType' => 'EmbeddedCheckout',
                      'url' => base_url().'payment/checkout',
                      'termsUrl' => base_url().'terms-conditions',
                    ),
                    'order' => 
                    array (
                      'items' => 
                      array (
                        0 => 
                        array (
                          'reference' => 'ref_'.$user_id,
                          'name' => 'Car wash',
                          'quantity' => 1,
                          'unit' => 'hours',
                          'unitPrice' => $amount,
                          'grossTotalAmount' => $amount,
                          'netTotalAmount' => $amount,
                        ),
                      ),
                      'amount' => $amount,
                      'currency' => 'DKK',
                      'reference' => 'Wash Order',
                    ),
                );
                $payload = json_encode($payload);
                // $payload = file_get_contents('payload.json');
                assert(json_decode($payload) && json_last_error() == JSON_ERROR_NONE);

                $ch = curl_init('https://api.dibspayment.eu/v1/payments');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
                        'Content-Type: application/json',
                        'Accept: application/json',
                        // 'Authorization: test-secret-key-a40713b2b4394379bb039699cb77a41d'
                        'Authorization: live-secret-key-4df1a783981c4c12b09155f640ae3e12'
                    ));                                                
                $result = curl_exec($ch);
                $result= json_decode($result);
                
                $transaction_id = $result->paymentId;


                // $ins_array['fk_user_id'] = (isset($input['user_id']) && $input['user_id'] != "") ? $input['user_id'] : "";
                // $ins_array['fk_booking_id'] = (isset($input['wash_id']) && $input['wash_id'] != "") ? $input['wash_id'] : "";
                // // $ins_array['pay_type'] = (isset($input['pay_type']) && $input['pay_type'] != "") ? $input['pay_type'] : "";
                // $ins_array['paid'] = (isset($input['amount']) && $input['amount'] != "") ? $input['amount'] : "";
                // // $ins_array['fk_bank_account_id'] = (isset($input['bank_account_id']) && $input['bank_account_id'] != "") ? $input['bank_account_id'] : "";
                // /* transaction */
                // $ins_array['transaction_id'] = $transaction_id;
                // // $ins_array['request'] = (isset($input['request']) && $input['request'] != "") ? $input['request'] : NULL;
                // /* transaction */
                // $ins_array['status'] = '0';
                
                // $this->db->insert($this->table, $ins_array);


                $this->return_data = array('transaction_id' => $transaction_id);

                $this->chk = 1;
                $this->msg = 'Transaction Id created';

            }

        }
    }

    public function retrieve_payment(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('transaction_id', 'Payment transaction Id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {

                $transaction_id = $this->input->post('transaction_id', true);

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://api.dibspayment.eu/v1/payments/".$transaction_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                    // "Authorization: test-secret-key-a40713b2b4394379bb039699cb77a41d",
                    'Authorization: live-secret-key-4df1a783981c4c12b09155f640ae3e12',
                    "CommercePlatformTag: Monkyshine"
                    ],
                ]);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);
                
                if ($err) {
                    // echo "cURL Error #:" . $err;
                    $this->chk = 0;
                    $this->msg = 'Payment failed';

                } else {

                    // $updateArray =
                    //     array(
                    //         'status' => '1',
                    //     );

                    // $this->db->where('transaction_id', $transaction_id);
                    // $this->db->update("$this->table", $updateArray);

                    // echo $response;
                    $this->chk = 1;
                    $this->msg = 'Payment successful';
                    $this->return_data = json_decode($response);
                }

            }
    
        }
    }


    public function initiate(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('amount', 'amount', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $amount = $input['amount'] * 100;
                $guid = $this->GUID();
                $reference = $this->GUID();
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.mobilepay.dk/v1/paymentpoints?pageNumber=1&pageSize=100',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'x-ibm-client-id: 4f105750-3d1c-4367-b2ec-b30b9c83fb78',
                    'accept: application/json',
                    'content-type: application/json',
                    // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
                    'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
                ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                // echo $response;
                $response = json_decode($response);
                // print_r($response);

                if(isset($response->paymentPoints[0]->paymentPointId)){
                    $paymentPointId = $response->paymentPoints[0]->paymentPointId;
                    // exit;



                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.mobilepay.dk/v1/payments',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{"amount":'.$amount.',
                    "description":"Car wash.",
                    "paymentPointId":"'.$paymentPointId.'",
                    "reference":"'.$reference.'",
                    "idempotencyKey":"'.$guid.'",
                    "redirectUri":"monkyapp://easyauth.callback"
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'X-IBM-Client-Id: 4f105750-3d1c-4367-b2ec-b30b9c83fb78',
                        'accept: application/json',
                        'content-type: application/json',
                        // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
                        'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    // $response= json_decode($response);
                    // print_r($response);

                    $this->chk = 1;
                    $this->msg = 'Payment data';
                    $this->return_data = json_decode($response);

                }
                

            }


        }

    }

    public function status(){
        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('payment_id', 'payment_id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $payment_id = $input['payment_id'];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.mobilepay.dk/v1/payments/'.$payment_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-IBM-Client-Id: 4f105750-3d1c-4367-b2ec-b30b9c83fb78',
                    'accept: application/json',
                    // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
                    'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
                ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                $response = json_decode($response);
                if(isset($response->paymentId)){

                    $this->chk = 1;
                    $this->msg = 'Payment data';
                    $this->return_data = $response;


                }else{
                    $this->msg = 'Payment data not found';

                }
            }
        }

    }

    public function mobilepay_capture(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('payment_id', 'payment_id', 'required');
            $this->form_validation->set_rules('amount', 'amount', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $payment_id = $input['payment_id'];
                $amount = $input['amount'] * 100;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.mobilepay.dk/v1/payments/'.$payment_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-IBM-Client-Id: 4f105750-3d1c-4367-b2ec-b30b9c83fb78',
                    'accept: application/json',
                    // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
                    'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
                ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                $response = json_decode($response);
                if(isset($response)){

                    if($response->state == 'reserved'){
                
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.mobilepay.dk/v1/payments/'.$payment_id.'/capture',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                            "amount": '.$amount.'
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
                            'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
                        ),
                        ));

                        $response = curl_exec($curl);

                        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);

                        if ($httpcode == 204){

                            $this->chk = 1;
                            $this->msg = 'Betalingen er gennemført.';
                            $this->return_data = $response;

                        }else{
                            $this->chk = 0;
                            $this->msg = 'Noget gik galt.';
                        }
                    }else{
                        $this->chk = 0;
                        $this->msg = 'Noget gik galt.';
                    }

                }else{
                    $this->chk = 0;
                    $this->msg = 'Noget gik galt.';
                }
            }

        }
    }


    public function mobilepay_reserve(){

        if (!empty($_POST)) {
            $this->form_validation->set_rules('api_key', 'API KEY', 'required');
            $this->form_validation->set_rules('payment_id', 'payment_id', 'required');

            $input = $this->input->post();
            $lang = (isset($input['language']) && $input['language'] != "") ? $input['language'] : "1";

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Input Error, Please check Params';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $payment_id = $input['payment_id'];

            //     $curl = curl_init();

            //     curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://api.mobilepay.dk/v1/integration-test/payments/'.$payment_id.'/reserve',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS =>'{
            //     "paymentSourceId": "aa4fabd5-c6ab-41f5-a59d-099ec5076655",
            //     "userId": "59e637e6-1484-4c99-9afa-63ee972951f4"
            //     }',
            //     CURLOPT_HTTPHEADER => array(
            //         'Content-Type: application/json',
            //         // 'Authorization: Bearer A28B30B250BC62C77266D6B3914129FC4A81695086B5213C00D4C786BE9F9A3D'
            //         'Authorization: Bearer 3C2D3B87AA50CF54C63B47CB218C3CC3293562C1B77E62DFB0C0347ABA62A359'
            //     ),
            //     ));
                
            //     $response = curl_exec($curl);
            //     $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            //     curl_close($curl);
            //     // echo $response;
            //     if ($httpcode == 204){

            //         $this->chk = 1;
            //         $this->msg = 'Payment successfully reserved.';
            //         $this->return_data = $response;

            //     }else{
            //         $this->chk = 0;
            //         $this->msg = 'Something went wrong.';
            //     }
            }
        }
    }

    public function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
    
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}
