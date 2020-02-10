<h2><?php echo sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')); ?></h2>
<div class="contentText">
  <ul style="margin: 0; padding: 0;">

    <?php
    foreach ($new_products_array as $products) {
      ?>

      <li style="display: inline-block; width: 32%; text-align: center; vertical-align: top; padding: 2px 0;">

        <?php
        echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br />
<a href="' . tep_href_link('product_info.php', 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a><br />' . $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id']));
        ?>

      </li>

      <?php
    }
    ?>

  </ul>
</div>