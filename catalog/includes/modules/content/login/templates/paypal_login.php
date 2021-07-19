<div class="contentContainer <?php echo (OSCOM_APP_PAYPAL_LOGIN_CONTENT_WIDTH == 'Half') ? 'grid_8' : 'grid_16'; ?>">
  <h2><?php echo $cm_paypal_login->_app->getDef('module_login_template_title'); ?></h2>

  <div class="mb-3">

    <?php
    if (OSCOM_APP_PAYPAL_LOGIN_STATUS == '0') {
      ?>

      <div class="alert alert-danger d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <div>
          <?php echo $cm_paypal_login->_app->getDef('module_login_template_sandbox_alert'); ?>
        </div>
      </div>

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
