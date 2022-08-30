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
			$this->enqueue_style('phragmites', 'main.css', ['source-sans']);
		});
	}

	function setup_blocks() {
		$this->blocks = new PhragmitesBlocks();
	}

	function enqueue_style($handle, $file, $deps = []) {
		$version = filemtime("$this->dir/dist/$file");
		$url = "$this->url/dist/$file";
		wp_enqueue_style($handle, $url, $deps, $version);
	}

}

new Phragmites();
