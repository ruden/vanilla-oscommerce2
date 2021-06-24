<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/
?>

<div id="btStartDashboard" style="width: 100%;">
  <div style="float: left; width: 50%;">
    <div style="padding: 2px;">
      <h3 class="bt-panel-header-info"><?php echo $OSCOM_Braintree->getDef('online_documentation_title'); ?></h3>
      <div class="bt-panel bt-panel-info">
        <?php echo $OSCOM_Braintree->getDef('online_documentation_body', array('button_online_documentation' => $OSCOM_Braintree->drawButton($OSCOM_Braintree->getDef('button_online_documentation'), 'https://library.oscommerce.com/Package&braintree&oscom23', 'info', 'target="_blank"'))); ?>
      </div>
    </div>
  </div>

  <div style="float: left; width: 50%;">
    <div style="padding: 2px;">
      <h3 class="bt-panel-header-warning"><?php echo $OSCOM_Braintree->getDef('online_forum_title'); ?></h3>
      <div class="bt-panel bt-panel-warning">
        <?php echo $OSCOM_Braintree->getDef('online_forum_body', array('button_online_forum' => $OSCOM_Braintree->drawButton($OSCOM_Braintree->getDef('button_online_forum'), 'http://forums.oscommerce.com/forum/109-braintree/', 'warning', 'target="_blank"'))); ?>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    $('#btStartDashboard > div:nth-child(2)').each(function () {
      if ($(this).prev().height() < $(this).height()) {
        $(this).prev().height($(this).height());
      } else {
        $(this).height($(this).prev().height());
      }
    });

    OSCOM.APP.BRAINTREE.versionCheck();
  });
</script>
