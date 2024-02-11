<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<div class="form-group col-sm-6">
		<label>User Id</label>
		<input type="name" name="user_id" readonly class="form-control" value="<?php echo userList($type = "1", $id = $form_data->user_id)['name'] ?>" placeholder="User Id" />
	</div>
	<div class="form-group col-sm-6">
		<label>Booking Id</label>
		<input type="text" name="booking_id" readonly class="form-control" value="<?= (!empty($form_data) ? $form_data->booking_id : ''); ?>" placeholder="Booking Id" />
	</div>
	<div class="form-group col-sm-6">
		<label>Zipcode</label>
		<input type="text" name="zipcode" class="form-control" value="<?= (!empty($form_data) ? $form_data->zipcode : ''); ?>" placeholder="Zipcode" />
	</div>
	<!-- <div class="form-group col-sm-6">
		<label>Latitude</label>
		<input type="text" name="latitude" class="form-control" value="<?= (!empty($form_data) ? $form_data->latitude : ''); ?>" placeholder="Latitude" />
	</div>
	<div class="form-group col-sm-6">
		<label>Longitude</label>
		<input type="text" name="longitude" class="form-control" value="<?= (!empty($form_data) ? $form_data->longitude : ''); ?>" placeholder="Longitude" />
	</div> -->
	<div class="form-group col-sm-6">
		<label>Address</label>
		<input type="text" name="address" class="form-control" value="<?= (!empty($form_data) ? $form_data->address : ''); ?>" placeholder="Address" />
	</div>

	<?php $options = $fk_car_reg_id; ?>
	<div class="form-group col-sm-6">
		<label>Registered Car Number</label>
		<select id='fk_car_reg_id' name="fk_car_reg_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_car_reg_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->registration_number ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Chosen Wash</label>
		<?php $options = washTypesList(); ?>
		<!-- <input type="number" name="wash_id" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_id : ''); ?>" placeholder="Wash Id" /> -->
		<select id='wash_id' name="wash_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->wash_id == $dv['id']) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv['id'] ?>"><?= $dv['name'].' ('.CarSizeType($dv['car_size']).')'; ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Chosen Extra Washes</label>
		<input type="text" name="extra_wash_type_ids" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_wash_type_ids : ''); ?>" placeholder="Extra Wash Type Ids" />
	</div>
	<div class="form-group col-sm-6">
		<label>Wash Date</label>
		<input type="date" name="wash_date" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_date : ''); ?>" placeholder="Wash Date" />
	</div>
	<div class="form-group col-sm-6">
		<label>Wash Time</label>
		<input type="text" name="wash_time" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_time : ''); ?>" placeholder="Wash Time" />
	</div>
	<div class="form-group col-sm-6">
		<label>Notes</label>
		<input type="text" name="notes" class="form-control" value="<?= (!empty($form_data) ? $form_data->notes : ''); ?>" placeholder="Notes" />
	</div>
	<div class="form-group col-sm-6">
		<label>Washer</label>
		<?php $options = userList($type='2'); ?>
		<!-- <input type="number" name="washer_id" class="form-control" value="<?= (!empty($form_data) ? $form_data->washer_id : ''); ?>" placeholder="Washer Id" /> -->
		<select id='washer_id' name="washer_id" class="form-control">
			<option value="">Select washer</option>
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->washer_id == $dv['id']) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv['id'] ?>"><?= $dv['name'] ?></option>
			<?php  } ?>
		</select>
	</div> 

	<?php $options = washStatus(); ?>
	<div class="form-group col-sm-6">
		<label>Wash Status</label>
		<select id='wash_status' name="wash_status" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->wash_status == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?=$dv ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Vat Percentage</label>
		<input type="text" readonly name="vat_percentage" class="form-control" value="<?= (!empty($form_data) ? $form_data->vat_percentage : ''); ?>" placeholder="Vat Percentage" />
	</div>
	<div class="form-group col-sm-6">
		<label>Vat</label>
		<input type="text" readonly name="vat" class="form-control" value="<?= (!empty($form_data) ? $form_data->vat : ''); ?>" placeholder="Vat" />
	</div>
	<div class="form-group col-sm-6">
		<label>Extra Charges</label>
		<input type="text" readonly name="extra_charges" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_charges : ''); ?>" placeholder="Extra Charges" />
	</div>
	<!-- <div class="form-group col-sm-6">
		<label>Wash Type Json</label>
		<input type="text" name="wash_type_json" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_type_json : ''); ?>" placeholder="Wash Type Json" />
	</div> -->
	<div class="form-group col-sm-6">
		<label>Wash Amount</label>
		<input type="text" name="wash_amount" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_amount : ''); ?>" placeholder="Wash Amount" />
	</div>
	<!-- <div class="form-group col-sm-6">
		<label>Extra Wash Type Json</label>
		<input type="text" name="extra_wash_type_json" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_wash_type_json : ''); ?>" placeholder="Extra Wash Type Json" />
	</div> -->
	<div class="form-group col-sm-6">
		<label>Extra Wash Amount</label>
		<input type="text" readonly name="extra_wash_amount" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_wash_amount : ''); ?>" placeholder="Extra Wash Amount" />
	</div>
	<div class="form-group col-sm-6">
		<label>Total Pay</label>
		<input type="text" readonly name="total_pay" class="form-control" value="<?= (!empty($form_data) ? $form_data->total_pay : ''); ?>" placeholder="Total Pay" />
	</div>
	<div class="form-group col-sm-6">
		<label>Transaction</label>
		<input type="text" readonly name="transaction" class="form-control" value="<?= (!empty($form_data) ? $form_data->transaction : ''); ?>" placeholder="Transaction" />
	</div>

	<?php $options = payType(); ?>
	<div class="form-group col-sm-6">
		<label>Pay Method</label>
		<select id='pay_method' name="pay_method" class="form-control" readonly>
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->pay_method == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>


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
<!--Uncomment if Scroll Required div -->