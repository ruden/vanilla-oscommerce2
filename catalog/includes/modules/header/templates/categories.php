<div class="t-categories">
  <link rel="stylesheet" href="includes/modules/header/templates/categories/bootstrap-4/jquery.smartmenus.bootstrap-4.css">

  <nav class="navbar navbar-expand-lg navbar-light bg-light">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <a class="d-lg-none" href="<?php echo tep_href_link('index.php'); ?>"><?php echo tep_image('images/store_logo.png', STORE_NAME); ?></a>

    <ul class="mb-0 list-inline d-lg-none">
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('advanced_search.php'); ?>">
          <svg>
            <use href="#svg-icon-search"/>
          </svg>
        </a></li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('login.php'); ?>">
          <svg>
            <use href="#svg-icon-user"/>
          </svg>
        </a></li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('wishlist.php'); ?>">
          <svg>
            <use href="#svg-icon-heart"/>
          </svg>
        </a></li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('shopping_cart.php'); ?>">
          <svg>
            <use href="#svg-icon-shopping-cart"/>
          </svg>
        </a></li>
    </ul>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">

      <ul class="nav navbar-nav mr-auto">

        <?php
        if ($special_products) {
          ?>

          <li class="nav-item">
            <a class="nav-link text-danger" href="<?php echo tep_href_link('specials.php'); ?>"><?php echo MODULE_HEADER_CATEGORIES_TEXT_SALE; ?></a></li>

          <?php
        }
        ?>

        <?php echo $categories_list; ?>

      </ul>
      
    </div>
  </nav>

</div>
