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

require('includes/languages/' . $language . '/product_info.php');

$page_content = $oscTemplate->getContent('product_info');

require('includes/template_top.php');

if (!isset($product_info['products_id'])) {
  ?>

  <div class="mb-5">
    <div class="mb-3">
      <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
    </div>
  </div>

  <?php
} else {
  tep_db_query("update products_description set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
  ?>

  <div class="container">
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
  </div>


  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');