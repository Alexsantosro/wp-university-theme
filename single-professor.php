<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner(); //Usa a função criada em functions.php, isso é feito para reduzir a quantidade códigos na página
    ?>

    <div class="container container--narrow page-section">

      <div class="generic-content">
        <div class="row group">
          <div class="one-third">
            <?php the_post_thumbnail('professorPortrait'); ?> <!-- Carrega o formato de imagem criado em functions.php -->
          </div>
          <div class="two-thirds">
            <?php the_content(); ?>
          </div>
        </div>
      </div>




      <?php

      $relatedPrograms = get_field('related_programs'); //Essa váriavel recebe o custom field Related Programs do ACF, que é um array 
      
      if ($relatedPrograms) {
      
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
      echo '<ul class="link-list min-list">';
      
      foreach($relatedPrograms as $program) { ?> <!--Cade item no array da váriavel $relatedPrograms é um wordpress post object-->
        <li><a href="<?php echo get_the_permalink($program)?>"><?php echo get_the_title($program)?></a></li>
      <?php }

      echo "</ul>";
      } 
      ?>
    </div>
    

    
  <?php }

  get_footer();

?>