<?php get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
						$page = get_page_by_path( 'teams' );
						echo get_the_title( $page->ID );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
					while ( have_posts() ) {

						the_post();

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
