<?php

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Шапка, номер телефона',
    'description' => __( 'Блок в шапке справа с номером телефона', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  register_sidebar(array(
    'name' => 'Подвал, копирайт',
    'description' => __( 'Блок с левой стороны в подвале с правовой информацией и номером лицензии', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  register_sidebar(array(
    'name' => 'Подвал, правый блок',
    'description' => __( 'Блок с правой стороны с номером телефона и соцссылками', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  register_sidebar(array(
    'name' => 'Подвал, блок Moving Services',
    'description' => __( 'Блок Moving Services', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  register_sidebar(array(
    'name' => 'Подвал, блок Storage Services',
    'description' => __( 'Блок Storage Services', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));
  register_sidebar(array(
    'name' => 'Подвал, блок Buy Supplies',
    'description' => __( 'Блок Buy Supplies', '' ),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
  ));

/* добавляем в тему меню */
if (function_exists('add_theme_support')) {
	add_theme_support('menus');
}
add_filter('nav_menu_item_id', 'remove_nav_menu_item_id');
  function remove_nav_menu_item_id($id) {
  return "";
}
/* /добавляем в тему меню */

/* удаляем всякое дерьмо, генерируемое вордпрессом */
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head','rel_canonical');
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('pre_term_description', 'wp_kses_data');
//remove_filter( 'the_content', 'wpautop' ); // Отключаем автоформатирование в полном посте
remove_filter( 'the_excerpt', 'wpautop' ); // Отключаем автоформатирование в кратком(анонсе) посте
remove_filter('comment_text', 'wpautop'); // Отключаем автоформатирование в комментариях
/* /удаляем всякое дерьмо, генерируемое вордпрессом */

/* выпиливаем к херам стандартный jqery */
function add_scripts() {
  wp_deregister_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'add_scripts');
/* /выпиливаем к херам стандартный jqery */


// Хлебные крошки
/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2015.09.14
 * лицензия: MIT
*/
function dimox_breadcrumbs() {

  /* === ОПЦИИ === */
  $text['home'] = 'Home'; // текст ссылки "Главная"
  $text['category'] = 'Архив рубрики "%s"'; // текст для страницы рубрики
  $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
  $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
  $text['author'] = 'Статьи автора %s'; // текст для страницы автора
  $text['404'] = 'Ошибка 404'; // текст для страницы 404
  $text['page'] = 'Страница %s'; // текст 'Страница N'
  $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

  $wrap_before = '<div class="breadcrumbs">'; // открывающий тег обертки
  $wrap_after = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
  $sep = '/'; // разделитель между "крошками"
  $sep_before = '<span class="sep">'; // тег перед разделителем
  $sep_after = '</span>'; // тег после разделителя
  $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
  $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
  $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
  $before = '<span class="current">'; // тег перед текущей "крошкой"
  $after = '</span>'; // тег после текущей "крошки"
  /* === КОНЕЦ ОПЦИЙ === */

  global $post;
  $home_link = home_url('/');
  $link_before = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
  $link_after = '</span>';
  $link_attr = ' itemprop="url"';
  $link_in_before = '<span itemprop="title">';
  $link_in_after = '</span>';
  $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
  $frontpage_id = get_option('page_on_front');
  $parent_id = $post->post_parent;
  $sep = ' ' . $sep_before . $sep . $sep_after . ' ';

  if (is_home() || is_front_page()) {

    if ($show_on_home) echo $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;

  } else {

    echo $wrap_before;
    if ($show_home_link) echo sprintf($link, $home_link, $text['home']);

    if ( is_category() ) {
      $cat = get_category(get_query_var('cat'), false);
      if ($cat->parent != 0) {
        $cats = get_category_parents($cat->parent, TRUE, $sep);
        $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        if ($show_home_link) echo $sep;
        echo $cats;
      }
      if ( get_query_var('paged') ) {
        $cat = $cat->cat_ID;
        echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
      }

    } elseif ( is_search() ) {
      if (have_posts()) {
        if ($show_home_link && $show_current) echo $sep;
        if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
      } else {
        if ($show_home_link) echo $sep;
        echo $before . sprintf($text['search'], get_search_query()) . $after;
      }

    } elseif ( is_day() ) {
      if ($show_home_link) echo $sep;
      echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
      echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
      if ($show_current) echo $sep . $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      if ($show_home_link) echo $sep;
      echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
      if ($show_current) echo $sep . $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      if ($show_home_link && $show_current) echo $sep;
      if ($show_current) echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ($show_home_link) echo $sep;
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
        if ($show_current) echo $sep . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, $sep);
        if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        echo $cats;
        if ( get_query_var('cpage') ) {
          echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
        } else {
          if ($show_current) echo $before . get_the_title() . $after;
        }
      }

    // custom post type
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      if ( get_query_var('paged') ) {
        echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . $post_type->label . $after;
      }

    } elseif ( is_attachment() ) {
      if ($show_home_link) echo $sep;
      $parent = get_post($parent_id);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      if ($cat) {
        $cats = get_category_parents($cat, TRUE, $sep);
        $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        echo $cats;
      }
      printf($link, get_permalink($parent), $parent->post_title);
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_page() && !$parent_id ) {
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_page() && $parent_id ) {
      if ($show_home_link) echo $sep;
      if ($parent_id != $frontpage_id) {
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          if ($parent_id != $frontpage_id) {
            $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
          }
          $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
          echo $breadcrumbs[$i];
          if ($i != count($breadcrumbs)-1) echo $sep;
        }
      }
      if ($show_current) echo $sep . $before . get_the_title() . $after;

    } elseif ( is_tag() ) {
      if ( get_query_var('paged') ) {
        $tag_id = get_queried_object_id();
        $tag = get_tag($tag_id);
        echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
      }

    } elseif ( is_author() ) {
      global $author;
      $author = get_userdata($author);
      if ( get_query_var('paged') ) {
        if ($show_home_link) echo $sep;
        echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
      } else {
        if ($show_home_link && $show_current) echo $sep;
        if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
      }

    } elseif ( is_404() ) {
      if ($show_home_link && $show_current) echo $sep;
      if ($show_current) echo $before . $text['404'] . $after;

    } elseif ( has_post_format() && !is_singular() ) {
      if ($show_home_link) echo $sep;
      echo get_post_format_string( get_post_format() );
    }

    echo $wrap_after;

  }
} // end of dimox_breadcrumbs()
// / Хлебные крошки

// минифицируем вообще всё, АХАХАХАХАХ
/* Minifies HTML and removes comments (except IE tags and comments within script tags)
 *
 * To disable compression of code portions, use '<!--wp-html-compression no compression-->' tag
 *
 * @see http://forrst.com/posts/Wordpress_Minify_output_HTML-29q
 * @see http://www.intert3chmedia.net/2011/12/minify-html-javascript-css-without.html
 */
class WP_HTML_Compression {
  // Settings
  protected $compress_css = true;
  protected $compress_js = false;
  protected $info_comment = true;
  protected $remove_comments = true;

  // Variables
  protected $html;

  public function __construct($html) {
    if (!empty($html)) {
      $this->parseHTML($html);
    }
  }

  public function __toString() {
    return $this->html;
  }

  protected function minifyHTML($html) {
    $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
    preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
    $overriding = false;
    $raw_tag = false;
    // Variable reused for output
    $html = '';
    foreach ( $matches as $token ) {
      $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
      $content = $token[0];
      if ( is_null( $tag ) ) {
        if ( !empty( $token['script'] ) ) {
          $strip = $this->compress_js;
        } else if ( !empty($token['style'] ) ) {
          $strip = $this->compress_css;
        } else if ( $content == '<!--wp-html-compression no compression-->' ) {
          $overriding = !$overriding;
          // Don't print the comment
          continue;
        } else if ( $this->remove_comments ) {
          if ( !$overriding && $raw_tag != 'textarea' ) {
            // Remove any HTML comments, except MSIE conditional comments
            $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
          }
        }
      } else {
        if ( $tag == 'pre' || $tag == 'textarea' || $tag == 'script' ) {
          $raw_tag = $tag;
        } else if ( $tag == '/pre' || $tag == '/textarea' || $tag == '/script' ) {
          $raw_tag = false;
        } else {
          if ($raw_tag || $overriding) {
            $strip = false;
          } else {
            $strip = true;
            // Remove any empty attributes, except:
            // action, alt, content, src
            $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
            // Remove any space before the end of self-closing XHTML tags
            // JavaScript excluded
            $content = str_replace(' />', '/>', $content);
          }
        }
      }
      if ( $strip ) {
        $content = $this->removeWhiteSpace($content);
      }
      $html .= $content;
    }
    return $html;
  }
  public function parseHTML($html) {
    $this->html = $this->minifyHTML($html);
  }
  protected function removeWhiteSpace($str) {
    $str = str_replace( "\t", ' ', $str );
    $str = str_replace( "\n",  '', $str );
    $str = str_replace( "\r",  '', $str );

    while ( stristr($str, '  ' ) ) {
      $str = str_replace('  ', ' ', $str);
    }
    return $str;
  }
}
function wp_html_compression_finish($html) {
  return new WP_HTML_Compression($html);
}
function wp_html_compression_start() {
  ob_start( 'wp_html_compression_finish' );
}
add_action( 'get_header', 'wp_html_compression_start' );
// /минифицируем вообще всё, АХАХАХАХАХ

?>
