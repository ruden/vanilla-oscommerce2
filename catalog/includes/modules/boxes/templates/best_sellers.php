<div class="card mb-2">
  <div class="card-header"><?php echo MODULE_BOXES_BEST_SELLERS_BOX_TITLE; ?></div>

  <ul class="list-group list-group-flush">

    <?php
    foreach ($best_sellers_array as $products) {
      ?>

      <li>
        <a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products['products_id']); ?>"><?php echo $products['products_name']; ?></a>
      </li>

      <?php
    }
    ?>

  </ul>

</div>