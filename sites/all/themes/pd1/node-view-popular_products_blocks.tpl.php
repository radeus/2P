<div class="node-slideshow">

  <div class="img">
    <?php $fpath = $node->field_image_cache['0']['filepath']; ?>
    <?php $imgcch = theme('imagecache', 'front_rotator_thumbs', $fpath) ?>
	<?php print l($imgcch,'node/'.$node->nid, array('html'=>TRUE)); ?>
  </div>

  <div class="info">
    <?php if ($node->field_short_title['0']['value']): ?>
      <?php $this_title = $node->field_short_title['0']['value']; ?>
    <?php else: ?>
      <?php $this_title = $node->title; ?>
    <?php endif; ?>
    <h3><?php print l($this_title,'node/'.$node->nid); ?></h3>

    <div class="node-body"><?php print $node->content['body']['#value']; ?></div>
    <div class="more-details"><?php print l('more details','node/'.$node->nid); ?></div>
  </div>

  <div class="row2">
    <td class="price"><?php print $node->content['display_price']['#value']; ?></td>
    <td class="button"><?php print $node->content['add_to_cart']['#value']; ?></td>
  </div>

</div> <!-- content -->


<!-- ?php $chars = 10; ? -->
<!-- ?php $prerest = $node->content['body']['#value']; ? -->
<!-- ?php $rest = substr($prerest, 0, $chars); ? -->
<!-- ?php print $rest; ? -->
 