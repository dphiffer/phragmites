<?php

$phragmites->set_social_card([
	'title' => 'Videos - ' . get_bloginfo('name')
]);
get_header();
get_template_part('blocks/videos');
get_footer();
