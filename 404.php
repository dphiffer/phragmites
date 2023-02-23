<?php

$phragmites->set_social_card([
	'title' => 'Page not found - ' . get_bloginfo('name')
]);
get_header();

?>

<div class="container">
	<h1>Page not found</h1>
	<p>Sorry, the page you're looking for is not here. Maybe you want to check what’s on the <a href="/">front page</a>?</p>
</div>
<?php

get_footer();
