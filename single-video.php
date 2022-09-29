<?php

get_header();

get_template_part('blocks/videos', null, [
	'selected' => $post->post_name,
	'breadcrumbs' => $phragmites->get_breadcrumbs([
		$post->post_title => get_permalink($post),
		'Videos' => '/videos',
		get_bloginfo('name') => '/'
	])
]);

get_footer();
