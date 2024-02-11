<?php
function siteName()
{
	return "MONKEYSHINE";
}

function siteLogo()
{
	return base_url('app_assets/images/logo.jpg');
}

function checkUserExists($table, $column = "", $value = "")
{
	$ci = &get_instance();

	$result = [];
	if ($column != "" && $value != "") {
		$ci->db->where($column, $value);
		$ci->db->where('is_deleted', "0");
		$check = $ci->db->get($table);
		$result = ($check->num_rows() > 0) ? $check->row_array() : [];
	}

	return $result;
}

function check_upload_exists($id = "")
{
	$ci = &get_instance();
	$result = [];
	if ($id != "") {
		$ci->db->where('fk_user_id', $id);
		$uploads = $ci->db->get("rudra_user_uploads");
		$result = ($uploads->num_rows() > 0) ? $uploads->result() : [];
	}

	return $result;
}


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function calcDistance($lat1, $lon1, $lat2, $lon2, $unit)
{
	if (!is_numeric($lat1) && !is_numeric($lon1) && !is_numeric($lat2) && !is_numeric($lon2) && ($lat1 == $lat2) && ($lon1 == $lon2)) {
		return 0;
	} else {
		// $theta = $lon1 - $lon2;
		if (is_numeric($lon1) - is_numeric($lon2)) $theta = $lon1 - $lon2;
		else return 0;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	/* echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>"; */
}

function sendPushNotifications($sending_ids = NULL, $message = NULL, $userid = NULL)
{
	$ci = &get_instance();

	$count=0;
	$ci->db->where('is_read', '0');
	$ci->db->where('fk_to_id', $userid);
	$ci->db->where('status', '1');
	$ci->db->where('is_deleted', '0');
	$check = $ci->db->get('rudra_chat');
	$chats = $check->num_rows();

	$count = $count + $chats;

	$ci->db->where('is_read', '0');
	$ci->db->where('fk_user_id', $userid);
	$ci->db->where('status', '1');
	$ci->db->where('is_deleted', '0');
	$check2 = $ci->db->get('rudra_notifications');
	$notifications = $check2->num_rows();
	
	$count = $count + $notifications;
	$post_fields['app_id'] = "18107925-fe66-42ae-ab06-5db5a7e1fa97";

	$post_fields['contents'] = array("en" => $message);
	$post_fields['include_external_user_ids'] = $sending_ids;
	$post_fields['headings'] = array("en" => "Monkyshine");
	$post_fields['subtitle'] = array("en" => 'Reminder Notification');
	$post_fields['ios_badgeType'] = "Increase";
	$post_fields['ios_badgeCount'] = $count;

	// echo "{\"app_id\": \"c69f499b-662c-49ea-a0b3-e936232a9755\",\n\"contents\": {\"en\": \"English Message\"},\n\"include_external_user_ids\": [\"13\"]}";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields, true));

	$headers = array();
	$headers[] = 'Content-Type: application/json; charset=utf-8';
	$headers[] = 'Authorization: Basic OTIwZDdmMzAtMDY3Mi00ZTY4LWEyZGEtYWUyYWZiOWZhY2U4';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);


	return $result;
}

function user_count($params = "")
{
	$ci = &get_instance();

	if ($params == "inactive")
		$ci->db->where(['status' => '0', 'is_deleted' => '0']);
	else
		$ci->db->where(['status' => '1', 'is_deleted' => '0']);

	$query = $ci->db->get('rudra_user');

	return $query->num_rows();
}

function bookings_count($params = "")
{
	$ci = &get_instance();

	if ($params == "completed") {
		$ci->db->where('washer_id !=', '');
		$ci->db->where(['wash_status' => '3', 'is_deleted' => '0']);
	} elseif ($params == "approved") {
		$ci->db->where('washer_id !=', '');
		$ci->db->where(['wash_status' => '2', 'is_deleted' => '0']);
	} elseif ($params == "refund_pending") {
		$ci->db->where(['is_deleted' => '1', 'status' => '0', 'cancellation' => '1']);
	} elseif ($params == "todaysbooking") {
		$ci->db->where('wash_date', date('Y-m-d'));
		$ci->db->where(['is_deleted' => '0']);
	}else
		$ci->db->where(['wash_status' => '1', 'is_deleted' => '0']);

	$query = $ci->db->get('rudra_booking');

	return $query->num_rows();
}

function getLangMessage($lang = "1", $str = "")
{
	$ci = &get_instance();

	$file = ($lang == "1") ? "english" : "danish";
	$ci->lang->load($file, $file);

	return $ci->lang->line($str);
}

function unread_chats(){

	$ci = &get_instance();

	$user_id = $ci->session->userdata('rudra_admin_id');

	$query = "SELECT count(id) as count FROM rudra_chat WHERE  `fk_to_id` = '" . $user_id . "' AND `is_read` = '0' AND `status` = '1' AND `is_deleted` = '0'  ORDER BY `created_at` desc";
	$res = $ci->db->query($query)->row();

	return $res->count;

}
/* End of file common.php */
