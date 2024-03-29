<?php

global $phragmites;

if (! empty($args['breadcrumbs'])) {
	$breadcrumbs = $args['breadcrumbs'];
} else {
	$site_title = get_bloginfo('name');
	$breadcrumbs = $phragmites->get_breadcrumbs([
		'Projects' => '/projects',
		$site_title => '/'
	]);
}

$post_parent = empty($args['post_parent']) ? 0 : $args['post_parent'];

$project_list = get_posts([
	'post_type' => 'project',
	'orderby' => 'menu_order',
	'order' => 'DESC',
	'posts_per_page' => -1,
	'post_parent' => $post_parent
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

// $class = $this->get_class($block);

?>
<section id="projects" class="projects-block">
	<?php echo $breadcrumbs; ?>
	<div class="container">
		<?php echo $projects; ?>
	</div>
</section>
