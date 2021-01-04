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
      </div>
    </div>
  </div>
</main>

<footer class="footer">
  <?php require('includes/footer.php'); ?>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

<?php
if (defined('ACCOUNT_DOB') && ACCOUNT_DOB == 'true' && in_array($PHP_SELF, array('create_account.php', 'account_edit.php'))) {
  ?>

  <link rel="stylesheet" href="ext/vanillajs-datepicker/css/datepicker-bs4.min.css">
  <script src="ext/vanillajs-datepicker/js/datepicker.min.js"></script>

  <script>
    const inputDob = document.querySelector('input[name="dob"]');
    if (inputDob) {
      const datepicker = new Datepicker(inputDob, {
        buttonClass: 'btn',
        format: '<?php echo JQUERY_DATEPICKER_FORMAT; ?>'
      });
    }
  </script>

  <?php
  if (defined('JQUERY_DATEPICKER_I18N_CODE') && !empty(JQUERY_DATEPICKER_I18N_CODE)) {
    ?>

    <script src="ext/vanillajs-datepicker/js/locales/<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>.js"></script>
    <script>
      if (typeof datepicker !== 'undefined') {
        datepicker.setOptions({
          language: '<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>'
        });
      }
    </script>

    <?php
  }
}
?>

<?php echo $oscTemplate->getBlocks('footer_scripts'); ?>

</body>
</html>
