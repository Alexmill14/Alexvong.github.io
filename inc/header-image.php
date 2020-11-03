<?php
/**
 * Displays header media
 *
 * @package kantipur_blog
 */

?>

<div class="custom-header">
	<div class="custom-header-media">
		<?php 
			if( !is_front_page() ) {
				the_custom_header_markup();
			} 
			elseif( is_front_page() ) {
				if( get_theme_mod('enable_frontpage_header_image', false) ) { 
	                the_custom_header_markup();
	            }
			}
		?>
	</div><!-- .custom-header-media -->
</div><!-- .custom-header -->