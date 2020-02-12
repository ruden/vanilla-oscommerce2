<div class="contentText">
  <ul style="margin: 0; padding: 0; text-align: center;">

    <?php
    foreach ($manufacturers_array as $manufacturers) {
      ?>

      <li style="display: inline-block; padding: 5px;">

        <?php
        echo '<a href="' . tep_href_link('index.php', 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . tep_image('images/' . $manufacturers['manufacturers_image'], $manufacturers['manufacturers_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link('index.php', 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . $manufacturers['manufacturers_name'] . '</a>';
        ?>

      </li>

      <?php
    }
    ?>

  </ul>
</div>