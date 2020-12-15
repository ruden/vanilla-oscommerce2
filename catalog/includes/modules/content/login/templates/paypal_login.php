<div class="contentContainer <?php echo (OSCOM_APP_PAYPAL_LOGIN_CONTENT_WIDTH == 'Half') ? 'grid_8' : 'grid_16'; ?>">
  <h2><?php echo $cm_paypal_login->_app->getDef('module_login_template_title'); ?></h2>

  <div class="mb-3">

    <?php
    if (OSCOM_APP_PAYPAL_LOGIN_STATUS == '0') {
      ?>

      <div class="alert alert-danger"><?php echo $cm_paypal_login->_app->getDef('module_login_template_sandbox_alert'); ?></div>

      <?php
    }
    ?>

    <p><?php echo $cm_paypal_login->_app->getDef('module_login_template_content'); ?></p>

    <div id="PayPalLoginButton" class="text-end mb-3"></div>
  </div>
</div>

<script src="https://www.paypalobjects.com/js/external/api.js"></script>
<script>
  paypal.use(["login"], function (login) {
    login.render({

      <?php
      if (OSCOM_APP_PAYPAL_LOGIN_STATUS == '0') {
        echo '    "authend": "sandbox",';
      }

      if (OSCOM_APP_PAYPAL_LOGIN_THEME == 'Neutral') {
        echo '    "theme": "neutral",';
      }
      ?>

      "locale": "<?php echo $cm_paypal_login->_app->getDef('module_login_language_locale'); ?>",
      "appid": "<?php echo (OSCOM_APP_PAYPAL_LOGIN_STATUS == '1') ? OSCOM_APP_PAYPAL_LOGIN_LIVE_CLIENT_ID : OSCOM_APP_PAYPAL_LOGIN_SANDBOX_CLIENT_ID; ?>",
      "scopes": "<?php echo implode(' ', $use_scopes); ?>",
      "containerid": "PayPalLoginButton",
      "returnurl": "<?php echo str_replace('&amp;', '&', tep_href_link('login.php', 'action=paypal_login', 'SSL', false)); ?>"
    });
  });
</script>
