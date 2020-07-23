<?php
/**
 * Plugin Name: Envira Latest Videos
 * Description: Pull the latest videos from an Envira Video album/gallery
 * Version: 1.1.1
 * Author: Chinara James
 * Author URI: https://chinarajames.com
 * Text Domain: elvs
 * Domain Path: /languages
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}
define( 'ELVS_PLUGIN_VERSION', '1.1.1' );
define( 'ELVS_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

add_action('wp_enqueue_scripts', 'elvs_enqueue_styles');
function elvs_enqueue_styles() {
  wp_enqueue_style( 'elvs-style', plugin_dir_url( __FILE__ ) . 'style.css', [], ELVS_PLUGIN_VERSION, 'all' );
}

add_action('wp_enqueue_scripts', 'elvs_enqueue_scripts');
function elvs_enqueue_scripts() {
  wp_enqueue_script( 'elvs-script', plugin_dir_url( __FILE__ ) . 'lightbox.js', [], ELVS_PLUGIN_VERSION, true );
}


add_shortcode( 'elvs-gallery', 'elvs_display_latest' );
function elvs_display_latest( $atts ) {
  $atts = array_change_key_case((array)$atts, CASE_LOWER);
  $atts = shortcode_atts(
    array(
      'gallery_id' => '',
      'num_posts' => '6',
      'sort_order' => 'DESC',
    ),
    $atts,
    'elvs-gallery'
  );

  extract($atts);

  $gallery_data = get_post_meta((int)$gallery_id, '_eg_gallery_data', true);

  if ( empty($gallery_id) || empty($gallery_data) ) {
    return 'You need to add the gallery_id attribute with a valid gallery ID';
  }

  // Output the gallery
  ob_start();
  echo '<div class="elvs">';
  $i = 0;
  foreach($gallery_data['gallery'] as $id => $data) {
    if (++$i == (int)$num_posts + 1) break;
    $youtube_id = elvs_get_yt_id($data['link']);
    echo get_gallery_item_output($data, $youtube_id);
  }
  echo get_lightbox_html();

  return ob_get_clean();

}

add_shortcode( 'elvs-album', 'elvs_display_latest_from_album' );
function elvs_display_latest_from_album( $atts ) {
  $atts = array_change_key_case((array)$atts, CASE_LOWER);
  $atts = shortcode_atts(
    array(
      'album_id' => '',
      'num_posts' => '6',
      'sort_order' => 'DESC',
    ),
    $atts,
    'elvs-album'
  );

  extract($atts);

  $album_data = get_post_meta((int)$album_id, '_eg_album_data', true);

  if ( empty($album_id) || empty($album_data) ) {
    return 'You need to add the album_id attribute with a valid gallery ID';
  }


  $gallery_IDs = $album_data['galleryIDs'];
  $posts_per_page = count($gallery_IDs) == 1 ? -1 : (int)$num_posts * count($gallery_IDs);

  $galleries = new WP_Query([
    'post_type' => 'attachment',
    'post_status' => 'inherit',
    'orderby' => 'date',
    'order' => 'DESC',
    'post_parent__in' => $gallery_IDs,
    'posts_per_page' => $posts_per_page
  ]);

  ob_start();
  if( $galleries->have_posts()) {
    echo '<div class="elvs">';
    $i = 0;
    while($galleries->have_posts()) {
      $galleries->the_post();
      if (++$i == (int)$num_posts + 1) break;
      $_eg_has_gallery = get_post_meta(get_the_ID(), '_eg_has_gallery', true);
      $gallery_data = get_post_meta($_eg_has_gallery[0], '_eg_gallery_data', true);
      if($gallery_data == false) {
        $i = $i - 1;
        continue;
      }
      $data = $gallery_data['gallery'][get_the_ID()];
      $youtube_id = elvs_get_yt_id($data['link']);
      echo get_gallery_item_output($data, $youtube_id);
    }
    echo get_lightbox_html();
  }
  return ob_get_clean();

}

/**
 * Return the gallery item html
 *
 * @param [array] $data
 * @param [string] $youtube_id
 * @return void
 * @todo Allow user to edit template e.g custom classes, html, change thumbnail size
 */
function get_gallery_item_output($data, $youtube_id) {
  $html = '<div class="elvs__item">';
  $html .= '<a class="elvs__link" href="//youtube.com/embed/' . $youtube_id . '" title="'. $data['title'] .'" data-lightbox="https://youtube.com/embed/' . $youtube_id . '">';
  $html .= '<img class="elvs__image" src="'. $data['src'] .'" width="80" height="60" />';
  $html .= '<span class="elvs__play-icon">Play</span>';
  $html .= '</a>';
  $html .= '</div>';
  return $html;
}

function get_lightbox_html() {
  $html = '<div id="lightbox-overlay"><span id="lightbox-close">Close</span><iframe id="lightbox-iframe" src="" frameborder="0" allowfullscreen></iframe></div>';
  $html .= '</div>';
  return $html;
}


add_filter( 'envira_albums_output_image_count', 'change_albums_output_image_count_label', 10, 2 );
function change_albums_output_image_count_label($label, $count) {
  return $label = $count . ' ' . _n( 'Video', 'Videos', $count, 'elvs' );
}

/** Helper Functions **/
function elvs_get_yt_id($ytlink) {
  // Assuming youtube IDs are always 11 characters
  $ytid = substr($ytlink, -11);
  return $ytid;
}