<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">

	<?php $options = $fk_user_id; ?>
	<div class="form-group col-sm-6">
		<label>Fk User Id</label>
		<select id='fk_user_id' name="fk_user_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_user_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->id ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = $fk_booking_id; ?>
	<div class="form-group col-sm-6">
		<label>Fk Booking Id</label>
		<select id='fk_booking_id' name="fk_booking_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_booking_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->id ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Date Time</label>
		<input type="date" name="date_time" class="form-control" value="<?= (!empty($form_data) ? $form_data->date_time : ''); ?>" placeholder="Date Time" />
	</div>

	<?php $options = $pay_type; ?>
	<div class="form-group col-sm-6">
		<label>Pay Type</label>
		<select id='pay_type' name="pay_type" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->pay_type == $dv) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Paid</label>
		<input type="text" name="paid" class="form-control" value="<?= (!empty($form_data) ? $form_data->paid : ''); ?>" placeholder="Paid" />
	</div>
	<div class="form-group col-sm-6">
		<label>Transaction Id</label>
		<input type="text" name="transaction_id" class="form-control" value="<?= (!empty($form_data) ? $form_data->transaction_id : ''); ?>" placeholder="Transaction Id" />
	</div>
	<div class="form-group col-sm-6">
		<label>Request</label>
		<input type="text" name="request" class="form-control" value="<?= (!empty($form_data) ? $form_data->request : ''); ?>" placeholder="Request" />
	</div>

	<?php $options = $fk_bank_account_id; ?>
	<div class="form-group col-sm-6">
		<label>Fk Bank Account Id</label>
		<select id='fk_bank_account_id' name="fk_bank_account_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_bank_account_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->id ?></option>
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