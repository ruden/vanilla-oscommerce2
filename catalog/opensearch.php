<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

header('Content-Type: text/xml');

require('includes/application_top.php');

if (!defined('MODULE_HEADER_TAGS_OPENSEARCH_STATUS') || (MODULE_HEADER_TAGS_OPENSEARCH_STATUS != 'True')) {
  exit;
}

echo '<?xml version="1.0"?>' . "\n";
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
  <ShortName><?php echo tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_SHORT_NAME); ?></ShortName>
  <Description><?php echo tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_DESCRIPTION); ?></Description>
  <?php
  if (!empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_CONTACT)) {
    echo '  <Contact>' . tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_CONTACT) . '</Contact>' . "\n";
  }

  if (!empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_TAGS)) {
    echo '  <Tags>' . tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_TAGS) . '</Tags>' . "\n";
  }

  if (!empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ATTRIBUTION)) {
    echo '  <Attribution>' . tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ATTRIBUTION) . '</Attribution>' . "\n";
  }

  if (MODULE_HEADER_TAGS_OPENSEARCH_SITE_ADULT_CONTENT == 'True') {
    echo '  <AdultContent>True</AdultContent>' . "\n";
  }

  if (!empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ICON)) {
    echo '  <Image height="16" width="16" type="image/x-icon">' . tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ICON) . '</Image>' . "\n";
  }

  if (!empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_IMAGE)) {
    echo '  <Image height="64" width="64" type="image/png">' . tep_output_string(MODULE_HEADER_TAGS_OPENSEARCH_SITE_IMAGE) . '</Image>' . "\n";
  }
  ?>
  <InputEncoding>UTF-8</InputEncoding>
  <Url type="text/html" method="get" template="<?php echo tep_href_link('advanced_search_result.php', 'keywords={searchTerms}', 'SSL', false); ?>"/>
</OpenSearchDescription>
<?php
require('includes/application_bottom.php');
?>
