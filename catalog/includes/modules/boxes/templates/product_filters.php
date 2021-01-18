<div class="card border-0 mb-3" id="product-filters">
  <h5 class="mb-3"><?php echo MODULE_BOXES_PRODUCT_FILTERS_TITLE; ?></h5>

  <div class="card-text">
    <?php echo tep_draw_form('product_filters', tep_href_link('index.php', tep_get_all_get_params()), 'get'); ?>
    <?php echo tep_draw_hidden_field('cPath', $cPath); ?>
    <?php echo tep_draw_hidden_field('sort', isset($_GET['sort']) ? $_GET['sort'] : 'date'); ?>

    <?php echo implode(' ', $product_filters); ?>

    </form>
  </div>

</div>