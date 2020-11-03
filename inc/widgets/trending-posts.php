<?php
/**
 * Trending Posts.
 *
 * @package kantipur_blog
 */

function kantipur_blog_trending_posts() {
	register_widget( 'Kantipur_Blog_Trending_Posts' );
}
add_action( 'widgets_init', 'kantipur_blog_trending_posts' );

class Kantipur_Blog_Trending_Posts extends WP_Widget { 

	function __construct() {
		global $control_ops;
		$widget_ops = array(
		  'classname'   => 'widget_trending_posts',
		  'description' => esc_html__( 'Add Widget to Display Trending Posts.', 'kantipur-blog' )
		);
		parent::__construct( 'Kantipur_Blog_Trending_Posts',esc_html__( 'Trending Posts Section', 'kantipur-blog' ), $widget_ops, $control_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, 
			array( 
			  'title'			=> esc_html__( 'Trending this week', 'kantipur-blog' ),		
			  'category'       	=> '', 
			  'number'          => 3, 
			) 
		);
		$title     			= isset( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'Trending this week', 'kantipur-blog' );
		$category 			= isset( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$number    			= isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;   
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
	    		<?php echo esc_html__( 'Choose Number (Max: 3)', 'kantipur-blog' );?>    		
	    	</label>

	    	<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" max="3" />
	    </p>	
    <?php
    }

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 				= sanitize_text_field( $new_instance['title'] );
		$instance['category'] 			= absint( $new_instance['category'] );		
		$instance['number'] 			= absint( $new_instance['number'] );
		return $instance;
	}

    function widget( $args, $instance ) {

    	extract( $args ); 
		$title     			= isset( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'Trending this week', 'kantipur-blog' );
    	$title 				= apply_filters( 'widget_title', $title, $instance, $this->id_base );
    	
        $category  			= isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : 0;
        $number 			= ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3; 
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

    		<div class="col-3 clear">
        		<?php while ( $the_loop->have_posts() ) : $the_loop->the_post(); ?>
                    <article class="<?php echo has_post_thumbnail() ? 'has-post-thumbnail' : 'no-post-thumbnail' ?>">
        				<div class="trending-post-item">
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

								<div class="entry-meta">
									<?php kantipur_blog_posted_on(); ?>
								</div><!-- .entry-meta -->
							</div><!-- .entry-container -->
						</div><!-- .trending-post-item -->
                    </article>
        		<?php endwhile; ?>
            </div><!-- .col-3 -->
            <?php wp_reset_postdata(); ?>
        <?php endif;?>
	        		    
        <?php echo $after_widget;

    } 

}