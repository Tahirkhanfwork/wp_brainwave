<?php
/*
* Template Name: Home Page
* Template Author: Tahir Khan
*/
?>

<!doctype html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<?php wp_head(); ?>
</head>

<?php get_header(); ?>

<?php include(get_stylesheet_directory() . '/pages/homepage_components/hero.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/product.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/why.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/help.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/popular.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/testimonial.php'); ?>
<?php include(get_stylesheet_directory() . '/pages/homepage_components/blog.php'); ?>


<?php get_footer(); ?>
</html>