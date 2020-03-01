<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>

    </div>

<?php
  if ($oscTemplate->hasBlocks('boxes_column_right')) {
?>

  <aside class="aside col-xl-2 d-none d-xl-block">
    <?php echo $oscTemplate->getBlocks('boxes_column_right'); ?>
  </aside>

<?php
  }
?>

  </div>

</main>

<footer class="footer container">
  <?php require('includes/footer.php'); ?>
</footer>

<script src="ext/bootstrap/bootstrap.min.js"></script>
<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>

</body>
</html>
