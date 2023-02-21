<?php

get_header();

global $post;
$breadcrumbs = [];
$curr = $post;
$breadcrumbs[$curr->post_title] = get_permalink($curr->ID);
while ($curr->post_parent) {
	$curr = get_post($curr->post_parent);
	$breadcrumbs[$curr->post_title] = get_permalink($curr->ID);
}
$breadcrumbs[get_bloginfo('name')] = '/';

?>
<section class="subpage-page subpage--<?php the_field('subpage_class'); ?>">
	<?php echo $phragmites->get_breadcrumbs($breadcrumbs); ?>
	<div class="container">
		<div class="content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php get_footer();
