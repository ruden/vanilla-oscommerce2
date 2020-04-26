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

  $page_content = $oscTemplate->getContent('product_info');

  require('includes/languages/' . $language . '/product_info.php');

  require('includes/template_top.php');

  if (!isset($product_info['products_id'])) {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?>
  </div>
</div>

<?php
  } else {
    tep_db_query("update products_description set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if (!empty($product_info['specials_new_products_price'])) {
      $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link('product_info.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>

<div>
  <h1 style="float: right;"><?php echo $products_price; ?></h1>
  <h1><?php echo $products_name; ?></h1>
</div>

<div class="contentContainer">
  <div class="contentText">

    <div id="piGal">
<?php
    if (!empty($product_info['products_image']) && is_file('images/' . $product_info['products_image'])) {
?>
        <a href="<?php echo tep_href_link('images/' . $product_info['products_image']); ?>" class="lightbox"><?php echo tep_image('images/' . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH*2, SMALL_IMAGE_HEIGHT*2); ?></a>
<?php
      $pi_query = tep_db_query("select image, htmlcontent from products_images where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");

      if (tep_db_num_rows($pi_query) > 0) {
        while ($pi = tep_db_fetch_array($pi_query)) {
          if (!empty($pi['htmlcontent'])) {
?>
            <a href="<?php echo $pi['htmlcontent']; ?>" data-type="iframe" class="lightbox"><?php echo tep_image('images/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH/1.5, SMALL_IMAGE_HEIGHT/1.5); ?></a>
<?php
          } else {
?>
              <a href="<?php echo tep_href_link('images/' . $pi['image']); ?>" class="lightbox"><?php echo tep_image('images/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH/1.5, SMALL_IMAGE_HEIGHT/1.5); ?></a>
<?php
          }
        }
      }
?>
<script>
  const tobi = new Tobi({
    captions: false,
    zoom: false
  })
</script>
<?php
    } else {
      echo tep_image('images/no_picture.gif');
    }
?>

    </div>

<?php echo stripslashes($product_info['products_description']); ?>

<?php include('includes/modules/products_attributes.php'); ?>

    <div style="clear: both;"></div>

<?php
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>

    <p style="text-align: center;"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></p>

<?php
    }
?>

  </div>

<?php
    $reviews_query = tep_db_query("select count(*) as count from reviews r, reviews_description rd where r.products_id = '" . (int)$_GET['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1");
    $reviews = tep_db_fetch_array($reviews_query);
?>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', null, 'btn-primary'); ?></span>

    <?php echo tep_draw_button(IMAGE_BUTTON_IN_WISHLIST, 'clipboard', null, 'btn-primary', array('params' => 'name="wishlist" value="wishlist"')); ?>

    <?php echo tep_draw_button(IMAGE_BUTTON_REVIEWS . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : ''), 'comment', tep_href_link('product_reviews.php', tep_get_all_get_params())); ?>
  </div>

  <div class="contentContainer">
    <?php echo $page_content; ?>
  </div>

</div>

</form>

<?php
  }

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
