<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class splitPageResults {
  public $sql_query;
  public $number_of_rows;
  public $current_page_number;
  public $number_of_pages;
  public $number_of_rows_per_page;
  public $page_name;

  /* class constructor */
  public function __construct($query, $max_rows, $count_key = '*', $page_holder = 'page') {
    $this->sql_query = $query;
    $this->page_name = $page_holder;

    if (isset($_GET[$page_holder])) {
      $page = $_GET[$page_holder];
    } elseif (isset($_POST[$page_holder])) {
      $page = $_POST[$page_holder];
    } else {
      $page = '';
    }

    if (empty($page) || !is_numeric($page)) {
      $page = 1;
    }
    $this->current_page_number = $page;

    $this->number_of_rows_per_page = $max_rows;

    $pos_to = strlen($this->sql_query);
    $pos_from = stripos($this->sql_query, ' from', 0);

    $pos_group_by = stripos($this->sql_query, ' group by', $pos_from);
    if (($pos_group_by < $pos_to) && ($pos_group_by !== false)) {
      $pos_to = $pos_group_by;
    }

    $pos_having = stripos($this->sql_query, ' having', $pos_from);
    if (($pos_having < $pos_to) && ($pos_having !== false)) {
      $pos_to = $pos_having;
    }

    $pos_order_by = stripos($this->sql_query, ' order by', $pos_from);
    if (($pos_order_by < $pos_to) && ($pos_order_by !== false)) {
      $pos_to = $pos_order_by;
    }

    if (stripos($this->sql_query, 'distinct') === true || stripos($this->sql_query, 'group by') === true) {
      $count_string = 'distinct ' . tep_db_input($count_key);
    } else {
      $count_string = tep_db_input($count_key);
    }

    $count_query = tep_db_query("select count(" . $count_string . ") as total " . substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));
    $count = tep_db_fetch_array($count_query);

    $this->number_of_rows = $count['total'];

    $this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

    if ($this->current_page_number > $this->number_of_pages) {
      $this->current_page_number = $this->number_of_pages;
    }

    $offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

    if ($this->number_of_rows == 0) {
      http_response_code(404);
    }

    $this->sql_query .= " limit " . $this->number_of_rows_per_page . " offset " . max($offset, 0);
  }

  /* class functions */

  // display split-page-number-links
  public function display_links($max_page_links, $parameters = '') {
    global $PHP_SELF, $request_type;

    $display_links_string = '<nav aria-label="' . sprintf(PREVNEXT_TITLE_PAGE_NO, $this->current_page_number) . '">
                               <ul class="pagination justify-content-center justify-content-md-end mb-0">';

    if (!empty($parameters) && (substr($parameters, -1) != '&')) {
      $parameters .= '&';
    }

    // previous button - not displayed on first page
    if ($this->current_page_number > 1) {
      $display_links_string .= '<li class="page-item"><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a></li>';
    }

    if ($this->current_page_number == 1) {
      $display_links_string .= '<li class="page-item disabled"><span class="page-link">' . PREVNEXT_BUTTON_PREV . '</span></li>';
    }

    // check if number_of_pages > $max_page_links
    $cur_window_num = intval($this->current_page_number / $max_page_links);
    if ($this->current_page_number % $max_page_links) {
      $cur_window_num++;
    }

    $max_window_num = intval($this->number_of_pages / $max_page_links);
    if ($this->number_of_pages % $max_page_links) {
      $max_window_num++;
    }

    // previous window of pages
    if ($cur_window_num > 1) {
      $display_links_string .= '<li class="page-item"><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a></li>';
    }

    // page nn button
    for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
      if ($jump_to_page == $this->current_page_number) {
        $display_links_string .= '<li class="page-item active"><span class="page-link">' . $jump_to_page . '</span></li>';
      } else {
        $display_links_string .= '<li class="page-item"><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a></li>';
      }
    }

    // next window of pages
    if ($cur_window_num < $max_window_num) {
      $display_links_string .= '<li class="page-item"><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a></li>';
    }

    // next button
    if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) {
      $display_links_string .= '<li class="page-item"><a class="page-link" href="' . tep_href_link($PHP_SELF, $parameters . 'page=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a></li>';
    }

    if (($this->current_page_number == $this->number_of_pages) && ($this->number_of_pages != 1)) {
      $display_links_string .= '<li class="page-item disabled"><span class="page-link">' . PREVNEXT_BUTTON_NEXT . '</span></li>';
    }

    $display_links_string .= '</ul>
                            </nav>';

    return $display_links_string;
  }

  // display number of total products found
  public function display_count($text_output) {
    $to_num = ($this->number_of_rows_per_page * $this->current_page_number);
    if ($to_num > $this->number_of_rows) {
      $to_num = $this->number_of_rows;
    }

    $from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

    if ($to_num == 0) {
      $from_num = 0;
    } else {
      $from_num++;
    }

    return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
  }
}
