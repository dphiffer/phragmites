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
		acf_register_block_type([
			'name' => 'projects',
			'title' => 'Projects',
			'description' => 'List of project posts',
			'icon' => 'images-alt2',
			'render_callback' => [$this, 'render_projects'],
			'mode' => 'auto'
		]);
	}

	function render_intro($block, $content = '', $is_preview = false, $post_id = 0) {
		$image_id = get_field('image');
		$menu_list = get_field('menu');

		$title = get_the_title($post_id);

		list($image_url) = wp_get_attachment_image_src($image_id, 'full');
		$style = "style=\"background-image: url('$image_url')\"";

		$menu = "<ul>";
		foreach ($menu_list as $link) {
			$url = $link['link_url'];
			$label = $link['link_label'];
			$menu .= "<li><a href=\"$url\">$label</a></li>";
		}
		$menu .= "</ul>";

		$class = $this->get_class($block);

		echo <<<END
			<section id="intro" class="$class" $style>
				<div class="container">
					<h1>$title</h1>
					$menu
				</div>
			</section>
END;
	}

	function render_projects($block, $content = '', $is_preview = false, $post_id = 0) {
		echo get_template_part('archive', 'project');
	}

	function get_class($block) {
		$class = str_replace('acf/', '', $block['name']) . '-block';
		if (! empty($block['className'])) {
			$class .= " {$block['className']}";
		}
		return $class;
	}

}
