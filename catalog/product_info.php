<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!isset($_GET['products_id'])) {
  tep_redirect(tep_href_link('products_new.php'));
}

$product_info_query = tep_db_query("select p.*, pd.*, m.*, group_concat(pi.image) as products_images, IF(s.status, s.specials_new_products_price, null) as specials_new_products_price from products p left join products_description pd on (pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "') left join products_images pi on pi.products_id = p.products_id left join manufacturers m on m.manufacturers_id = p.manufacturers_id left join specials s on s.products_id = p.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "'");
$product_info = tep_db_fetch_array($product_info_query);
if (isset($product_info['products_id'])) {
  $breadcrumb->add($product_info['products_name'], tep_href_link('product_info.php', 'cPath=' . $cPath . '&products_id=' . $product_info['products_id']));
} else {
  http_response_code(404);
}

require('includes/languages/' . $language . '/product_info.php');

$page_content = $oscTemplate->getContent('product_info');

require('includes/template_top.php');

if (!isset($product_info['products_id'])) {
  ?>

  <div class="mb-5">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <?php
} else {
  ?>

  <div class="row">
    <div class="col-lg">
      <?php echo $oscTemplate->getContent('product_info_left'); ?>
    </div>
    <div class="col-lg">
      <h1><?php echo $product_info['products_name']; ?></h1>

      <?php echo $oscTemplate->getContent('product_info_right'); ?>

      <?php echo tep_draw_form('cart_quantity', tep_href_link('product_info.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>

      <div class="bg-light p-3 my-3">
        <?php echo $oscTemplate->getContent('product_info_form'); ?>
      </div>

      </form>

    </div>

    <div class="col-12">
      <?php echo $page_content; ?>
    </div>
  </div>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');