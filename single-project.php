<?php

global $post, $phragmites;

while (have_posts()) {
	the_post();

	if (get_field('project_website')) {
		$url = get_field('project_url');
		header("Location: $url");
		exit;
	}

	$phragmites->meta['title'] = $post->post_title . ' - ' . get_bloginfo('name');
	$excerpt = wp_trim_excerpt('', $post);
	if (! empty($excerpt)) {
		$phragmites->meta['description'] = $excerpt;
	}
	$image_id = get_post_thumbnail_id($post->ID);
	$phragmites->meta['twitter_image'] = $phragmites->image_url($image_id, 'twitter');
	$phragmites->meta['facebook_image'] = $phragmites->image_url($image_id, 'facebook');

	get_header();

	$breadcrumbs = [];
	$curr = $post;
	$breadcrumbs[$curr->post_title] = get_permalink($curr->ID);
	while ($curr->post_parent) {
		$curr = get_post($curr->post_parent);
		$breadcrumbs[$curr->post_title] = get_permalink($curr->ID);
	}
	$breadcrumbs['Projects'] = '/projects';
	$breadcrumbs[get_bloginfo('name')] = '/';

	?>
	<section class="project-page <?php echo @$project_class; ?>">
		<?php echo $phragmites->get_breadcrumbs($breadcrumbs); ?>
		<div class="container">
			<div class="content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<?php
}

get_footer();
