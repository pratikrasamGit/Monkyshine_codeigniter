<!--UNComment if SCROLL BAR required div class='col-sm-12' style='max-height:500px;overflow: auto;'-->
<div class='row'><input type="hidden" required name="id" value="<?= (isset($id) ? $id : 0); ?>">

	<?php $options = $fk_from_id; ?>
	<div class="form-group col-sm-6">
		<label>Fk From Id</label>
		<select id='fk_from_id' name="fk_from_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_from_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->id ?></option>
			<?php  } ?>
		</select>
	</div>

	<?php $options = $fk_to_id; ?>
	<div class="form-group col-sm-6">
		<label>Fk To Id</label>
		<select id='fk_to_id' name="fk_to_id" class="form-control">
			<?php
			foreach ($options as $dk => $dv) {
				$selectop = '';
				if (!empty($form_data) && $form_data->fk_to_id == $dv->id) {
					$selectop = 'selected="selected"';
				} ?>
				<option <?= $selectop ?> value="<?= $dv->id ?>"><?= $dv->id ?></option>
			<?php  } ?>
		</select>
	</div>

	<div class="form-group col-sm-6">
		<label>Message</label>
		<input type="text" name="message" class="form-control" value="<?= (!empty($form_data) ? $form_data->message : ''); ?>" placeholder="Message" />
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