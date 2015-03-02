<?php get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="entry-header">
				<h1 class="entry-title">
					<?php
						$page = get_page_by_path( 'events' );
						echo get_the_title( $page->ID ); 
					?>
				</h1>
			</header><!-- .page-header -->
      <article class="hentry kf-em-event">
        <table>
          <tr>
            <th width="120">Datum</th>
            <th width="150">Kategorie</th>
            <th>Name</th>
          </tr>
          <?php
            while ( have_posts() ) {
              the_post();

              include 'content-event.php';
            }

            else :
              get_template_part( 'content', 'none' );
            endif;
          ?>
        </table>
      </article>
    </div>
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_footer(); ?>
