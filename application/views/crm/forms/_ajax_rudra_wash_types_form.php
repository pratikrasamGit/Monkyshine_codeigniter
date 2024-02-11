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
	<div class="form-group col-sm-6">
		<label>Icon</label>
		<input type="file" name="icon" class="form-control" value="<?= (!empty($form_data) ? $form_data->icon : ''); ?>" placeholder="Icon" />
	</div>
	<div class="form-group col-sm-6">
		<label>Wash Name</label>
		<input type="text" name="wash_name" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_name : ''); ?>" placeholder="Wash Name" />
	</div>
	<div class="form-group col-sm-6">
		<label>Wash Name Dn</label>
		<input type="text" name="wash_name_dn" class="form-control" value="<?= (!empty($form_data) ? $form_data->wash_name_dn : ''); ?>" placeholder="Wash Name in danish" />
	</div>
	<div class="form-group col-sm-6">
		<label>Amount</label>
		<input type="text" name="amount" class="form-control" value="<?= (!empty($form_data) ? $form_data->amount : ''); ?>" placeholder="Amount" />
	</div>
	<div class="form-group col-sm-12">
		<label>Description</label>
		<!-- <input type="text" name="description" id="description" class="form-control" value="<?= (!empty($form_data) ? $form_data->description : ''); ?>" placeholder="Description" /> -->
		<textarea name="description" class="form-control" id="description" style="display:none;"><?= (!empty($form_data) ? $form_data->description : ''); ?></textarea>
	</div>
	<div class="form-group col-sm-12">
		<label>Descriptio Dn</label>
		<!-- <input type="text" name="description" id="description" class="form-control" value="<?= (!empty($form_data) ? $form_data->description : ''); ?>" placeholder="Description" /> -->
		<textarea name="description_dn" class="form-control" id="description_dn" style="display:none;"><?= (!empty($form_data) ? $form_data->description_dn : ''); ?></textarea>
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

<script>
	// $('#description').wysiwyg();
	$('#description').wysiwyg({
		toolbar: [
			['operations', ['undo', 'rendo', 'cut', 'copy', 'paste']],
			['text', ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'font-color', 'bg-color']],
			['align', ['left', 'center', 'right', 'justify']],
			['lists', ['unordered', 'ordered', 'indent', 'outdent']],
		],
	});

	$('#description_dn').wysiwyg({
		toolbar: [
			['operations', ['undo', 'rendo', 'cut', 'copy', 'paste']],
			['text', ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'font-color', 'bg-color']],
			['align', ['left', 'center', 'right', 'justify']],
			['lists', ['unordered', 'ordered', 'indent', 'outdent']],
		],
	});
</script>
<!--Uncomment if Scroll Required div -->