<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">
	<?php $options = CarSizeType($id = ""); ?>
	<div class="form-group col-sm-6">
		<label>Car Size</label>
		<select id='car_size' name="car_size" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->car_size == $dk) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dk ?>"><?= $dv ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = userList($type = "1", $id = ""); ?>
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
	<div class="form-group col-sm-6">
		<label>Registration Number</label>
		<input type="text" name="registration_number" class="form-control" value="<?= (!empty($form_data) ? $form_data->registration_number : ''); ?>" placeholder="Registration Number" />
	</div>
	<div class="form-group col-sm-6">
		<label>Name</label>
		<input type="text" name="name" class="form-control" value="<?= (!empty($form_data) ? $form_data->name : ''); ?>" placeholder="Name" />
	</div>
	<div class="form-group col-sm-6">
		<label>Brand</label>
		<input type="text" name="brand" class="form-control" value="<?= (!empty($form_data) ? $form_data->brand : ''); ?>" placeholder="Brand" />
	</div>
	<div class="form-group col-sm-6">
		<label>Model</label>
		<input type="text" name="model" class="form-control" value="<?= (!empty($form_data) ? $form_data->model : ''); ?>" placeholder="Model" />
	</div>
	<div class="form-group col-sm-6">
		<label>Color</label>
		<input type="text" name="color" class="form-control" value="<?= (!empty($form_data) ? $form_data->color : ''); ?>" placeholder="Color" />
	</div>
	<div class="form-group col-sm-6">
		<label>Description</label>
		<input type="text" name="description" class="form-control" value="<?= (!empty($form_data) ? $form_data->description : ''); ?>" placeholder="Description" />
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