<?php get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				$args = array(
					'post_type'			=> 'events',
					'posts_per_page'	=> 10,
					'orderby'			=> 'meta_value',
					'order'				=> 'DESC',
					'meta_key'			=> 'kf-em-date-date',
					'meta_query'		=> array(
						array(
							'key'			=> 'kf-em-date-date',
							'value'		=> date('Y-m-d'),
							'compare'	=> '>='
						)
					)
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
			?>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
						$page = get_page_by_path( 'events' );
						echo get_the_title( $page->ID ); 
					?>
				</h1>
			</header><!-- .page-header -->

			<?php

				while ( $query->have_posts() ) {
					$query->the_post();

					include 'content-event.php';
				}

				else :
					get_template_part( 'content', 'none' );
				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_footer(); ?>
