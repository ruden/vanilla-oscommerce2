<div class="t-category-images my-5">

  <div class="row text-center">

    <?php
    foreach ($categories_array as $cPath => $categories) {
      ?>

      <div class="col-6 col-lg">
        <a href="<?php echo tep_href_link('index.php', $cPath); ?>"><?php echo tep_image('images/categories/' . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT); ?>
          <br/><?php echo $categories['categories_name']; ?></a>
      </div>

      <?php
    }
    ?>

  </div>

</div>