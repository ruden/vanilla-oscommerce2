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

<div id="bodyWrapper" class="container_<?php echo $oscTemplate->getGridContainerWidth(); ?>">

<?php require('includes/header.php'); ?>

<div id="bodyContent" class="grid_<?php echo $oscTemplate->getGridContentWidth(); ?> <?php echo ($oscTemplate->hasBlocks('boxes_column_left') ? 'push_' . $oscTemplate->getGridColumnWidth() : ''); ?>">
