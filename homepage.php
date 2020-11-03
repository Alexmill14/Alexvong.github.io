<?php
/**
 * This Template is used to display front page.
 * Template Name: Home Page
 *
 * @package kantipur_blog
 */

get_header();
?>
	<div id="home-sections">
		<?php dynamic_sidebar( 'home-sections' ); ?>
	</div><!-- #home-sections -->

<?php
get_footer();