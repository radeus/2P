<?php
// $Id: node.tpl.php,v 1.4 2008/09/15 08:11:49 johnalbin Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
?>

<?php print $node->field_header_image['0']['view']; ?>

  <?php if ($content): ?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>">
<div class="node-top">
<div class="node-bottom">
<div class="node-inner clear-block">

<?php if ($node->type == 'distributor' or $node->type == 'publisher'): ?>
  <div id="CT-icon">
	<?php if ($node->type == 'distributor'): ?>
	  <img src="<?php print base_path().path_to_theme(); ?>/img/truck.png" alt="" />
	<?php elseif ($node->type == 'publisher'): ?>
	<?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($node->type == 'distributor'): ?>
  <h4><?php print l('view all distributors','distributor'); ?></h4>
<?php endif; ?>

  <?php print $picture; ?>

  <?php if (!$page): ?>
    <h2 class="title">
      <a href="<?php print $node_url; ?>" title="<?php print $title ?>"><?php print $title; ?></a>
    </h2>
  <?php endif; ?>

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <?php if ($submitted or $terms): ?>
    <div class="meta">
      <?php if ($submitted): ?>
        <div class="submitted">
          <?php print $submitted; ?>
        </div>
      <?php endif; ?>

      <?php if ($terms): ?>
        <div class="terms terms-inline"><?php print t(' in ') . $terms; ?></div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="content">

    <?php if ($node->type == 'd_hanger'): ?>
  <?php global $user; ?>
  <?php if ($user->uid == 1): ?>
	<p>Note to admin:
	<p>Where is a link to Wheels MFG? King Cage? '/brands'???
	<p>Make the derailler hangers page look like this site:
	<p><a href="http://www.handgunsforsale.net/">handgunsforsale.net</a></p>
  <?php endif; ?>


      <p>Sorry about the mess! I'm just beginning to add derailleur hangers to the site. My goal is to have all the hangers added, along with what bikes they fit by early 2011. Feel free to order the ones you see. The picture will always be correct, the model info is the best that I can find. Trust the picture over the description. If you aren't sure:</p>
      <ul>
        <li>take off your back wheel</li>
        <li>take pictures of the hanger from both sides</li>
        <li>send both pictures and the make, model, and year of your bike</li>
      </ul>

      <h3>This derailleur hanger fits the following bikes:</h3>
	  <?php print views_embed_view('hanger_fits_models', 'block_1', $tid); ?>

    <?php endif; ?>


    <?php print $content; ?>


<?php if ($user->uid > 0): ?>
  <?php if ($node->type == 'event' OR $node->type == 'maps' OR $node->type == 'books' ): ?>
    <h4>embed the map for these node types as well!</h4>
  <?php endif; ?>
<?php endif; ?>

<div class="phone">
  <?php
    if ($node->location['phone']) {
      $shop_phone = $node->location['phone'];
      $form_phone = preg_replace('/(\d{3})(\d{3})(\d{4})/', "$1-$2-$3", $shop_phone);
    }
  ?>
  <?php if ($shop_phone && $node->location['country'] == 'us'): ?>
    <strong>phone:</strong> <?php print $form_phone; ?>
  <?php elseif ($shop_phone): ?>
    <strong>Phone:</strong> <?php print $node->location['phone']; ?>
  <?php endif; ?>
</div>

  </div>

<?php  global $user; ?>
<?php if ($user->uid == '1'): ?>
  <p>Change to if in roles admin (currently only user 1 sees)</p>
  <p>use ?q=address for bike shops, and ?q=lat,long for events,TH,maps,books</p>
  <p>Map has issues. See <a href="http://code.google.com/apis/maps/documentation/staticmaps/">Google Static Maps API</a> for fix, or <a href="http://mapki.com/wiki/Google_Map_Parameters">GMAP parameters</a> and some more <a href="http://www.google.com/support/forum/p/maps/thread?tid=6b81c8a171a22161&hl=en">details here</a>. Also, can i show different icons for shops, TH, and events?</p>
<?php endif; ?>

<?php if ($node->type == 'bike_shop' || $node->type == 'event'): ?>
  <?php $glat = $node->location['latitude']; ?>
  <?php $glon = $node->location['longitude']; ?>

