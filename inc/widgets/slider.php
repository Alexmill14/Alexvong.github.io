<?php
/**
 * Slider Posts.
 *
 * @package kantipur_blog
 */

function kantipur_blog_slider() {
	register_widget( 'kantipur_blog_Slider' );
}
add_action( 'widgets_init', 'kantipur_blog_slider' );

class kantipur_blog_Slider extends WP_Widget{ 

	function __construct() {
		global $control_ops;
		$widget_ops = array(
		  'classname'   => 'widget_slider',
		  'description' => esc_html__( 'Add Widget to Display Slider.', 'kantipur-blog' )
		);
		parent::__construct( 'kantipur_blog_Slider',esc_html__( 'Slider Section', 'kantipur-blog' ), $widget_ops, $control_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, 
			array( 
			  'read_more_text'  => esc_html__( 'Read More', 'kantipur-blog' ),
			  'category'       	=> '', 
			  'number'          => 3, 
			) 
		);
		$read_more_text     = isset( $instance['read_more_text'] ) ? esc_html( $instance['read_more_text'] ) : esc_html__( 'Read More', 'kantipur-blog' );		
		$category 			= isset( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$number    			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;   
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
				<?php esc_html_e( 'Slider Category:', 'kantipur-blog' ); ?>			
			</label>

			<?php
				wp_dropdown_categories(array(
					'show_option_none' => '',
					'class' 		  => 'widefat',
					'show_option_all'  => esc_html__('Recent Posts','kantipur-blog'),
					'name'             => esc_attr($this->get_field_name( 'category' )),
					'selected'         => absint( $category ),          
				) );
			?>
		</p>

	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>">
	    		<?php echo esc_html__( 'Choose Number (Max: 3)', 'kantipur-blog' );?>    		
	    	</label>

	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" max="3" />
	    </p>  

	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id( 'read_more_text' )); ?>"><?php echo esc_html__( 'Read More:', 'kantipur-blog' ); ?></label>
	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'read_more_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'read_more_text' )); ?>" type="text" value="<?php echo esc_attr($read_more_text); ?>" />
		</p> 	    
    <?php
    }

    function widget( $args, $instance ) {

    	extract( $args ); 
    	    	
    	$read_more_text     = isset( $instance['read_more_text'] ) ? esc_html( $instance['read_more_text'] ) : esc_html__( 'Read More', 'kantipur-blog' );
        $category  			= isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : 0;
        $number 			= ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3; 
        echo $before_widget;
        ?>       		    
	        
        <?php $slider_args = array(
            'posts_per_page' 		=> absint( $number ),
            'post_type' 	 		=> 'post',
            'post_status' 	 		=> 'publish',
            'ignore_sticky_posts'   => true,     
        );

        if ( absint( $category ) > 0 ) {
          $slider_args['cat'] = absint( $category );
        }

        $the_loop = new WP_Query( $slider_args ); 

	        if ($the_loop->have_posts()) : $count= 0; ?>      
	    		<div class="swiper-container">
	    			<div class="swiper-wrapper">
	        			<?php while ( $the_loop->have_posts() ) : $the_loop->the_post(); ?>
		                    <article class="swiper-slide">
		                    	<?php if ( has_post_thumbnail() ){ ?>
			                        <div class="featured-image">
			                        	<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
		                    			<div class="section-overlay"></div>
			                        </div><!-- .featured-image -->
		                        <?php } ?>

		                        <div class="slider-content">
		                        	<div class="category-meta">
										<?php kantipur_blog_entry_footer(); ?>
									</div><!-- .category-meta -->

		                            <header class="entry-header">
		                                <h2 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		                            </header>

		                            <div class="entry-meta">
										<?php kantipur_blog_posted_on(); ?>
									</div><!-- .entry-meta -->

									<?php if ( !empty($read_more_text) ): ?>
	                            		<a href="<?php the_permalink();?>" class="btn"><?php echo esc_html($read_more_text); ?></a>
	                            	<?php endif; ?>
		                    	</div><!-- .slider-content -->
		                    </article>
	            		<?php endwhile; ?>
	                </div><!-- .swiper-wrapper -->
	                <div class="swiper-pagination"></div>
	                <div class="swiper-button-next"></div>
	    			<div class="swiper-button-prev"></div>
	            </div><!-- .swiper-container -->
	        <?php endif;?>

        <?php wp_reset_postdata(); ?>
        <?php echo $after_widget;
    } 

    function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['read_more_text'] 	= sanitize_text_field( $new_instance['read_more_text'] );
		$instance['category'] 			= absint( $new_instance['category'] );		
		$instance['number'] 			= absint( $new_instance['number'] );
		return $instance;
	}
}