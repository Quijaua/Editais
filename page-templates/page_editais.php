<?php
      /*
      Template Name: Editais - Template

       * @author Eduardo Alencar
       * @copyright 2015
       */

      ?>
      <?php get_header(); ?>
      	<div id="content">
      	<?php
      		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      		$loop = new WP_Query(array('post_type' => 'quijauaeditais_edt',
      									'post_status' => 'published',
      									'paged' => $paged,
      									'posts_per_page' => 25,
                        'orderby' => 'edt_period',
                        'order'   => 'DESC'
      		));
         
      	?>
      	<div id="editais-container">
      	
      	<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
          <div class="edital-item" id="edital-<?php the_ID(); ?>">
      	<?php
      	   $organization = get_post_meta($more_post->ID, 'edt_organization', true);
           $period = get_post_meta($more_post->ID, 'edt_period', true);
           $externalLink = get_post_meta($more_post->ID, 'edt_link', true);	
           
      	?>
            <h1><a href="<?php echo $externalLink; ?>"><?php the_title(); ?></a></h1>
            <p>
              ÓRGÃO/INSTITUIÇÃO/ORGANIZAÇÃO<span><?php echo $organization ?></span><br />
              PERIODO<span><?php echo $period ?></span><br />
            </p>      	

      			
      			
      			
      			</div>
              <?php endwhile; ?>
      		
      	</div>

      <div class="navigation">
        <div class="alignleft"><?php previous_posts_link('&laquo; Anterior') ?></div>
        <div class="alignright"><?php next_posts_link('Mais &raquo;') ?></div>
        </div>

          </div><!-- #content -->
      	<?php get_sidebar();?>
      <?php get_footer(); ?>