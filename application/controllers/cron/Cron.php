<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "Cron start <br>";
        $query = $this->db->query('SELECT `rb`.*, `ru`.`id` `user_id`, `ru`.`name` `user_name` FROM `rudra_booking` `rb` LEFT JOIN `rudra_user` `ru` ON `ru`.`id` = `rb`.`user_id` WHERE `rb`.`status` = "1" AND `rb`.`is_deleted` = "0" AND `rb`.`wash_status` IN ("2") AND `rb`.`push_notification_status` = "0" AND( `rb`.`created_at` <= DATE_ADD(NOW(), INTERVAL 2 HOUR) AND `rb`.`created_at` >= DATE_SUB(NOW(), INTERVAL 3 HOUR) ) ORDER BY `rb`.`id` ASC');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $b) {

                echo "current booking id is : " . $b->id . '<br>';

                $sending_ids = [strval($b->user_id)];
                $message = "Hi " . $b->user_name . ', Your wash for booking ID: #' . $b->booking_id . ' has been accepted and yet to start in few hours';
                sendPushNotifications($sending_ids, $message, $userid=$b->user_id);

                $this->db->where(['id' => $b->id]);
                $update = $this->db->update('rudra_booking', ['push_notification_status' => '1']);
            }
        }
        echo "<br> Cron end";
    }

    public function testPush()
    {
        if (isset($_GET['id']) && $_GET['id'] != "" && is_numeric($_GET['id'])) {
            $sending_ids = [$_GET['id']];
            $message = "Hi User, Your wash for booking ID: #ABCDE123 has been accepted and yet to start in few hours";
            sendPushNotifications($sending_ids, $message, $userid=$_GET['id']);
        }
    }

    public function sendnotification(){

        $query = "SELECT * FROM rudra_booking WHERE `wash_status`='2' AND `wash_date`='".date('Y-m-d')."'  AND `status` = '1' AND `is_deleted` = '0' ORDER BY id ASC;";
        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            foreach ($result->result() as $key => $n) {

                $message = 'Husk din vask i dag kl. '.date('H:i', strtotime($n->wash_time));
                sendPushNotifications([strval($n->washer_id)], $message, $userid=$n->washer_id);

            }
        }
        
    }

    public function test(){

        // $sending_ids = [strval(52)];
    // $post_fields['app_id'] = "18107925-fe66-42ae-ab06-5db5a7e1fa97";

	// // $post_fields['contents'] = array("en" => $message);
	// // $post_fields['include_external_user_ids'] = $sending_ids;
	// // $post_fields['headings'] = array("en" => "Monkyshine");
	// // $post_fields['subtitle'] = array("en" => 'Remainder Notification');
	// // $post_fields['ios_badgeType'] = "Increase";
	// // $post_fields['ios_badgeCount'] = 0;

	// // echo "{\"app_id\": \"c69f499b-662c-49ea-a0b3-e936232a9755\",\n\"contents\": {\"en\": \"English Message\"},\n\"include_external_user_ids\": [\"13\"]}";

	// $ch = curl_init();
    // // https://onesignal.com/api/v1/players/<my-player-id>
	// curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/apps/18107925-fe66-42ae-ab06-5db5a7e1fa97/users/52');
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($ch, CURLOPT_POST, 1);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields, true));

	// $headers = array();
	// $headers[] = 'Content-Type: application/json; charset=utf-8';
	// $headers[] = 'Authorization: Basic OTIwZDdmMzAtMDY3Mi00ZTY4LWEyZGEtYWUyYWZiOWZhY2U4';
	// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	// $result = curl_exec($ch);
	// if (curl_errno($ch)) {
	// 	echo 'Error:' . curl_error($ch);
	// }
	// curl_close($ch);
    //    //echo sendPushNotifications([strval(52)], "test");
    //     echo $result;exit;

    }
}
/* End of file  cron.php */