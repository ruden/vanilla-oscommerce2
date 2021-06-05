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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="stylesheet.css">

  <?php echo $oscTemplate->getBlocks('header_tags'); ?>
</head>
<body>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none">
  <defs>
    <symbol id="svg-icon-user" viewBox="0 0 24 24">
      <circle fill="none" cx="12" cy="7" r="3"/><path d="M12 2C9.243 2 7 4.243 7 7s2.243 5 5 5 5-2.243 5-5S14.757 2 12 2zM12 10c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3S13.654 10 12 10zM21 21v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h2v-1c0-2.757 2.243-5 5-5h4c2.757 0 5 2.243 5 5v1H21z"/>
    </symbol>
    <symbol id="svg-icon-search" viewBox="0 0 24 24">
      <path d="M10,18c1.846,0,3.543-0.635,4.897-1.688l4.396,4.396l1.414-1.414l-4.396-4.396C17.365,13.543,18,11.846,18,10 c0-4.411-3.589-8-8-8s-8,3.589-8,8S5.589,18,10,18z M10,4c3.309,0,6,2.691,6,6s-2.691,6-6,6s-6-2.691-6-6S6.691,4,10,4z"/>
    </symbol>
    <symbol id="svg-icon-shopping-cart" viewBox="0 0 24 24">
      <path d="M21.822,7.431C21.635,7.161,21.328,7,21,7H7.333L6.179,4.23C5.867,3.482,5.143,3,4.333,3H2v2h2.333l4.744,11.385 C9.232,16.757,9.596,17,10,17h8c0.417,0,0.79-0.259,0.937-0.648l3-8C22.052,8.044,22.009,7.7,21.822,7.431z M17.307,15h-6.64 l-2.5-6h11.39L17.307,15z"/><circle cx="10.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/>
    </symbol>
    <symbol id="svg-icon-heart" viewBox="0 0 24 24">
      <path d="M12,4.595c-1.104-1.006-2.512-1.558-3.996-1.558c-1.578,0-3.072,0.623-4.213,1.758c-2.353,2.363-2.352,6.059,0.002,8.412 l7.332,7.332c0.17,0.299,0.498,0.492,0.875,0.492c0.322,0,0.609-0.163,0.792-0.409l7.415-7.415 c2.354-2.354,2.354-6.049-0.002-8.416c-1.137-1.131-2.631-1.754-4.209-1.754C14.513,3.037,13.104,3.589,12,4.595z M18.791,6.205 c1.563,1.571,1.564,4.025,0.002,5.588L12,18.586l-6.793-6.793C3.645,10.23,3.646,7.776,5.205,6.209 c0.76-0.756,1.754-1.172,2.799-1.172s2.035,0.416,2.789,1.17l0.5,0.5c0.391,0.391,1.023,0.391,1.414,0l0.5-0.5 C14.719,4.698,17.281,4.702,18.791,6.205z"/>
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

if (isset($_GET['error_message']) && !empty($_GET['error_message'])) {
  ?>

  <div class="p-3 mb-3 bg-danger text-white text-center">
    <?php echo htmlspecialchars(stripslashes(urldecode($_GET['error_message']))); ?>
  </div>

  <?php
}

if (isset($_GET['info_message']) && !empty($_GET['info_message'])) {
  ?>

  <div class="p-3 mb-3 bg-info text-white text-center">
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
      if ($oscTemplate->hasBlocks('boxes_column_left') && isset($cPath_array)) {
        ?>

        <aside class="aside col-lg-2">
          <?php echo $oscTemplate->getBlocks('boxes_column_left'); ?>
        </aside>

        <?php
      }
      ?>

      <div class="col">
        <div class="<?php echo str_replace('_', '-', basename($PHP_SELF, '.php')); ?>">
