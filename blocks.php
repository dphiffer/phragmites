<?php

class PhragmitesBlocks {

	function __construct() {
		$this->register_blocks();
	}

	function register_blocks() {
		acf_register_block_type([
			'name' => 'intro',
			'title' => 'Intro',
			'description' => 'Splash image with menu',
			'icon' => 'cover-image',
			'render_callback' => [$this, 'render_intro'],
			'mode' => 'auto'
		]);
	}

	function render_intro($block, $content = '', $is_preview = false, $post_id = 0) {
		$image_id = get_field('image');
		$menu_list = get_field('menu');

		$title = '<h1>' . get_the_title($post_id) . '</h1>';

		list($image_url) = wp_get_attachment_image_src($image_id, 'full');
		$style = "style=\"background-image: url('$image_url')\"";

		$menu = "<ul class=\"intro-block__menu\">";
		foreach ($menu_list as $link) {
			$url = $link['link_url'];
			$label = $link['link_label'];
			$menu .= "<li><a href=\"$url\">$label</a></li>";
		}
		$menu .= "</ul>";

		echo "<div class=\"intro-block {$block['className']}\" $style><div class=\"container\">$title$menu</div></div>";
	}

}
