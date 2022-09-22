<?php

class PhragmitesBlocks {

	function __construct() {
		$this->register_blocks();
		$this->setup_embeds();
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
		acf_register_block_type([
			'name' => 'scrolly-image',
			'title' => 'Scrolly Image',
			'description' => 'Image sequence via scrolling',
			'icon' => 'images-alt2',
			'render_callback' => [$this, 'render_scrolly_image'],
			'mode' => 'auto'
		]);
	}

	function setup_embeds() {
		add_filter('embed_defaults', function() {
			return [
				'width' => 720,
				'height' => 405
			];
		});
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
		global $phragmites;
		$args = [];
		$post = get_post($post_id);
		$title = get_field('title');
		if ($post->post_type == 'project') {
			$project_title = get_the_title($post_id);
			$url = get_permalink($post_id);
			$args['breadcrumbs'] = $phragmites->get_breadcrumbs([
				$title => "#projects",
				$project_title => $url
			]);
			$args['post_parent'] = $post_id;
		} else {
			$site_title = get_bloginfo('name');
			$breadcrumbs = $phragmites->get_breadcrumbs([
				$title => '/projects',
				$site_title => '/'
			]);
		}
		echo get_template_part('blocks/projects', null, $args);
	}

	function render_scrolly_image($block, $content = '', $is_preview = false, $post_id = 0) {

		$class = $this->get_class($block);
		$image_list = get_field('images');
		$images = '';
		$captions = '';

		foreach ($image_list as $index => $item) {
			list($image_url) = wp_get_attachment_image_src($item['image_id'], 'xl');
			$images .= "<img src=\"$image_url\" class=\"image-$index\">\n";
			$captions .= "<div class=\"caption caption-$index\">{$item['caption']}</div>\n";
		}

		echo <<<END
		<section class="$class">
			<div class="scrolly-image">
				$images
			</div>
			<div class="scrolly-caption">
				$captions
			</div>
		</section>
END;
	}

	function get_class($block) {
		$class = str_replace('acf/', '', $block['name']) . '-block';
		if (! empty($block['className'])) {
			$class .= " {$block['className']}";
		}
		return $class;
	}

}
