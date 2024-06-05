<?php

function pageBanner($args = NULL) { //Analisa o argumento, se não houver considera como nulo.
  
  if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  }
 
  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }
 
  if (!isset($args['photo'])) {
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg'); //Fallback se não houver imagem de fundo.
    }
  }

  ?>
 <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div> <!-- O campo gerado de imagem do ACF é um Array. Nesse caso pegamos a imagem olhando dentro da propriedade sizes e pegamos o tamanho criado em functions -->
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle'] ?></p>
        </div>
      </div>  
    </div>
<?php }

function university_files() {
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAfnEeSd13yjBPw4YcaADGZ4rSoWWjxYCg', NULL, '1.0', true);
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() { //Função que ativa recursos do wordpress
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails'); //Ativa as thumbnails nos blog posts.
  add_image_size('professorLandscape', 400, 260, true); //Argumentos - a: Nick name image size, b: wide size, c: tall size, d: Crop or not
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);

}

add_action('after_setup_theme', 'university_features'); //Ativa recursos do wordpress.

//Personlização da querie do wordpress
function university_adjust_queries($query) {
  //Personlização da querie do archive do CPT Event
  if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
            ));
  }
  //Personlização da querie do archive do CPT Program
  if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
   $query->set('orderby', 'title'); //Olha dentro da query e ordena os itens por titulo.
   $query->set('order', 'ASC');
   $query->set('posts_per_page', -1);
  }

  //Personlização da querie do archive do CPT Campus
  if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
    $query->set('posts_per_page', -1);
   }
}

add_action('pre_get_posts', 'university_adjust_queries');

//Função para ativar a api do google maps

function universityMapKey ($api) { 
$api['key'] = 'AIzaSyAfnEeSd13yjBPw4YcaADGZ4rSoWWjxYCg';
return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey'); 