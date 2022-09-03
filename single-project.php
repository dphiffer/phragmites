<?php get_header(); ?>
<section class="project">
	<?php echo $phragmites->get_breadcrumbs([
		$post->post_title => get_permalink(),
		'Projects' => '/projects',
		get_bloginfo('name') => '/'
	]); ?>
	<div class="container">
		<div class="content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php get_footer();
