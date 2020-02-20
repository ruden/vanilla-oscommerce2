<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>

<?php
  if ($oscTemplate->hasBlocks('boxes_column_left')) {
?>

<div id="columnLeft" class="grid_<?php echo $oscTemplate->getGridColumnWidth(); ?> pull_<?php echo $oscTemplate->getGridContentWidth(); ?>">
  <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
</div>

<?php
  }

  if ($oscTemplate->hasBlocks('boxes_column_right')) {
?>

<div id="columnRight" class="grid_<?php echo $oscTemplate->getGridColumnWidth(); ?>">
  <?php echo $oscTemplate->getBlocks('boxes_column_right'); ?>
</div>

<?php
  }
?>

</main>

<footer class="footer container">
  <?php require('includes/footer.php'); ?>
</footer>


<script src="ext/bootstrap/bootstrap.min.js"></script>
<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>

</body>
</html>
