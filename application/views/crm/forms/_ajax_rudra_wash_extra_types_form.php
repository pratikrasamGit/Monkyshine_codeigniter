<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">

	<?php $options = washTypesList(); ?>
	<div class="form-group col-sm-6">
		<label>Choose Wash Type</label>
		<select id='fk_wash_id' name="fk_wash_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_wash_id == $dv['id']) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv['id'] ?>"><?= $dv['name'].' - '.CarSizeType($dv['car_size']) ?></option>
			<?php  } ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label>Extra Name</label>
		<input type="text" name="extra_name" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_name : ''); ?>" placeholder="Extra Name" />
	</div>
	<div class="form-group col-sm-6">
		<label>Extra Name Dn</label>
		<input type="text" name="extra_name_dn" class="form-control" value="<?= (!empty($form_data) ? $form_data->extra_name_dn : ''); ?>" placeholder="Extra Name in Danish" />
	</div>
	<div class="form-group col-sm-6">
		<label>Amount</label>
		<input type="text" name="amount" class="form-control" value="<?= (!empty($form_data) ? $form_data->amount : ''); ?>" placeholder="Amount" />
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