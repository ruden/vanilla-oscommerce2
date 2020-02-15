<div class="ui-widget infoBoxContainer">
  <div class="ui-widget-header infoBoxHeading"><?php echo MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_BOX_TITLE; ?></div>
  <div class="ui-widget-content infoBoxContents">
    <ul style="margin: 0; padding: 0;">

      <?php
      foreach ($orders_array as $products) {
        ?>

        <li style="display: inline-block; width: 19%; text-align: center; vertical-align: top; padding: 2px 0;">

          <?php
          echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products['products_id']) . '">' . tep_image('images/' . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link('product_info.php', 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a><br />' . $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id']));
          ?>

        </li>

        <?php
      }
      ?>

    </ul>
  </div>
</div>
