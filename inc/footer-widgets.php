<?php
/**
 * Displays footer widgets if assigned
 *
 * @package kantipur_blog
 */

?>

<?php
	$footer_column_data 	= kantipur_blog_footer_column_class();
	$footer_sidebar    		= $footer_column_data['active_sidebar'];
	$footer_column      	= $footer_column_data['class']; 
?>

<aside class="widget-area <?php echo esc_attr( $footer_column ); ?>" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'kantipur-blog' ); ?>">
	<?php
		for ( $i = 1; $i <= 3 ; $i++ ) {
			if ( is_active_sidebar( 'footer-' . $i ) ) { ?>
				<div class="widget-column">
					<?php dynamic_sidebar( 'footer-' . $i ); ?>
				</div>
			<?php }
		}
	?>
</aside><!-- .widget-area -->

<?php 

	function kantipur_blog_footer_column_class() {
	    $data = array();
	    $active_sidebar = array();
	    $count = 0;

	    if ( is_active_sidebar( 'footer-1' ) ) {
			$active_sidebar[] = 'footer-1';
			$count++;
	    }

	    if ( is_active_sidebar( 'footer-2' ) ) {
			$active_sidebar[] = 'footer-2';
			$count++;
	    }

		if ( is_active_sidebar( 'footer-3' ) ){
			$active_sidebar[] = 'footer-3';
			$count++;
		}

		$class = '';

	    switch ( $count ) {
			case '1':
				$class = 'col-1';
				break;
			case '2':
				$class = 'col-2';
				break;
			case '3':
				$class = 'col-3';
				break;
	    }

	    $data['active_sidebar'] = $active_sidebar;
	    $data['class']        = $class;

		return $data;
	}
?>