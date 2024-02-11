
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/plugins/data-tables/css/datatables.min.css" />
<link rel="stylesheet" href="<?= base_url('app_') ?>assets/css/style.css" />
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [Total-user section] start -->
   
    <div class="clear-fix clearfix">
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-5">
                        <h5><?= $page_header ?></h5>
                    </div>
                </div>
            </div>



<div class="card-block">
<form id="frm_rudra_user" method="post" enctype="multipart/form-data">

<div class='row'>

	<?php $options = userType(); ?>
	<div class="form-group col-sm-6">
		<label>User Type</label>
		<select id='user_type' name="user_type" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->user_type == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php /* $options = $login_type; ?>
	<div class="form-group col-sm-6">
		<label>Login Type</label>
		<select id='login_type' name="login_type" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->login_type == $dv) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Social Id</label>
		<input type="text" name="social_id" class="form-control" value="<?= (!empty($form_data) ? $form_data->social_id : ''); ?>" placeholder="Social Id" />
	</div>
	<?php */ ?>
	<div class="form-group col-sm-6">
		<label>Email</label>
		<input type="text" name="email" class="form-control" value="<?= (!empty($form_data) ? $form_data->email : ''); ?>" placeholder="Email" />
	</div>
	<div class="form-group col-sm-6">
		<label>Password</label>
		<input type="text" name="password" class="form-control" value="<?= (!empty($form_data) ? $form_data->password : ''); ?>" placeholder="Password" />
	</div>

	<?php  /* ?>
	<div class="form-group col-sm-6">
		<label>Forgot Code</label>
		<input type="text" name="forgot_code" class="form-control" value="<?= (!empty($form_data) ? $form_data->forgot_code : ''); ?>" placeholder="Forgot Code" />
	</div>
	<div class="form-group col-sm-6">
		<label>Forgot Time</label>
		<input type="date" name="forgot_time" class="form-control" value="<?= (!empty($form_data) ? $form_data->forgot_time : ''); ?>" placeholder="Forgot Time" />
	</div>
	<?php */ ?>

	<div class="form-group col-sm-6">
		<label>Name</label>
		<input type="text" name="name" class="form-control" value="<?= (!empty($form_data) ? $form_data->name : ''); ?>" placeholder="Name" />
	</div>
	<div class="form-group col-sm-6">
		<label>Surname</label>
		<input type="text" name="surname" class="form-control" value="<?= (!empty($form_data) ? $form_data->surname : ''); ?>" placeholder="Surname" />
	</div>
	<div class="form-group col-sm-6">
		<label>Contact Email</label>
		<input type="text" name="contact_email" class="form-control" value="<?= (!empty($form_data) ? $form_data->contact_email : ''); ?>" placeholder="Contact Email" />
	</div>
	<div class="form-group col-sm-6">
		<label>Contact Number</label>
		<input type="text" name="contact_number" class="form-control" value="<?= (!empty($form_data) ? $form_data->contact_number : ''); ?>" placeholder="Contact Number" />
	</div>

	<?php  /* ?>
	<div class="form-group col-sm-6">
		<label>Lat</label>
		<input type="text" name="lat" class="form-control" value="<?= (!empty($form_data) ? $form_data->lat : ''); ?>" placeholder="Lat" />
	</div>
	<div class="form-group col-sm-6">
		<label>Lon</label>
		<input type="text" name="lon" class="form-control" value="<?= (!empty($form_data) ? $form_data->lon : ''); ?>" placeholder="Lon" />
	</div>
	<?php */ ?>

	<div class="form-group col-sm-6">
		<label>Address</label>
		<input type="text" name="address" class="form-control" value="<?= (!empty($form_data) ? $form_data->address : ''); ?>" placeholder="Address" />
	</div>
	<div class="form-group col-sm-6">
		<label>Zipcode</label>
		<input type="text" name="zipcode" class="form-control" value="<?= (!empty($form_data) ? $form_data->zipcode : ''); ?>" placeholder="Zipcode" />
	</div>
	<div class="form-group col-sm-6">
		<label>City</label>
		<input type="text" name="city" class="form-control" value="<?= (!empty($form_data) ? $form_data->city : ''); ?>" placeholder="City" />
	</div>
	<?php  /* ?>
	<div class="form-group col-sm-6">
		<label>Device Token</label>
		<input type="text" name="device_token" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_token : ''); ?>" placeholder="Device Token" />
	</div>
	<div class="form-group col-sm-6">
		<label>App Version</label>
		<input type="text" name="app_version" class="form-control" value="<?= (!empty($form_data) ? $form_data->app_version : ''); ?>" placeholder="App Version" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Type</label>
		<input type="text" name="device_type" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_type : ''); ?>" placeholder="Device Type" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Company</label>
		<input type="text" name="device_company" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_company : ''); ?>" placeholder="Device Company" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Version</label>
		<input type="text" name="device_version" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_version : ''); ?>" placeholder="Device Version" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Location</label>
		<input type="text" name="device_location" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_location : ''); ?>" placeholder="Device Location" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Lat</label>
		<input type="text" name="device_lat" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_lat : ''); ?>" placeholder="Device Lat" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Lang</label>
		<input type="text" name="device_lang" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_lang : ''); ?>" placeholder="Device Lang" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device City</label>
		<input type="text" name="device_city" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_city : ''); ?>" placeholder="Device City" />
	</div>
	<div class="form-group col-sm-6">
		<label>Device Country</label>
		<input type="text" name="device_country" class="form-control" value="<?= (!empty($form_data) ? $form_data->device_country : ''); ?>" placeholder="Device Country" />
	</div>
	<?php */ ?>

	<?php $options = statusDropdown($id = ""); ?>
	<div class="form-group col-sm-6">
		<label>Status</label>
		<select id='status' name="status" class="form-control">
			<?php foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->status == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = isDeletedDropdown($id = ""); ?>
	<!-- <div class="form-group col-sm-6">
		<label>Is Deleted</label>
		<select id='is_deleted' name="is_deleted" class="form-control">
			<?php foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->is_deleted == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div> -->
</div>
<button type="submit" class="btn btn-primary btn-modal-form">Submit</button>

<!--Uncomment if Scroll Required div -->

</form>
</div>
</div>
</div>
</div>


<!-- Form Scripts Starts Here -->

<script type='text/javascript'>
    
    $("#frm_rudra_user").attr("action", '<?= base_url() ?>rudra_user/post_actions/insert_data');

    $("#frm_rudra_user").submit(function(e) {
        e.preventDefault();
        var formData = new FormData($("#frm_rudra_user")[0]);
        var action_url = $("#frm_rudra_user").attr("action");
        $.ajax({
            type: 'POST',
            url: action_url,
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                location.reload();
                // response = JSON.parse(data);
                // if (response.text) {
                //     loadTextData();
                //     $(".btn-modal-form").prop('disabled', false);
                //     $(".btn-modal-form").html('Update');
                //     $('.dynamic-modal').modal('hide');
                //     $("#static_form_modal").modal('hide');
                //     //setTimeout(function(){ window.location = response.data.url; }, 3000);
                // } else if (response.status) {
                //     $(".btn-modal-form").prop('disabled', false);
                //     $(".btn-modal-form").html('Update');
                //     usrTable.ajax.reload(null, false);
                //     $('.dynamic-modal').modal('hide');
                //     //setTimeout(function(){ window.location = response.data.url; }, 3000);
                // } else {
                //     if (typeof response.msg !== 'undefined' && response.msg != "") swal(response.msg);
                //     $(".btn-modal-form").prop('disabled', false);
                //     $(".btn-modal-form").html('Update');
                // }
            }
        });
    });
</script>