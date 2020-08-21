<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

$oscTemplate->buildBlocks();
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta charset="<?php echo CHARSET; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
  <base href="<?php echo ($request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
  <link rel="shortcut icon" href="favicon.ico">

  <link rel="stylesheet" href="ext/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="stylesheet.css">

  <?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<body>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none">
  <defs>

    <symbol id="svg-icon-user" viewBox="0 0 24 24">
      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </symbol>

    <symbol id="svg-icon-search" viewBox="0 0 24 24">
      <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
    </symbol>

    <symbol id="svg-icon-shopping-cart" viewBox="0 0 24 24">
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="20" cy="21" r="1"></circle>
      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </symbol>

    <symbol id="svg-icon-heart" viewBox="0 0 24 24">
      <path d="M12,21 L10.55,19.7051771 C5.4,15.1242507 2,12.1029973 2,8.39509537 C2,5.37384196 4.42,3 7.5,3 C9.24,3 10.91,3.79455041 12,5.05013624 C13.09,3.79455041 14.76,3 16.5,3 C19.58,3 22,5.37384196 22,8.39509537 C22,12.1029973 18.6,15.1242507 13.45,19.7149864 L12,21 Z"></path>
    </symbol>

  </defs>
</svg>

<header class="header">
  <div class="mb-3">
    <?php require('includes/header.php'); ?>
  </div>
</header>

<?php
if ($messageStack->size('header') > 0) {
  ?>

  <div class="p-1 bg-danger text-white text-center mb-3">
    <?php echo $messageStack->output('header'); ?>
  </div>

  <?php
}

if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
  ?>

  <div class="p-1 bg-danger text-white text-center mb-3">
    <?php echo htmlspecialchars(stripslashes(urldecode($_GET['error_message']))); ?>
  </div>

  <?php
}

if (isset($_GET['info_message']) && tep_not_null($_GET['info_message'])) {
  ?>

  <div class="p-1 bg-info text-white text-center mb-3">
    <?php echo htmlspecialchars(stripslashes(urldecode($_GET['info_message']))); ?>
  </div>

  <?php
}
?>

<main class="main">
  <div class="container">

    <ul class="list-inline">
      <?php echo $breadcrumb->trail('&#47;'); ?>
    </ul>

    <div class="row">

      <?php
      if ($oscTemplate->hasBlocks('boxes_column_left') && isset($category_depth) && $category_depth == 'products') {
        ?>

        <aside class="aside col-lg-2 d-none d-lg-block">
          <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
        </aside>

        <?php
      }
      ?>

      <div class="col">
        <div class="<?php echo str_replace('_', '-', basename($PHP_SELF, '.php')); ?>">
