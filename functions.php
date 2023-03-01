<?php

require_once __DIR__ . '/dbug.php';
require_once __DIR__ . '/blocks.php';
require_once __DIR__ . '/redirects.php';

class Phragmites {

	var $meta = [];

	function __construct() {
		$this->dir = get_stylesheet_directory();
		$this->url = get_stylesheet_directory_uri();
		$this->setup();
	}

	function setup() {
		$this->setup_theme_supports();
		$this->setup_post_types();
		$this->setup_acf();
		$this->setup_styles();
		$this->setup_scripts();
		$this->setup_blocks();
		$this->setup_image_sizes();
		$this->setup_redirects();
		$this->setup_social_cards();
		$this->setup_caption_shortcode();
		$this->setup_admin_projects_post_list();
	}

	function setup_theme_supports() {
		add_action('after_setup_theme', function() {
			add_theme_support('title-tag');
			add_theme_support('post-thumbnails');
			add_theme_support('editor-font-sizes', []);
			add_theme_support('disable-custom-font-sizes');
			add_theme_support('align-wide');
			add_theme_support('responsive-embeds');
			add_theme_support('html5', ['style', 'script', 'caption']);
		});
	}

	function setup_post_types() {
		add_action('init', function() {
			register_post_type('project', [
				'labels' => [
					'name' => 'Projects',
					'singular_name' => 'Project'
				],
				'public' => true,
				'hierarchical' => true,
				'show_in_rest' => true,
				'menu_position' => 4,
				'menu_icon' => 'dashicons-format-image',
				'has_archive' => true,
				'rewrite' => [
					'slug' => 'projects'
				],
				'supports' => [
					'title',
					'editor',
					'thumbnail',
					'revisions',
					'page-attributes'
				]
			]);

			register_post_type('video', [
				'labels' => [
					'name' => 'Videos',
					'singular_name' => 'Video'
				],
				'public' => true,
				'hierarchical' => true,
				'show_in_rest' => true,
				'menu_position' => 4,
				'menu_icon' => 'dashicons-format-video',
				'has_archive' => true,
				'rewrite' => [
					'slug' => 'videos'
				],
				'supports' => [
					'title',
					'editor',
					'thumbnail',
					'revisions',
					'page-attributes'
				]
			]);
		});

		// Remove Posts & Comments from WordPress admin
		add_action('admin_menu', function() {
			remove_menu_page('edit.php');
			remove_menu_page('edit-comments.php');
		});

		add_action('wp_before_admin_bar_render', function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('comments');
		});
	}

	function setup_acf() {
		add_filter('acf/settings/save_json', function() {
			return "$this->dir/acf";
		});
		add_filter('acf/settings/load_json', function() {
			return ["$this->dir/acf"];
		});
	}

	function setup_styles() {
		add_action('wp_enqueue_scripts', function() {
			$this->enqueue_style('source-sans', 'source-sans/source-sans-3.css');
			$this->enqueue_style('main', 'main.css', ['source-sans']);
			$this->enqueue_style('blocks', 'blocks.css', ['source-sans']);
		});
		add_action('admin_enqueue_scripts', function() {
			$this->enqueue_style('source-sans', 'source-sans/source-sans-3.css');
			$this->enqueue_style('blocks', 'blocks.css', ['source-sans']);
		});
	}

	function setup_scripts() {
		add_action('wp_enqueue_scripts', function() {
			$this->enqueue_script('scrollama', 'dist/scrollama.min.js');
			$this->enqueue_script('lodash', 'dist/lodash.min.js');
			$this->enqueue_script('main', 'js/main.js', ['scrollama', 'lodash']);
		});
	}

	function setup_blocks() {
		$this->blocks = new PhragmitesBlocks();
	}

	function setup_image_sizes() {
		add_action('after_setup_theme', function() {
			$base_sizes = ['thumbnail', 'medium', 'large'];
			$sizes = [
				'thumbnail' => [285, 176, 1],
				'thumbnail_2x' => [570, 352, 1],
				'medium' => [720, 0, 0],
				'medium_2x' => [1440, 0, 0],
				'large' => [1000, 0, 0],
				'large_2x' => [2000, 0, 0],
				'xl' => [2000, 0, 0],
				'xl_2x' => [4000, 0, 0],
				'twitter' => [1200, 600, 1],
				'facebook' => [1200, 628, 1]
			];
			foreach ($sizes as $name => $dimensions) {
				list($width, $height, $crop) = $dimensions;
				if (in_array($name, $base_sizes)) {
					if (get_option("{$name}_size_w") != $width) {
						update_option("{$name}_size_w", $width);
					}
					if (get_option("{$name}_size_h") != $height) {
						update_option("{$name}_size_h", $height);
					}
					if (get_option("{$name}_crop") != $crop) {
						update_option("{$name}_crop", $crop);
					}
				} else {
					add_image_size($name, $width, $height, $crop);
				}
			}
		});
	}

	function setup_redirects() {
		add_action('acf/init', function() {
			acf_add_options_page([
				'page_title' => 'Redirects',
				'menu_title' => 'Redirects',
				'menu_slug'  => 'redirects',
				'position'   => '20',
				'icon_url'   => 'dashicons-redo',
				'capability' => 'activate_plugins'
			]);
			acf_add_options_page([
				'page_title' => 'Social Cards',
				'menu_title' => 'Social Cards',
				'menu_slug'  => 'social-cards',
				'position'   => '30',
				'icon_url'   => 'dashicons-share-alt2',
				'capability' => 'activate_plugins'
			]);
			setup_redirects(); // redirects.php
		});
	}

	function setup_social_cards() {
		add_action('acf/init', [$this, 'social_card_defaults']);
		add_action('wp_head', function() {
			echo <<<END
<meta property="og:type" content="website">
<meta property="og:title" content="{$this->meta['title']}">
<meta property="og:description" content="{$this->meta['description']}">
<meta property="og:image" content="{$this->meta['facebook_image']}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="{$this->meta['twitter_handle']}">
<meta name="twitter:creator" content="{$this->meta['twitter_handle']}">
<meta name="twitter:title" content="{$this->meta['title']}">
<meta name="twitter:image" content="{$this->meta['twitter_image']}">
<meta name="twitter:description" content="{$this->meta['description']}">
END;
		});
	}

	function setup_caption_shortcode() {
		add_filter('img_caption_shortcode', function($output, $attr, $content) {
			return <<<END
				<figure class="wp-caption align{$attr['align']}">
					$content
					<figcaption>
						{$attr['caption']}
					</figcaption>
				</figure>
END;
		}, 10, 3);
	}

	function setup_admin_projects_post_list() {
		add_action('admin_init', function() {
			add_action('pre_get_posts', function($query) {
				$screen = get_current_screen();
				if (! empty($screen) && in_array($screen->id, [
					'edit-project',
					'edit-video'
				])) {
					// List the most recent projects/videos first
					$query->set('order', 'DESC');
				}
			});
		});
	}

	function social_card_defaults() {
		$this->meta['title'] = get_bloginfo('name');
		$this->meta['description'] = get_bloginfo('description');
		$image_id = get_field('social_card_image', 'options');
		$this->meta['image_id'] = $image_id;
		$this->meta['facebook_image'] = $this->image_url($image_id, 'facebook');
		$this->meta['twitter_image'] = $this->image_url($image_id, 'twitter');
		$this->meta['twitter_handle'] = get_field('social_card_twitter_handle', 'options');
	}

	function set_social_card($post) {
		if (is_array($post)) {
			$this->meta = array_merge($this->meta, $post);
			$image_id = $this->meta['image_id'];
		} else {
			$this->meta['title'] = $post->post_title . ' - ' . get_bloginfo('name');
			$excerpt = wp_trim_excerpt('', $post);
			if (! empty($excerpt)) {
				$this->meta['description'] = $excerpt;
			}
			$image_id = get_post_thumbnail_id($post->ID);
		}
		if ($image_id) {
			$this->meta['twitter_image'] = $this->image_url($image_id, 'twitter');
			$this->meta['facebook_image'] = $this->image_url($image_id, 'facebook');
		}
	}

	function image_url($id, $size) {
		list($url) = wp_get_attachment_image_src($id, $size);
		return $url;
	}

	function enqueue_style($handle, $file, $deps = []) {
		$version = filemtime("$this->dir/dist/$file");
		$url = "$this->url/dist/$file";
		wp_enqueue_style($handle, $url, $deps, $version);
	}

	function enqueue_script($handle, $file, $deps = []) {
		$version = filemtime("$this->dir/$file");
		$url = "$this->url/$file";
		wp_enqueue_script($handle, $url, $deps, $version);
	}

	function get_breadcrumbs($pages) {
		$links = '';

		foreach ($pages as $label => $href) {
			if (empty($links)) {
				$links .= "<h2><a href=\"$href\">$label</a></h2>";
			} else {
				$links .= " / <a href=\"$href\">$label</a>";
			}
		}

		return <<<END
			<div class="breadcrumbs">
				$links
			</div>
END;
	}

}

$phragmites = new Phragmites();