<iframe width="563" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/?q=<?php print $glat.','.$glon.'('.$node->title.')'; ?>&amp;ll=<?php print $glat.','.$glon; ?>&amp;ll=<?php print $glat.','.$glon; ?>&amp;ie=UTF8&amp;z=14&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/?q=<?php print $glat.','.$glon.'('.$node->title.')'; ?>&amp;ie=UTF8&amp;z=14&amp;ll=<?php print $glat.','.$glon; ?>&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>

<?php endif; ?>


<?php if ($user->uid == '1'): ?>
  <?php if ($node->type == 'maps' ): ?>
    <p>all products need the distributor field hidden to most users via user->role</p>
  <?php endif; ?>


  <?php if($node->type == 'event' ): ?>
    <div id="edit-statement">
      <p>Show only if date is in the past</p>
      <p>Do you know the upcoming date?</p>
      <p>Edit it yourself and help us promote this event!</p>
      <p>Instructions and link to edit this page</p>
      <p>Is this date wrong? (event only)</p>
    </div>
  <?php endif; ?>

  <?php if($node->type == 'bike-shop' or $node->type == 'event' or $node->type == 'trail'): ?>
    <p>Update wrong information for this <?php print $node->type; ?> (link to edit page)</p>
    <p>Is this duplicate content? (contact form? something where they can add a link to the two pages?)</p>
  <?php endif; ?>

<?php endif; ?>


<?php if($node->type == 'bin_loc' ): ?>
  <div class="category-image">

<h1>Convert this back to a view?</h1>

<h2>all i need are linked titles and stock quantity</h2>

<?php foreach ($node->field_bin_backref as $id => $backref): ?>
  <div class="handmade-teaser">
    <?php $brefnode = node_load($backref['nid']); ?>
    
    <?php $fpath = $brefnode->field_image_cache['0']['filepath']; ?>
    <a href="<?php print base_path().'node/'.$backref['nid']; ?>">
      <?php print theme('imagecache', '100x90_inside', $fpath, ''); ?>
    </a>

    <h2><?php print l($brefnode->title,'node/'.$backref['nid']); ?></h2>
    <?php print $brefnode->teaser; ?>
    <p>need price and buy now button, anything else?</p>
  </div>
<?php endforeach; ?>

  </div>
<?php endif; ?>

<div><?php print $links; ?></div>

</div> <!-- node-inner -->
</div> <!-- node-bottom -->
</div> <!-- node-top -->
</div> <!-- node -->

  <?php endif; ?>


<?php $commastring = null; ?>
<?php if($node->type == 'publisher' ): ?>
  <?php foreach ($node->field_pub_backref as $id => $backref): ?>
	<?php $commastring .= $backref['nid'].","; ?>
  <?php endforeach; ?>
<?php endif; ?>
<?php $commastring = substr($commastring,0,-1); ?>
<?php if($node->type == 'publisher' ): ?>
  <h2 class="title">Maps and Guidebooks from <?php print $node->title; ?></h2>
  <div class="category-image">  
	<?php print views_embed_view('pubman_backref', 'block_1', $commastring); ?>
  </div>
<?php endif; ?>

<?php $commastring = null; ?>
<?php if($node->type == 'brand' ): ?>
  <?php foreach ($node->field_brand_backref as $id => $backref): ?>
	<?php $commastring .= $backref['nid'].","; ?>
  <?php endforeach; ?>
<?php endif; ?>
<?php $commastring = substr($commastring,0,-1); ?>
<?php if($node->type == 'brand' ): ?>
  <h2 class="title">All <?php print $node->title; ?> Products</h2>
  <div class="category-image">  
	<?php print views_embed_view('pubman_backref', 'block_1', $commastring); ?>
  </div>
<?php endif; ?>


<?php $commastring = null; ?>
<?php if($node->type == 'distributor' ): ?>
  <?php foreach ($node->field_distro_backref as $id => $backref): ?>
	<?php $commastring .= $backref['nid'].","; ?>
  <?php endforeach; ?>
<?php endif; ?>
<?php $commastring = substr($commastring,0,-1); ?>
<?php if($node->type == 'distributor' ): ?>
  <h2 class="title">From this Distributor:</h2>
  <div class="category-image">  
	<?php print views_embed_view('pubman_backref', 'block_1', $commastring); ?>
  </div>
<?php endif; ?>
