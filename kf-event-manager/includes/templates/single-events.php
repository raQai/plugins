<?php get_header(); ?>

	<article id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : the_post(); ?>

			<header class="entry-header">
        <div class="entry-meta">
          <?php
            $args = array(
              'before' => '<span class="cat-links">',
              'after' => '</span>',
              'template' => '% %l'
            );
            the_taxonomies( $args );
          ?>
        </div>
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .page-header -->

      <div class="entry-content">
        <?php
          $start = get_post_meta( get_the_id(), 'kf-em-start', true );
          $open = get_post_meta( get_the_id(), 'kf-em-open', true );
          $is_extern = get_post_meta( get_the_id(), 'kf-em-location-is-extern', true );
          $loc = '';
          if ( get_post_meta( get_the_id(), 'kf-em-location-name', true ) ) {
            $loc .= get_post_meta( get_the_id(), 'kf-em-location-name', true ) . '<br />';
          }
          if ( get_post_meta( get_the_id(), 'kf-em-location-street', true ) ) {
            $loc .= get_post_meta( get_the_id(), 'kf-em-location-street', true ) . '<br />';
          }
          if ( get_post_meta( get_the_id(), 'kf-em-location-city', true ) ) {
            $loc .= get_post_meta( get_the_id(), 'kf-em-location-city', true ) . '<br />';
          }
        ?>
        <table>
          <tr>
            <td>Datum:</td>
            <td><?php echo date( 'd.m.Y', $start ); ?></td>
          </tr>
          <tr>
            <td>&Ouml;ffnung:</td>
            <td><?php echo date( 'H:i', $open ); ?></td>
          </tr>
          <tr>
            <td>Start:</td>
            <td><?php echo date( 'H:i', $start ); ?></td>
          </tr>
        <?php if ( $is_extern ) { ?>
          <tr>
            <td>Ort:</td>
            <td><?php echo $loc; ?></td>
          </tr>
        <?php } ?>
        </table>
        <?php
          the_content();

          edit_post_link( __( 'Edit' ), '<span class="edit-link">', '</span>' );

          $prev_post = get_previous_post();
          if($prev_post) {
            $prev_title = strip_tags(str_replace('"', '', $prev_post->post_title));
            echo "\t" . '<a rel="prev" href="' . get_permalink($prev_post->ID) . '" title="' . $prev_title. '" class=" ">&laquo; Previous post<br /><strong>&quot;'. $prev_title . '&quot;</strong></a>' . "\n";
          }

          $next_post = get_next_post();
          if($next_post) {
            $next_title = strip_tags(str_replace('"', '', $next_post->post_title));
            echo "\t" . '<a rel="next" href="' . get_permalink($next_post->ID) . '" title="' . $next_title. '" class=" ">Next post &raquo;<br /><strong>&quot;'. $next_title . '&quot;</strong></a>' . "\n";
          }
          //echo next_posts_link( '%link', 'Older Entries', $the_query->max_num_pages );
          //echo previous_posts_link( '%link', 'Newer Entries' );

          wp_reset_postdata();
        ?>
      </div><!-- .entry-content -->
			<?php

				else :
					get_template_part( 'content', 'none' );
				endif;
			?>
		</div><!-- #content -->
	</article><!-- #primary -->

<?php
get_sidebar( 'content' );
get_footer(); ?>
