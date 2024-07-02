<?php
/*
* Template Name: Shop Page
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

<?php include(get_stylesheet_directory() . '/pages/shoppage_components/products.php'); ?>

<?php get_footer(); ?>
</html>