<div class="my-auto col-auto">

  <a href="<?php echo tep_href_link('wishlist.php'); ?>" title="<?php echo MODULE_HEADER_WISHLIST_TITLE; ?>">
    <span class="badge rounded-pill bg-primary position-absolute ms-4"><?php echo $wishlist_count_list > 0 ? $wishlist_count_list : ''; ?></span>

    <svg class="svg-icon-heart">
      <use href="#svg-icon-heart"/>
    </svg>
  </a>

</div>