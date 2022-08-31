<?php

require_once __DIR__ . '/dbug.php';
require_once __DIR__ . '/blocks.php';

class Phragmites {

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
		$this->setup_blocks();
		$this->setup_image_sizes();
	}

	function setup_theme_supports() {
		add_action('after_setup_theme', function() {
			add_theme_support('title-tag');
			add_theme_support('post-thumbnails');
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
				'menu_icon' => 'dashicons-images-alt2',
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

	function setup_blocks() {
		$this->blocks = new PhragmitesBlocks();
	}

	function setup_image_sizes() {
		add_action('after_setup_theme', function() {
			$base_sizes = ['thumbnail', 'medium', 'large'];
			$sizes = [
				'thumbnail' => [316, 195, 1],
				'thumbnail_2x' => [632, 390, 1],
				'medium' => [482, 0, 0],
				'medium_2x' => [964, 0, 0],
				'large' => [648, 0, 0],
				'large_2x' => [1296, 0, 0],
				'xl' => [980, 0, 0],
				'xl_2x' => [1960, 0, 0],
				'xxl' => [1280, 0, 0],
				'xxl_2x' => [2560, 0, 0]
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

	function enqueue_style($handle, $file, $deps = []) {
		$version = filemtime("$this->dir/dist/$file");
		$url = "$this->url/dist/$file";
		wp_enqueue_style($handle, $url, $deps, $version);
	}

}

new Phragmites();
