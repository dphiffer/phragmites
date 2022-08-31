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
		$title = get_field('title') ?: 'Projects';
		$url = get_field('link_url') ?: '/projects';
		$page_title = get_the_title($post_id);
		$breadcrumbs = $this->get_breadcrumbs([
			$title => $url,
			$page_title => '#intro'
		]);

		$project_list = get_posts([
			'post_type' => 'project',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1
		]);

		$projects = '<ul>';
		foreach ($project_list as $project) {
			$url = get_permalink($project);
			$project_url = get_field('project_url', $project);
			if (! empty($project_url)) {
				$url = $project_url;
			}
			$time_span = get_field('time_span', $project);
			if (! empty($time_span)) {
				$time_span = "($time_span)";
			}
			$image_src = get_the_post_thumbnail_url($project, 'thumbnail_2x');
			$projects .= <<<END
				<li>
					<figure>
						<a href="$url"><img src="$image_src" alt="$project->post_title"></a>
						<figcaption>
							<a href="$url">$project->post_title</a> $time_span
						</figcaption>
					</figure>
				</li>
END;
		}
		$projects .= '</ul>';

		$class = $this->get_class($block);

		echo <<<END
			<section id="projects" class="$class">
				<div class="container">
					$breadcrumbs
					$projects
				</div>
			</section>
END;
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

	function get_class($block) {
		$class = str_replace('acf/', '', $block['name']) . '-block';
		if (! empty($block['className'])) {
			$class .= " {$block['className']}";
		}
		return $class;
	}

}
