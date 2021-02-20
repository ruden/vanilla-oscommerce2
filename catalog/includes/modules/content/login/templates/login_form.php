<div class="col-lg-6">
  <h2><?php echo MODULE_CONTENT_LOGIN_HEADING_RETURNING_CUSTOMER; ?></h2>

  <p><?php echo MODULE_CONTENT_LOGIN_TEXT_RETURNING_CUSTOMER; ?></p>

  <?php echo tep_draw_form('login', tep_href_link('login.php', 'action=process'), 'post', '', true); ?>

  <div class="mb-3">
    <label class="form-label" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
    <?php echo tep_draw_input_field('email_address', null, 'id="email-address" class="form-control" required', 'email'); ?>
  </div>
  <div class="mb-3">
    <label class="form-label" for="password"><?php echo ENTRY_PASSWORD; ?></label>
    <?php echo tep_draw_password_field('password', null, 'id="password" class="form-control"'); ?>
  </div>

  <div class="mb-3"><?php echo '<a href="' . tep_href_link('password_forgotten.php') . '">' . MODULE_CONTENT_LOGIN_TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></div>

  <div class="text-end"><?php echo tep_draw_button(IMAGE_BUTTON_LOGIN, 'key', null, 'btn-primary'); ?></div>

  </form>
</div>
