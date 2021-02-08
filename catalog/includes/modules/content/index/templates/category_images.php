<div class="mb-5">

  <div class="row text-center">

    <?php
    foreach ($categories_array as $categories) {
      ?>

      <div class="col-6 col-lg my-3">
        <a href="<?php echo tep_href_link('index.php', $categories['cPath']); ?>"><?php echo tep_image('images/categories/' . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, 'class="img-fluid"'); ?>
        <br><?php echo $categories['categories_name']; ?></a>
      </div>

      <?php
    }
    ?>

  </div>

</div>