<?php if (ISSET($page['content']['system_main']['term_heading'])): ?>
  <?php $page_vid = $page['content']['system_main']['term_heading']['term']['#term']->vid; ?>
<?php else: ?>
  <?php $page_vid = 'no'; ?>
<?php endif; ?>


<div id="page">

  <div id="main" class="clearfix">

  <div id="ubercontent" class="column" role="main">

    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
    <?php endif; ?>

      <?php if ($title): ?>
        <h1 class="title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>

	<header id="header" class="clearfix" role="banner"><div id="header-inner">

      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php print render($title_suffix); ?>
	  <?php print $messages; ?>
      <?php print render($page['help']); ?>

    <?php print render($page['header']); ?>

	</div><!-- /#header-inner -->
	</header>

	<?php print render($tabs); ?>
	<div id="content"><div id="content-inner">
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>

	  <?php if ($page_vid != '5'): ?>
    	<?php print render($page['content']); ?>
	  <?php endif; ?>
	</div></div><!-- /#content-inner, /#content -->

	<div id="h-rows"><div id="h-rows-inner">
      <?php print render($page['horizontal_rows']); ?>
      <div class="clearfix"></div>
	</div></div><!-- /#content-inner, /#h-rows -->

  <?php $content_bottom = render($page['content_bottom']); ?>
  <?php if ($content_bottom): ?>
	<div id="content-bottom"><div id="content-bottom-inner">
      <?php print render($page['content_bottom']); ?>
      <?php print $feed_icons; ?>
	</div></div><!-- /#content-inner, /#content-bottom-inner -->
  <?php endif; ?>

  </div><!-- /#ubercontent -->

  <div id="navigation" class="clearfix">
	<?php print render($page['navigation']); ?>
  </div><!-- /#navigation -->

  <?php
	// Render the sidebars to see if there's anything in them.
	$sidebar_first  = render($page['sidebar_first']);
	$sidebar_second = render($page['sidebar_second']);
	$aside_top = render($page['aside_top']);
  ?>

  <?php if ($sidebar_first || $sidebar_second): ?>
	<aside class="sidebars">
	  <?php if ($aside_top): ?>
		<?php print $aside_top; ?>
	  <?php endif; ?>
	  <?php print $sidebar_first; ?>
	  <?php print $sidebar_second; ?>
	</aside><!-- /.sidebars -->
  <?php endif; ?>

</div><!-- /#main -->

  <?php print render($page['footer']); ?>

</div><!-- /#page -->

<div id="page-bottom">

    <?php if ($site_name || $site_slogan): ?>
      <hgroup id="name-and-slogan">
        <?php if ($site_name): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?>
      </hgroup><!-- /#name-and-slogan -->
    <?php endif; ?>

  <?php print render($page['bottom']); ?>
</div><!-- /#page-bottom -->


<?php
/**
 * @file
 * Zen theme's implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['header']: Items for the header region.
 * - $page['navigation']: Items for the navigation region, below the main menu (if any).
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['footer']: Items for the footer region.
 * - $page['bottom']: Items to appear at the bottom of the page below the footer.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see zen_preprocess_page()
 * @see template_process()
 */
?>
