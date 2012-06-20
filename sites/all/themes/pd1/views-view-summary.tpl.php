<?php
// $Id: views-view-summary.tpl.php,v 1.6 2009/01/07 19:21:34 merlinofchaos Exp $
/**
 * @file views-view-summary.tpl.php
 * Default simple view template to display a list of summary lines
 *
 * @ingroup views_templates
 */
?>

<?php if ($view->vid == 5): ?>

<div class="item-list">
  <ul class="views-summary">
  <?php foreach ($rows as $row): ?>
    <?php $rowurl = "node/".$row->node_data_field_pub_ref_field_pub_ref_nid; ?>
    <li><?php print l($row->link,$rowurl); ?>
      <?php if (!empty($options['count'])): ?>
        (<?php print $row->count?>)
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
  </ul>
</div>

<?php else: ?>

<div class="item-list">
  <ul class="views-summary">
  <?php foreach ($rows as $row): ?>

<div class="hidey">nid2: <?php print $row->node_data_field_pub_ref_field_pub_ref_nid; ?></div>

    <li><a href="<?php print $row->url; ?>"><?php print $row->link; ?></a>
      <?php if (!empty($options['count'])): ?>
        (<?php print $row->count?>)
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
  </ul>
</div>

<?php endif; ?>
