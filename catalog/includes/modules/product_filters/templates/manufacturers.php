<div class="border-1 border-bottom mb-3">
  <h6><?php echo MODULE_PRODUCT_FILTERS_MANUFACTURERS_TITLE; ?></h6>

  <div class="mb-3">

    <?php
    while ($filterlist = tep_db_fetch_array($filterlist_query)) {
      $selected = false;

      if (isset($_GET['mid']) && !empty($_GET['mid']) && in_array($filterlist['manufacturers_id'], $_GET['mid'])) {
        $selected = true;
      }
      ?>

      <div class="form-check">
        <?php echo tep_draw_checkbox_field('mid[]', $filterlist['manufacturers_id'], $selected, 'class="form-check-input" id="manufacturers-id-' . $filterlist['manufacturers_id'] . '" onchange="this.form.submit()"'); ?>
        <label class="form-check-label" for="manufacturers-id-<?php echo $filterlist['manufacturers_id']; ?>">
          <?php echo $filterlist['manufacturers_name']; ?>
        </label>
      </div>

      <?php
    }
    ?>

  </div>
</div>