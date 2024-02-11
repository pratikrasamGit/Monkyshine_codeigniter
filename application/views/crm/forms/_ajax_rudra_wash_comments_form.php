<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<?php $options = userList($type = "", $id = ""); ?>
	<div class="form-group col-sm-6">
		<label>User</label>
		<select id='fk_user_id' name="fk_user_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_user_id == $dv['id']) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv['id'] ?>"><?= $dv['name'] ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = bookingList($id = ""); ?>
	<div class="form-group col-sm-6">
		<label>Booking Id</label>
		<select id='fk_wash_id' name="fk_wash_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_wash_id == $dv['id']) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv['id'] ?>"><?= '#' . $dv['booking_id']; ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-12">
		<label>Comment</label>
		<textarea placeholder="Comment" name="comment" class="form-control"><?= (!empty($form_data) ? $form_data->comment : ''); ?></textarea>
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