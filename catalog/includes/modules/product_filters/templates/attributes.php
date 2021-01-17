<?php
foreach ($products_attributes_array as $products_attributes) {
  ?>

  <div class="border-1 border-bottom mb-3">
    <h6><?php echo $products_attributes['options_name']; ?></h6>

    <div class="mb-3">

      <?php
      foreach ($products_attributes['options_array'] as $options_value) {
        $selected = false;

        if (isset($_GET['attrib_' . $products_attributes['options_id']]) && !empty($_GET['attrib_' . $products_attributes['options_id']]) && in_array($options_value['products_options_values_id'], $_GET['attrib_' . $products_attributes['options_id']])) {
          $selected = true;
        }
        ?>

        <div class="form-check">
          <?php echo tep_draw_checkbox_field('attrib_' . $products_attributes['options_id'] . '[]', $options_value['products_options_values_id'], $selected, 'class="form-check-input" id="po-id-' . $options_value['products_options_values_id'] . '" onchange="this.form.submit()"'); ?>
          <label class="form-check-label" for="po-id-<?php echo $options_value['products_options_values_id']; ?>">
            <?php echo $options_value['products_options_values_name']; ?>
          </label>
        </div>

        <?php
      }
      ?>

    </div>
  </div>

  <?php
}
?>