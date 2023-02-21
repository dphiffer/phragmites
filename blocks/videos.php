<?php

global $phragmites;

if (! empty($args['breadcrumbs'])) {
	$breadcrumbs = $args['breadcrumbs'];
} else {
	$site_title = get_bloginfo('name');
	$breadcrumbs = $phragmites->get_breadcrumbs([
		'Videos' => '/videos',
		$site_title => '/'
	]);
}

$post_parent = empty($args['post_parent']) ? 0 : $args['post_parent'];

$video_list = get_posts([
	'post_type' => 'video',
	'orderby' => 'menu_order',
	'order' => 'DESC',
	'posts_per_page' => -1,
	'post_parent' => $post_parent
]);

if (empty($args['selected']) && ! empty($video_list)) {
	$args['selected'] = $video_list[0]->post_name;
}

$videos = '<ul class="video-list">';
foreach ($video_list as $video) {
	$url = get_permalink($video);
	$selected = '';
	if ($args['selected'] == $video->post_name) {
		$selected = ' class="selected"';
		$content = apply_filters('the_content', $video->post_content);
	}
	$videos .= "<li$selected><a href=\"$url\">$video->post_title</a></li>\n";
}
$videos .= '</ul>';

// $class = $this->get_class($block);

?>
<section id="videos" class="videos-block">
	<?php echo $breadcrumbs; ?>
	<div class="container">
		<?php echo $videos; ?>
		<div class="content">
			<?php echo $content; ?>
		</div>
	</div>
</section>
