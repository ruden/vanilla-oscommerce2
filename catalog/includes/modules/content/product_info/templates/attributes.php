<div class="mb-3">

  <?php
  foreach ($products_attributes_array as $products_attributes) {
    ?>

    <div class="mb-3">
      <h5><?php echo $products_attributes['name']; ?></h5>

      <?php
      foreach ($products_attributes['array'] as $attribute) {
        ?>

        <div class="form-check-inline">
          <label class="form-check-label">
            <?php echo tep_draw_radio_field('id[' . $products_attributes['id'] . ']', $attribute['id'], ($products_attributes['selected_attribute'] == $attribute['id']), 'class="form-check-input" required') . ' ' . $attribute['text']; ?>
          </label>
        </div>

        <?php
      }
      ?>

    </div>

    <?php
  }
  ?>

</div>
