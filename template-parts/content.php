<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kantipur_blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item'); ?>>
	<div class="blog-post-item">
		<div class="featured-image">
			<?php kantipur_blog_post_thumbnail(); ?>
		</div><!-- .featured-image -->

		<div class="entry-container">
			<div class="category-meta">
				<?php kantipur_blog_entry_footer(); ?>
			</div><!-- .category-meta -->
			
			<header class="entry-header">
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif; ?>
			</header><!-- .entry-header -->

			<?php 
			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
						kantipur_blog_posted_by();
						kantipur_blog_posted_on();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>

			<?php $excerpt = get_the_excerpt();
			
			if ( !empty($excerpt) ) { ?>
				<div class="entry-content">
					<?php the_excerpt(); ?>
				</div><!-- .entry-content -->
			<?php } ?>
		</div><!-- .entry-container -->
	</div><!-- .blog-post-item -->
</article><!-- #post-<?php the_ID(); ?> -->