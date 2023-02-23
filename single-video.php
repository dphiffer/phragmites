<?php

// If the request comes in with Accept: application/json, just return the video
// post content in a JSON wrapper.
$headers = apache_request_headers();
if ($headers['Accept'] == 'application/json') {
	header('Content: application/json');
	echo json_encode([
		'ok' => true,
		'html' => apply_filters('the_content', $post->post_content)
	]);
	exit;
}

while (have_posts()) {
	the_post();

	$phragmites->set_social_card($post);

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
}
