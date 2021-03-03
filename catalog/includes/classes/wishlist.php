<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

class wishList {
  public $list;

  public function __construct() {
    $this->reset();
  }

  public function restore_lists() {
    global $customer_id;

    if (!isset($_SESSION['customer_id'])) {
      return false;
    }

    if (is_array($this->list)) {
      foreach (array_keys($this->list) as $products_id) {
        $product_query = tep_db_query("select products_id from customers_wishlist where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
        if (!tep_db_num_rows($product_query)) {
          tep_db_query("insert into customers_wishlist (customers_id, products_id, customers_wishlist_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . date('Ymd') . "')");
          if (isset($this->list[$products_id]['attributes'])) {
            foreach ($this->list[$products_id]['attributes'] as $option => $value) {
              tep_db_query("insert into customers_wishlist_attributes (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')");
            }
          }
        }
      }
    }

    $this->reset();

    $products_query = tep_db_query("select products_id from customers_wishlist where customers_id = '" . (int)$customer_id . "'");
    while ($products = tep_db_fetch_array($products_query)) {
      $this->list[$products['products_id']] = array();

      $attributes_query = tep_db_query("select products_options_id, products_options_value_id from customers_wishlist_attributes where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
      while ($attributes = tep_db_fetch_array($attributes_query)) {
        $this->list[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
      }
    }
  }

  public function reset($reset_database = false) {
    global $customer_id;

    $this->list = [];

    if (isset($_SESSION['customer_id']) && $reset_database == true) {
      tep_db_query("delete from customers_wishlist where customers_id = '" . (int)$customer_id . "'");
      tep_db_query("delete from customers_wishlist_attributes where customers_id = '" . (int)$customer_id . "'");
    }
  }

  public function add_list($products_id, $attributes = '') {
    global $customer_id;

    $products_id_string = tep_get_uprid($products_id, $attributes);
    $products_id = tep_get_prid($products_id_string);

    $attributes_pass_check = true;

    if (is_array($attributes) && !empty($attributes)) {
      foreach ($attributes as $option => $value) {
        if (!is_numeric($option) || !is_numeric($value)) {
          $attributes_pass_check = false;
          break;
        } else {
          $check_query = tep_db_query("select products_attributes_id from products_attributes where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "' limit 1");
          if (tep_db_num_rows($check_query) < 1) {
            $attributes_pass_check = false;
            break;
          }
        }
      }
    } elseif (tep_has_product_attributes($products_id)) {
      $attributes_pass_check = false;
    }

    if (is_numeric($products_id) && $attributes_pass_check) {
      $check_product_query = tep_db_query("select products_status from products where products_id = '" . (int)$products_id . "'");
      $check_product = tep_db_fetch_array($check_product_query);

      if (($check_product !== false) && ($check_product['products_status'] == '1')) {
        $this->list[$products_id_string] = [];
        if (isset($_SESSION['customer_id'])) {
          tep_db_query("insert into customers_wishlist (customers_id, products_id, customers_wishlist_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id_string) . "', '" . date('Ymd') . "')");
        }

        if (is_array($attributes)) {
          foreach ($attributes as $option => $value) {
            $this->list[$products_id_string]['attributes'][$option] = $value;
            if (isset($_SESSION['customer_id'])) {
              tep_db_query("insert into customers_wishlist_attributes (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id_string) . "', '" . (int)$option . "', '" . (int)$value . "')");
            }
          }
        }
      }
    }
  }

  public function count_list() {
    $total_items = 0;

    if (is_array($this->list)) {
      $total_items = count($this->list);
    }

    return $total_items;
  }

  public function in_list($products_id) {
    foreach (array_keys($this->list) as $key) {
      if ($products_id == tep_get_prid($key)) {
        return true;
      }
    }

    return false;
  }

  public function remove($products_id) {
    global $customer_id;

    unset($this->list[$products_id]);

    if (isset($_SESSION['customer_id'])) {
      tep_db_query("delete from customers_wishlist where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
      tep_db_query("delete from customers_wishlist_attributes where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
    }
  }

  public function attributes_price($products_id) {
    $attributes_price = 0;

    if (isset($this->list[$products_id]['attributes'])) {
      foreach ($this->list[$products_id]['attributes'] as $option => $value) {
        $attribute_price_query = tep_db_query("select options_values_price, price_prefix from products_attributes where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
        $attribute_price = tep_db_fetch_array($attribute_price_query);
        if ($attribute_price['price_prefix'] == '+') {
          $attributes_price += $attribute_price['options_values_price'];
        } else {
          $attributes_price -= $attribute_price['options_values_price'];
        }
      }
    }

    return $attributes_price;
  }

  public function get_products() {
    global $languages_id;

    if (!is_array($this->list)) {
      return false;
    }

    $products_array = [];
    foreach (array_keys($this->list) as $products_id) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_tax_class_id, if(s.status, s.specials_new_products_price, null) as specials_new_products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_id = '" . (int)$products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
      if ($products = tep_db_fetch_array($products_query)) {
        if (!empty($products['specials_new_products_price'])) {
          $products_price = $products['specials_new_products_price'];
        } else {
          $products_price = $products['products_price'];
        }

        $products_array[] = array('id' => $products_id,
                                  'name' => $products['products_name'],
                                  'model' => $products['products_model'],
                                  'image' => $products['products_image'],
                                  'price' => $products_price,
                                  'final_price' => ($products_price + $this->attributes_price($products_id)),
                                  'tax_class_id' => $products['products_tax_class_id'],
                                  'attributes' => (isset($this->list[$products_id]['attributes']) ? $this->list[$products_id]['attributes'] : ''));
      }
    }

    return $products_array;
  }

  public function update_list() {
    foreach (array_keys($this->list) as $products_id) {
      $products_query = tep_db_query("select 1 from products where products_id = '" . (int)$products_id . "' and products_status = 1");

      if (!tep_db_num_rows($products_query)) {
        $this->remove($products_id);
      }
    }
  }

  public function add_products() {
    global $session_started, $PHP_SELF;

    if (isset($_POST['wishlist'])) {
      if ($session_started == false) {
        tep_redirect(tep_href_link('cookie_usage.php'));
      }

      if (DISPLAY_CART == 'true') {
        $goto = 'wishlist.php';
      } else {
        $goto = $PHP_SELF;
      }

      if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
        $attributes = isset($_POST['id']) ? $_POST['id'] : '';
        $this->add_list($_POST['products_id'], $attributes);
      }

      tep_redirect(tep_href_link($goto));
    }
  }
}
