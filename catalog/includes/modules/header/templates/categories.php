<div class="t-categories">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">


    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <a class="d-lg-none" href="<?php echo tep_href_link('index.php'); ?>"><?php echo tep_image('images/store_logo.png', STORE_NAME); ?></a>

    <ul class="mb-0 list-inline d-lg-none">
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('advanced_search.php'); ?>">
          <svg>
            <use href="#svg-icon-search"/>
          </svg>
        </a>
      </li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('login.php'); ?>">
          <svg>
            <use href="#svg-icon-user"/>
          </svg>
        </a>
      </li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('wishlist.php'); ?>">
          <svg>
            <use href="#svg-icon-heart"/>
          </svg>
        </a>
      </li>
      <li class="list-inline-item">
        <a href="<?php echo tep_href_link('shopping_cart.php'); ?>">
          <svg>
            <use href="#svg-icon-shopping-cart"/>
          </svg>
        </a>
      </li>
    </ul>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <ul class="navbar-nav mr-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>

      </ul>
    </div>

  </nav>
</div>


<?php ?>
