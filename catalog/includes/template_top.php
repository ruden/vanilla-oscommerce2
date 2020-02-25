<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  $oscTemplate->buildBlocks();

  if (!$oscTemplate->hasBlocks('boxes_column_left')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }

  if (!$oscTemplate->hasBlocks('boxes_column_right')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="shortcut icon" href="favicon.ico">

<script src="ext/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="ext/Tobi/css/tobi.min.css">
<script src="ext/Tobi/js/tobi.min.js"></script>
<link rel="stylesheet" href="ext/bootstrap/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<body>

<header class="header container">
  <?php require('includes/header.php'); ?>
</header>

<nav aria-label="breadcrumb" class="container">
  <?php echo $breadcrumb->trail(); ?>
</nav>

<main class="main container">

<?php
  if ($messageStack->size('header') > 0) {
    echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
  }

  if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($_GET['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($_GET['info_message']) && tep_not_null($_GET['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($_GET['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>

  <div class="row">

  <?php
  if ($oscTemplate->hasBlocks('boxes_column_left')) {
    ?>

    <div id="columnLeft" class="col-md-2">
      <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
    </div>

    <?php
  }
  ?>

    <div class="col">


