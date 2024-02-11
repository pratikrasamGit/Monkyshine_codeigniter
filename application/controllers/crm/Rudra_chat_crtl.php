<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rudra_chat_crtl extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->bdp = $this->db->dbprefix;
        $this->full_table = 'rudra_chat';
        $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
        //$this->set_data();
        $this->base_id = 0;
        $this->load->model('crudmaster_model', 'crd');
        $this->load->model('rudra_chat_rudra_model', 'rudram');
        // Uncomment Following Codes for Session Check and change accordingly
        /*
        if (!$this->session->userdata('rudra_admin_logged_in'))
        {
            $this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
            $return_url = uri_string();
            redirect(base_url("admin-login?req_uri=$return_url"), 'refresh');
        }
        else
        {
            $this->admin_id = $this->session->userdata('rudra_admin_id');
            $this->return_data = array('status' => false, 'msg' => 'Working', 'login' => true, 'data' => array());
        }
        */
    }

    /***********
	//Rudra_chat_crtl ROUTES
        $crud_master = $crm . "Rudra_chat_crtl/";
        $route['rudra_chat'] = $crud_master . 'index';
        $route['rudra_chat/index'] = $crud_master . 'index';
        $route['rudra_chat/list'] = $crud_master . 'list';
        $route['rudra_chat/post_actions/(:any)'] = $crud_master.'post_actions/$1';
     **************/

    public function index()
    {
        // main index codes goes here
        $data['pageTitle'] = ' Chat';
        $data['page_template'] = '_rudra_chat';
        $data['page_header'] = ' Chat';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function list()
    {
        // main index codes goes here
        //Creating Cols Array used for Ordering the table: ASC and Descending Order
        //If you change the Columns(of DataTable), please change here too
        $orderArray = array('id', 'fk_from_id', 'fk_to_id', 'message', 'status', 'is_deleted',);
        $limit = html_escape($this->input->post('length'));
        $start = html_escape($this->input->post('start'));
        $order = '';
        $dir   = $this->input->post('order')[0]['dir'];
        $order   = $this->input->post('order')[0]['column'];
        $orderColumn = $orderArray[$order];
        $general_search = $this->input->post('search')['value'];
        $filter_data['general_search'] = $general_search;
        $totalData = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $totalData; //Initailly Total and Filtered count will be same
        $rescheck = '';
        $rows = $this->rudram->get_table_data($limit, $start, $orderColumn, $dir, $filter_data, $rescheck, $this->full_table);
        $rows_count = $this->rudram->count_table_data($filter_data, $this->full_table);
        $totalFiltered = $rows_count;
        if (!empty($rows)) {
            $res_json = array();
            foreach ($rows as $row) {
                $actions_base_url = 'rudra_chat/post_actions';
                $actions_query_string = "?id= $row->id.'&target_table='.$row->id";
                $form_data_url = 'rudra_chat/post_actions';
                $action_url = 'rudra_chat/post_actions';
                $sucess_badge =  "class=\'badge badge-success\'";
                $danger_badge =  "class=\'badge badge-danger\'";
                $info_badge =  "class=\'badge badge-info\'";
                $warning_badge =  "class=\'badge badge-warning\'";

                /* $actions_button = "<a id='edt$row->id' href='javascript:void(0)' class='label label-success text-white f-12' onclick =\"static_form_modal('$form_data_url/get_data?id=$row->id','$action_url/update_data','md','Update Details')\" >Edit</a>"; */
                $actions_button = '<a href="chat?id=' . $row->id . '" id="" class= "label label-primary f-12" href="#" role="button">Chat</a>';

                $row->actions = $actions_button;

                //JOINS LOGIC

                $fk_from_id = $this->db->get_where('rudra_user', array('id' => $row->fk_from_id))->row();
                $row->fk_from_id = (!empty($fk_from_id) ? $fk_from_id->id : '--');
                $fk_to_id = $this->db->get_where('rudra_user', array('id' => $row->fk_to_id))->row();
                $row->fk_to_id = (!empty($fk_to_id) ? $fk_to_id->id : '--');

                $row->status = (isset($row->status) && $row->status != "") ? statusDropdown($id = $row->status) : "";
                $row->is_deleted = (isset($row->is_deleted) && $row->is_deleted != "") ? statusDropdown($id = $row->is_deleted) : "";
                $data[] = $row;
            }
        } else {
            $data = array();
        }
        $json_data = array(
            'draw'           => intval($this->input->post('draw')),
            'recordsTotal'    => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data'           => $data
        );
        echo json_encode($json_data);
    }

    public function chat()
    {
        $data['pageTitle'] = ' Chat';
        $data['page_template'] = '_rudra_chat_detail';
        $data['page_header'] = ' Chat';
        $data['load_type'] = 'all';

        $data['user_id'] = (isset($_GET['id']) && $_GET['id'] != "" && is_numeric($_GET['id'])) ? $_GET['id'] : "";

        if($data['user_id']){
            $updateArray = array('is_read'=>'1');

            $this->db->where('fk_to_id', $this->session->userdata('rudra_admin_id') );
            $this->db->where('fk_from_id', $data['user_id']);
            $this->db->update($this->full_table, $updateArray);
        }

        $this->db->where(['id' => $data['user_id']]);
		$user_info = $this->db->get('rudra_user');
		$data['username'] = "User";
		if ($user_info->num_rows() > 0) {
			$user = $user_info->row();
			$data['username'] = $user->name.' '.$user->surname;
		}
        
        $this->load->view('crm/_rudra_chat_detail', $data);
        // $this->load->view('crm/forms/_ajax_rudra_chat_form', $data = [], FALSE);
    }

    public function saveRecord()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->form_validation->set_rules('user_id', 'user id', 'required');
            // $this->form_validation->set_rules('fk_to_id', 'fk_to_id', 'required');

            if (empty($_FILES))
                $this->form_validation->set_rules('message', 'message', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->chk = 0;
                $this->msg = 'Your message looks empty';
                $this->return_data = $this->form_validation->error_array();
            } else {
                $user_id = $this->input->post('user_id', true);
                $insert_array['fk_from_id'] = "1";
                $insert_array['fk_to_id'] = $user_id;
                $insert_array['message'] = $this->input->post('message');

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

                        $fileName = $new_id . '_' . $image;
                        $config['file_name'] = $fileName;

                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('images[]')) {
                            $images['fk_chat_id'] = $new_id;
                            $images['file_name'] = $fileName;
                            $upload_insert[] = $images;
                        } else {
                            // return false;
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
                //Format Data if required
                /*********
                 $res->added_on_custom_date = date('d-M-Y',strtotime($res->added_at));
                 $res->added_on_custom_time = date('H:i:s A',strtotime($res->added_at));
                 $res->updated_on_custom_date = date('d-M-Y',strtotime($res->updated_at));
                 $res->updated_on_custom_time = date('H:i:s A',strtotime($res->updated_at));
                 ************/
                $this->chk = 1;
                $this->msg = 'Message stored successfully';
                $this->return_data = $list;
            }
        }
    }

    public function getChatList()
    {
        $this->load->model('Rudra_user_rudra_model', 'user_model');

        $user_id = (isset($_GET['user']) && $_GET['user'] != "") ? $_GET['user'] : "";
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? $_GET['page'] : "1";
        $list = $this->user_model->chatData($user_id, $page, $chat_id = "", $order = "DESC");
        $admin_id = $this->session->userdata('rudra_admin_id');

        $message = "";
        if (isset($list['chat']) && !empty($list['chat'])) {
            /* array_reverse($list['chat']);
            array_reverse($list['chat']); */
            krsort($list['chat']);
            foreach ($list['chat'] as $key => $c) {
                $u_images = $a_images = "";
                if (isset($c['uploads']) && !empty($c['uploads'])) {
                    $u_img = $a_img = "";
                    foreach ($c['uploads'] as $key => $i) {
                        if($c['from_admin'] == '1'){
                            $a_images .= '<a href="' . $i . '" target="_blank"><img class="" src="' . $i . '" style="height:100px; width:100px;" alt="admin uploads" /></a>';
                        }else{
                            if ($c['from_id'] == $user_id) $u_images .= '<a href="' . $i . '" target="_blank"><img class="" src="' . $i . '" style="height:100px; width:100px;" alt="user uploads" /></a>';
                            else $a_images .= '<a href="' . $i . '" target="_blank"><img class="" src="' . $i . '" style="height:100px; width:100px;" alt="admin uploads" /></a>';
                        }
                    }
                    /* $u_images = $u_img;
                    $a_images = $a_img; */
                }

                if($c['from_admin'] == '1'){

                    $message .= '<div class="media chat-messages">
                                    <div class="media-body chat-menu-reply">';
                    if($c['message']){  
                        $message .= '<div class="">
                                <p class="chat-cont">' . $c['message'] . '</p>
                            </div>';
                    }
                    $message .= '<p class="chat-time">' . $a_images . '</p>
                                    <p class="chat-time">' . $c['created_at'] . '</p>
                                </div>
                            </div>';


                }else{
                    if ($c['from_id'] == $user_id) {
                        $message .= '<div class="media chat-messages">
                                        <a class="media-left photo-table" href="javascript:"><img class="media-object img-radius img-radius m-t-5" src="' . $c['from_logo'] . '" alt="' . $c['from_name'] . '" /></a>
                                        <div class="media-body chat-menu-content">';
                                            
                            if($c['message']){  
                                $message .= '<div class="">
                                        <p class="chat-cont">' . $c['message'] . '</p>
                                    </div>';
                            }
                            $message .= '<p class="chat-time">' . $u_images . '</p>
                                            <p class="chat-time">' . $c['created_at'] . '</p>
                                        </div>
                                    </div>';
                    } else {
                        $message .= '<div class="media chat-messages">
                                        <div class="media-body chat-menu-reply">';
                            if($c['message']){  
                                $message .= '<div class="">
                                        <p class="chat-cont">' . $c['message'] . '</p>
                                    </div>';
                            }
                            $message .= '<p class="chat-time">' . $a_images . '</p>
                                            <p class="chat-time">' . $c['created_at'] . '</p>
                                        </div>
                                    </div>';
                    }
                }
            }
        }

        echo $message;
    }

    public function post_actions($param1)
    {
        // main index codes goes here
        $action = (isset($_GET['act']) ? $_GET['act'] : $param1);
        $id = (isset($_GET['id']) ? $_GET['id'] : 0);
        $this->return_data['status'] = true;
        $col_json = $this->db->get_where($this->bdp . 'crud_master_tables', array('tbl_name' => $this->full_table))->row();
        $data['col_json'] = $col_json;
        $json_cols = json_decode($data['col_json']->col_strc);
        //Get Data Methods
        if ($action == "get_data") {
            $data['id'] = $id;
            foreach ($json_cols as $ck => $cv) {
                if ($cv->form_type == 'ddl') {
                    $data[$cv->col_name] = $cv->ddl_options;
                }

                //Foreign Key Logics
                if ($cv->f_key) {
                    $ref_table_name = $cv->ref_table;
                    $data[$cv->col_name] = $this->db->get($ref_table_name)->result();
                }
            }

            $data['form_data'] = $this->db->get_where($this->full_table, array('id' => $id))->row();
            $html = $this->load->view("crm/forms/_ajax_rudra_chat_form", $data, TRUE);
            $this->return_data['data']['form_data'] = $html;
        }
        // Post Methods
        //Update Codes
        if ($action == "update_data") {
            if (!empty($_POST)) {
                $id = $_POST['id'];
                $updateArray =
                    array(
                        'id' => $this->input->post('id', true),
                        'fk_from_id' => $this->input->post('fk_from_id', true),
                        'fk_to_id' => $this->input->post('fk_to_id', true),
                        'message' => $this->input->post('message', true),
                        'status' => $this->input->post('status', true),
                        'is_deleted' => $this->input->post('is_deleted', true),
                    );

                $this->db->where('id', $id);
                $this->db->update($this->full_table, $updateArray);
            }
        }
        //Insert Method
        if ($action == "insert_data") {
            if (!empty($_POST)) {
                $post = json_decode($_POST['post'], true);
                //Insert Codes goes here 
                $insert['fk_from_id'] = "1";
                $insert['from_admin'] = "1";
                $insert['fk_to_id'] = isset($post['fk_to_id']) ? $post['fk_to_id'] : "";
                $insert['message'] = isset($post['message']) ? $post['message'] : "";
                // date_default_timezone_set ( 'Europe/Copenhagen' );
                $datetime = new DateTime();
                $insert['created_at']  = $datetime->format( 'Y-m-d H:i:s' );

                $this->db->insert($this->full_table, $insert);
                $new_id = $this->db->insert_id();

                $check_user = $this->db->query("SELECT `id`, `user_type` FROM `rudra_user` WHERE `status` = '1' AND `is_deleted` = '0' AND `id` = '" . $post['fk_to_id'] . "';");
                if ($check_user->num_rows() > 0 && $post['message']) {
                    $user = $check_user->row();

                    $messagestr = "Message from Admin\n".$post['message'];
                    sendPushNotifications($sending_ids = [strval($insert['fk_to_id'])], $message = $messagestr, $userid=$insert['fk_to_id']);

                }

                if ($new_id == true && isset($_FILES['files']) && !empty($_FILES['files'])) {
                    /* chat upload */
                    $path = "app_assets/images/chat/";
                    $config = array(
                        'upload_path'   => $path,
                        'allowed_types' => 'jpg|jpeg|gif|png',
                        'overwrite'     => 1,
                    );
                    $this->load->library('upload', $config);

                    $upload_insert = array();
                    $files = $_FILES['files'];
                    $imgmessage = '';
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

                            $imagePath = base_url('app_assets/images/chat/' . $fileName);
                            $imgmessage .= "<img src='".$imagePath."' style='width:20px' >\n";
                                                            
                        } else {
                            // return false;
                            $a = $this->upload->display_errors();
                        }
                    }
                    if ($check_user->num_rows() > 0) {
                        $user = $check_user->row();
                        $messagestr = "Message from Admin\n".$imgmessage;
                        sendPushNotifications($sending_ids = [strval($insert['fk_to_id'])], $message = $messagestr, $userid=$insert['fk_to_id']);
                    }

                    if (isset($upload_insert) && !empty($upload_insert)) {
                        $this->db->insert_batch("rudra_chat_uploads", $upload_insert);
                        $uploaded = $this->db->insert_id();
                        
                    }
                }
            }
        }

        // Export CSV Codes 
        if ($action == "export_data") {
            $header = array();
            foreach ($json_cols as $ck => $cv) {
                $header[] = $cv->list_caption;
            }
            $filename = strtolower('rudra_chat') . '_' . date('d-m-Y') . ".csv";
            $fp = fopen('php://output', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            fputcsv($fp, $header);
            $result_set = $this->db->get($this->full_table)->result();
            foreach ($result_set as $k) {
                $row = array();
                foreach ($json_cols as $ck => $cv) {
                    $cl = $cv->col_name;
                    $row[] = $k->$cl;
                }
                fputcsv($fp, $row);
            }
        }
        echo json_encode($this->return_data);
        exit;
    }

    public function messages(){


        $user_id = $this->session->userdata('rudra_admin_id');
        // exit;

        $query = "SELECT * FROM " . $this->full_table . " WHERE   `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
		$result = $this->db->query($query)->result();
        $list = array();
        $fk_from_id=array();
        foreach($result as $row){
            if(!in_array($row->fk_from_id, $fk_from_id)){
                $query = "SELECT count(id) as count FROM " . $this->full_table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `fk_from_id` = '".$row->fk_from_id."' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res = $this->db->query($query)->row();

                $row->total_messages = $res->count;

                $query2 = "SELECT count(id) as count FROM " . $this->full_table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `fk_from_id` = '".$row->fk_from_id."' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res2 = $this->db->query($query2)->row();

                $row->unread_messages = $res2->count;

                $sender = $this->db->get_where('rudra_user', array('id' => $row->fk_from_id))->row();
                $row->sender = (!empty($sender) ? ($sender->name.' '.$sender->surname) : '--');
                $row->sender_type = '';
                if($sender->user_type == '1'){
                    $row->sender_type = 'User';
                }else{
                    $row->sender_type = 'Washer';
                }

                $list[] = $row;
            }
            array_push($fk_from_id, $row->fk_from_id);
        }


        $data['list'] = $list;
        // echo "<pre>";
        // print_r($result);exit;
        $data['pageTitle'] = ' Received Messages';
        $data['page_template'] = '_rudra_chat_messages';
        $data['page_header'] = ' Received Messages';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }

    public function new_messages(){

        $user_id = $this->session->userdata('rudra_admin_id');

        $query = "SELECT * FROM " . $this->full_table . " WHERE   `status` = '1' AND `is_deleted` = '0' AND `is_read` = '0'  ORDER BY `created_at` desc";
		$result = $this->db->query($query)->result();
        $list = array();
        $fk_from_id=array();
        foreach($result as $row){
            if(!in_array($row->fk_from_id, $fk_from_id)){
                $query = "SELECT count(id) as count FROM " . $this->full_table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `fk_from_id` = '".$row->fk_from_id."' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res = $this->db->query($query)->row();

                $row->total_messages = $res->count;

                $query2 = "SELECT count(id) as count FROM " . $this->full_table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `fk_from_id` = '".$row->fk_from_id."' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
                $res2 = $this->db->query($query2)->row();

                $row->unread_messages = $res2->count;

                $sender = $this->db->get_where('rudra_user', array('id' => $row->fk_from_id))->row();
                $row->sender = (!empty($sender) ? ($sender->name.' '.$sender->surname) : '--');
                $row->sender_type = '';
                if($sender->user_type == '1'){
                    $row->sender_type = 'User';
                }else{
                    $row->sender_type = 'Washer';
                }

                $list[] = $row;
            }
            array_push($fk_from_id, $row->fk_from_id);
        }


        $data['list'] = $list;
        // echo "<pre>";
        // print_r($result);exit;
        $data['pageTitle'] = ' Received Messages';
        $data['page_template'] = '_rudra_chat_messages';
        $data['page_header'] = ' Received Messages';
        $data['load_type'] = 'all';
        $this->load->view('crm/template', $data);
    }


    public function get_new_chats(){


        $user_id = $this->session->userdata('rudra_admin_id');

        $query2 = "SELECT count(id) as count FROM " . $this->full_table . " WHERE  `fk_to_id` = '" . $user_id . "' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
        $res2 = $this->db->query($query2)->row();

        echo $res2->count;
    }
}
