<?php get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				$args = array(
					'post_type' => 'teams',
					'orderby' => 'menu_order',
					'order' => 'ASC',
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
			?>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
						$page = get_page_by_path( 'teams' );
						echo get_the_title( $page->ID );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
					while ( $query->have_posts() ) {

						$query->the_post();

						include 'content-teams.php';
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
