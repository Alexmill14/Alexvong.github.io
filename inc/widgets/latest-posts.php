<?php
/**
 * Latest Posts.
 *
 * @package kantipur_blog
 */

function kantipur_blog_latest_posts() {
	register_widget( 'Kantipur_Blog_Latest_Posts' );
}
add_action( 'widgets_init', 'kantipur_blog_latest_posts' );

class Kantipur_Blog_Latest_Posts extends WP_Widget { 

	function __construct() {
		global $control_ops;
		$widget_ops = array(
		  'classname'   => 'widget_latest_posts',
		  'description' => esc_html__( 'Add Widget to Display Latest Posts.', 'kantipur-blog' )
		);
		parent::__construct( 'Kantipur_Blog_Latest_Posts',esc_html__( 'Latest Posts Section', 'kantipur-blog' ), $widget_ops, $control_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, 
			array( 
			  'title'			=> esc_html__( 'Latest Posts', 'kantipur-blog' ),	
			  'read_more_text'  => esc_html__( 'Read More', 'kantipur-blog' ),	
			  'category'       	=> '', 
			  'number'          => 4, 
			) 
		);
		$title     			= isset( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'Latest Posts', 'kantipur-blog' );
		$read_more_text     = isset( $instance['read_more_text'] ) ? esc_html( $instance['read_more_text'] ) : esc_html__( 'Read More', 'kantipur-blog' );
		$category 			= isset( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$number    			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;   
	?>
	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php echo esc_html__( 'Title:', 'kantipur-blog' ); ?></label>
	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>	
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>">
				<?php esc_html_e( 'Select Category:', 'kantipur-blog' ); ?>			
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
	    		<?php echo esc_html__( 'Choose Number (Max: 4)', 'kantipur-blog' );?>    		
	    	</label>

	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" max="4" />
	    </p>	

	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id( 'read_more_text' )); ?>"><?php echo esc_html__( 'Read More:', 'kantipur-blog' ); ?></label>
	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'read_more_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'read_more_text' )); ?>" type="text" value="<?php echo esc_attr($read_more_text); ?>" />
		</p> 	 
    <?php
    }

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 				= sanitize_text_field( $new_instance['title'] );
		$instance['read_more_text'] 	= sanitize_text_field( $new_instance['read_more_text'] );
		$instance['category'] 			= absint( $new_instance['category'] );		
		$instance['number'] 			= absint( $new_instance['number'] );
		return $instance;
	}

    function widget( $args, $instance ) {

    	extract( $args ); 
		$title     			= isset( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'Latest Posts', 'kantipur-blog' );
    	$title 				= apply_filters( 'widget_title', $title, $instance, $this->id_base );
    	$read_more_text     = isset( $instance['read_more_text'] ) ? esc_html( $instance['read_more_text'] ) : esc_html__( 'Read More', 'kantipur-blog' );
        $category  			= isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : 0;
        $number 			= ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 4; 
        echo $before_widget;
        ?>   		    
	        
        <?php $blog_args = array(
            'posts_per_page' 		=> absint( $number ),
            'post_type' 			=> 'post',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'   => true,
        );

        if ( absint( $category ) > 0 ) {
          $blog_args['cat'] = absint( $category );
        }

        $the_loop = new WP_Query( $blog_args ); 

        if ($the_loop->have_posts()) : $count= 0; ?>		            
        	<?php if ( !empty( $title ) ): ?>
                <?php echo $args['before_title'] . esc_html($title) . $args['after_title']; ?>
	        <?php endif; ?>	     

    		<div class="col-2 clear">
        		<?php while ( $the_loop->have_posts() ) : $the_loop->the_post(); ?>
                    <article class="<?php echo has_post_thumbnail() ? 'has-post-thumbnail' : 'no-post-thumbnail' ?>">
        				<div class="latest-post-item">
							<div class="featured-image">
								<a href="<?php the_permalink(); ?>">
		                        	<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
		                        </a>
							</div><!-- .featured-image -->

							<div class="entry-container">
								<div class="category-meta">
									<?php kantipur_blog_entry_footer(); ?>
								</div><!-- .category-meta -->
								
								<header class="entry-header">
									<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								</header><!-- .entry-header -->

								<?php $excerpt = get_the_excerpt();
								if ( !empty($excerpt) ) { ?>
									<div class="entry-content">
										<?php the_excerpt(); ?>
									</div><!-- .entry-content -->
								<?php } ?>

								<?php if ( !empty($read_more_text) ): ?>
                            		<a href="<?php the_permalink();?>" class="btn"><?php echo esc_html($read_more_text); ?></a>
                            	<?php endif; ?>
                            </div><!-- .entry-container -->
						</div><!-- .latest-post-item -->
                    </article>
        		<?php endwhile; ?>
            </div><!-- .col-2 -->
            <?php wp_reset_postdata(); ?>
        <?php endif;?>
	        		    
        <?php echo $after_widget;

    } 

}