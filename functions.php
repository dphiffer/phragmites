<?php

add_action('after_setup_theme', function() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
});

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
		'revisions'
	]
]);
