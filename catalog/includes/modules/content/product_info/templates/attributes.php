<div class="mb-3">

  <?php
  foreach ($products_attributes_array as $products_attributes) {
    ?>

    <div class="mb-3">
      <label class="form-label" for="<?php echo 'id[' . $products_attributes['id'] . ']'; ?>"><?php echo $products_attributes['name'] . ':'; ?></label>
      <?php echo tep_draw_pull_down_menu('id[' . $products_attributes['id'] . ']', $products_attributes['array'], $products_attributes['selected_attribute'], 'class="form-select w-auto" id="id[' . $products_attributes['id'] . ']"'); ?>
    </div>

    <?php
  }
  ?>

</div>
