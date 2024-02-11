<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<div class="form-group col-sm-6">
		<label>Code</label>
		<input type="text" name="code" class="form-control" value="<?= (!empty($form_data) ? $form_data->code : ''); ?>" placeholder="Code" required />
	</div>
	<div class="form-group col-sm-6">
		<label>Offered (%)</label>
		<input type="text" name="offered" class="form-control" value="<?= (!empty($form_data) ? $form_data->offered : ''); ?>" placeholder="Offered" required />
	</div>
	<div class="form-group col-sm-6">
		<label>Expiry</label>
		<?php $dt = "";
		if (!empty($form_data)) {
			$new_dt =  date('Y-m-d\TH:i:sP', strtotime($form_data->expiry));
			$dt = new DateTime($new_dt); // Date object using current date and time
			$dt = $dt->format('Y-m-d\TH:i:s');
		} ?>
		<input type="datetime-local" name="expiry" class="form-control" value="<?= $dt; ?>" placeholder="Expiry" required />
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
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->is_deleted == $dv) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div> -->
</div>
<!--Uncomment if Scroll Required div -->