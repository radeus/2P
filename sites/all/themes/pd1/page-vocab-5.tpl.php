<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>

  <?php $full_title = $title.' Mountain Bike Maps, Guides & Info'; ?>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyled Content in IE */ ?> </script>
</head>

<?php $tid = arg(2); ?>

<body class="<?php print $body_classes; ?> tid-<?php print $tid; ?> term-page">

<div id="vista">
<div id="hnav">

  <div id="page"><div id="page-inner">

    <a name="top" id="navigation-top"></a>
    <?php if ($primary_links or $secondary_links or $navbar): ?>
      <div id="skip-to-nav"><a href="#navigation"><?php print t('Skip to Navigation'); ?></a></div>
    <?php endif; ?>
    <div id="header"><div id="header-inner" class="clear-block">

      <?php if ($header or $search_box): ?>
        <div id="header-blocks" class="region region-header">
          <?php if ($search_box): ?>
            <div id="search-box">
              <?php print $search_box; ?>
            </div> <!-- /#search-box -->
          <?php print $header; ?>
          <?php endif; ?>

        </div> <!-- /#header-blocks -->
      <?php endif; ?>

    </div></div> <!-- /#header-inner, /#header -->

    <div id="main"><div id="main-inner" class="clear-block<?php if ($primary_links or $secondary_links or $navbar) { print ' with-navbar'; } ?>">

      <div id="content"><div id="content-inner">

        <?php if ($mission): ?>
          <div id="mission"><?php print $mission; ?></div>
        <?php endif; ?>

        <?php if ($content_top): ?>
          <div id="content-top" class="region region-content_top">
            <?php print $content_top; ?>
          </div> <!-- /#content-top -->
        <?php endif; ?>

        <?php if ($breadcrumb or $title or $tabs or $help or $messages): ?>
          <div id="content-header">
            <?php print $breadcrumb; ?>
            <?php if ($title): ?>
              <h1 class="title"><?php print $full_title; ?></h1>
            <?php endif; ?>
            <?php print $messages; ?>
            <?php if ($tabs): ?>
              <div class="tabs"><?php print $tabs; ?></div>
            <?php endif; ?>
            <?php print $help; ?>
          </div> <!-- /#content-header -->
        <?php endif; ?>

<!-- don't display site slogan -->
      <?php if ($xxxsite_slogan): ?>
        <?php if ($site_slogan): ?>
          <h4 id="site-slogan"><?php print $site_slogan; ?></h4>
        <?php endif; ?>
      <?php endif; ?>

        <div id="content-area">

<?php if ($tid == 44 || $tid == 113 || $tid == 114 || $tid == 115 || $tid == 116 || $tid == 125 || $tid == 126): ?>

<h4>All pages on this site are categorized by the lowest level region. Please make a selection below.</h4>

<?php $taxarray = taxonomy_get_tree(5, $tid); ?>

<div id="sub-tax-list">
  <?php foreach ($taxarray as $id => $subtax): ?>
    <?php if ($subtax->depth == 0): ?>
      <h3><?php print l($subtax->name,'taxonomy/term/'.$subtax->tid); ?></h3>
    <?php endif; ?>
  <?php endforeach; ?>
</div>

<!--
<p>current list of parent terms:</p>
<pre>
  44 usa
  113 europe
  114 canada
  115 latin america
  116 oceania
  125 africa
  126 asia
</pre>
-->

<?php else: ?>

	<div class="two-sidebars"><div class="node"><div class="node-inner">
	  <?php global $user; ?>
	  <?php if ($user->uid == 1): ?>
	    <?php $lower_title = strtolower ($title); ?>
	      <?php $this_search = array(" ","&amp;-"); ?>
	      <?php $this_replace = array("-",""); ?>
	    <?php $region_title = str_replace ($this_search,$this_replace,$lower_title); ?>
	    [[ <?php print l('view this region to edit','content/'.$region_title); ?> ]]
	  <?php endif; ?>
	  <?php print views_embed_view('regional_breakdown_terms', 'block_4', $tid); ?>
	</div></div></div>


