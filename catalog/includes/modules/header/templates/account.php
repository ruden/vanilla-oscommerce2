<div id="headerShortcuts">
  <?php
  echo tep_draw_button(HEADER_TITLE_CART_CONTENTS . ($cart_count_contents > 0 ? ' (' . $cart_count_contents . ')' : ''), 'cart', tep_href_link('shopping_cart.php')) .
    tep_draw_button(HEADER_TITLE_CHECKOUT, 'triangle-1-e', tep_href_link('checkout_shipping.php', '', 'SSL')) .
    tep_draw_button(HEADER_TITLE_MY_ACCOUNT, 'person', tep_href_link('account.php', '', 'SSL'));

  if (tep_session_is_registered('customer_id')) {
    echo tep_draw_button(HEADER_TITLE_LOGOFF, null, tep_href_link('logoff.php', '', 'SSL'));
  }
  ?>
</div>