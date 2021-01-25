<div class="row justify-content-center text-center mb-3">

  <?php
  foreach ($manufacturers_array as $manufacturers) {
    ?>

    <div class="col-auto px-1">

      <a href="<?php echo tep_href_link('manufacturers.php', 'manufacturer_id=' . $manufacturers['manufacturers_id']); ?>"><?php echo tep_image('images/manufacturers/' . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name']); ?>
        <br/><?php echo $manufacturers['manufacturers_name']; ?></a>

    </div>

    <?php
  }
  ?>

</div>