<!--//  Three column 3x33 Gpanel   //-->
  <div class="three-col-3x33 gpanel clear-block">
    <div class="section regional-products col-1 first"><div class="inner">
      <h2 class="title">Maps & Guidebooks for <?php print $title ?></h2>
	  <?php print views_embed_view('regional_breakdown_terms', 'block_1', $tid); ?>
    </div></div>
    <div class="section regional-shops col-2"><div class="inner">
      <h2 class="title">Bike Shops in <?php print $title ?></h2>
	  <?php print views_embed_view('regional_breakdown_terms', 'block_2', $tid); ?>
    </div></div>
    <div class="section regional-events col-3 last"><div class="inner">
      <h2 class="title"><?php print $title ?> Events</h2>
	  <?php print views_embed_view('regional_breakdown_terms', 'block_3', $tid); ?>
    </div></div>
  </div>
<!--/end Gpanel-->

	<div class="two-sidebars"><div class="node"><div class="node-inner">
	  <?php global $user; ?>
	  <?php if ($user->uid == 1): ?>
	    <?php $region_title = strtolower ($title); ?>
	    [[ <?php print l('edit this section','content/'.$region_title); ?> ]]
	  <?php endif; ?>
	  <?php print views_embed_view('regional_breakdown_terms', 'block_5', $tid); ?>
	</div></div></div>

<?php endif; ?>

        </div>

        <?php if ($feed_icons): ?>
          <div class="feed-icons"><?php print $feed_icons; ?></div>
        <?php endif; ?>

        <?php if ($content_bottom): ?>
          <div id="content-bottom" class="region region-content_bottom">
            <?php print $content_bottom; ?>
          </div> <!-- /#content-bottom -->
        <?php endif; ?>

      </div></div> <!-- /#content-inner, /#content -->

      <?php if ($primary_links or $secondary_links or $navbar): ?>
        <div id="navbar"><div id="navbar-inner" class="region region-navbar">

          <a name="navigation" id="navigation"></a>

          <?php if ($primary_links): ?>
            <div id="primary">
              <?php print theme('links', $primary_links); ?>
            </div> <!-- /#primary -->
          <?php endif; ?>

          <?php if ($secondary_links): ?>
            <div id="secondary">
              <?php print theme('links', $secondary_links); ?>
            </div> <!-- /#secondary -->
          <?php endif; ?>

          <?php print $navbar; ?>

        </div></div> <!-- /#navbar-inner, /#navbar -->
      <?php endif; ?>

      <div id="sidebar-right" class="sidebar"><div id="sidebar-right-inner" class="region region-right">

<!-- **************** -->
<!-- NOTES FOR ADMIN  -->
<!-- **************** -->
<?php global $user; ?>
<?php if ($user->uid == 1): ?>
  <h2>Note:</h2>
  <h3>This is printing $left!</h3>
<?php endif; ?>
<!-- **************** -->

        <?php print $left; ?>
      </div></div> <!-- /#sidebar-right-inner, /#sidebar-right -->


    </div></div> <!-- /#main-inner, /#main -->

    <?php if ($footer or $footer_message): ?>
      <div id="footer"><div id="footer-inner" class="region region-footer">

        <?php if ($footer_message): ?>
          <div id="footer-message"><?php print $footer_message; ?></div>
        <?php endif; ?>

        <?php print $footer; ?>

      </div></div> <!-- /#footer-inner, /#footer -->

    <?php endif; ?>

    <?php if ($logo or $site_name): ?>
      <div id="logo-title">

        <?php if ($logo): ?>
          <div id="logo"><a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" id="logo-image" /></a></div>
        <?php endif; ?>

        <?php if ($site_name): ?>
          <?php if ($is_front): ?>
            <h1 id="site-name">
              <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home">
              <?php print $site_name; ?>
              </a>
            </h1>
          <?php else: ?>
            <div id="site-name"><strong>
              <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home">
              <?php print $site_name; ?>
              </a>
            </strong></div>
          <?php endif; ?>
        <?php endif; ?>

      </div> <!-- /#logo-title -->
    <?php endif; ?>


  </div></div> <!-- /#page-inner, /#page -->

  <?php if ($closure_region): ?>
    <div id="closure-blocks" class="region region-closure"><?php print $closure_region; ?></div>
  <?php endif; ?>

  <?php print $closure; ?>


</div> <!-- /#hnav -->
</div> <!-- /#vista -->

</body>
</html>
