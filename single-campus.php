<?php

get_header();

while (have_posts()) {
  the_post(); 
  pageBanner();?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i
            class="fa fa-home" aria-hidden="true"></i>All Campuses</a> <span
          class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

    <div class="container container--narrow page-section">

<div class="acf-map">
    <?php $mapLocation = get_field('map_location');?>
    <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
    <h3><?php the_title(); ?></h3>
    <?php echo $mapLocation['address'] ?>
    </div>
</div>

    <?php
    $relatedPrograms = new WP_Query( //Custom querie para filtrar os programas
      array(
        'posts_per_page' => -1,
        'post_type' => 'program',
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
          array( //Cada array é um filtro, nesse caso é um filtro para mostrar somente os professores relacionados ao post em questão
            'key' => 'related_campus',
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"'
          )
        )
      )
    );
    if ($relatedPrograms->have_posts()) { // Verifica se existem posts para ser exibidos
  
      echo '<hr class="section-break">';
      echo '<h2 class="headline head-line--medium">Programs Available At This Campus</h2>';
      echo '<ul class="min-list link-list">';

      while ($relatedPrograms->have_posts()) { //Verifica se existem professores a serem exibidos
        $relatedPrograms->the_post(); ?> 
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
        </a>
      </li>
      <?php }
      echo '</ul>';
    }

    wp_reset_postdata();
    
    ?>
  </div>



<?php }

get_footer();

?>