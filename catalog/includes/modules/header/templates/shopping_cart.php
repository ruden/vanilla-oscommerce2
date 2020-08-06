<div class="t-shopping-cart my-auto col-auto">

  <a href="<?php echo tep_href_link('shopping_cart.php'); ?>" title="<?php echo MODULE_HEADER_SHOPPING_CART_TITLE; ?>">
    <span class="badge rounded-pill bg-primary position-absolute ml-4"><?php echo $cart_count_contents > 0 ? $cart_count_contents : ''; ?></span>

    <svg>
      <use href="#svg-icon-shopping-cart"/>
    </svg>
  </a>

</div>