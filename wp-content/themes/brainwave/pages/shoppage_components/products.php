<?php

$args = array(
'post_type'=> 'product',
'orderby'    => 'ID',
'post_status' => 'publish',
'order'    => 'ASC',
'posts_per_page' => -1
);
$result = new WP_Query( $args );
?>

<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Shop</h1>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		

		<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
		      	<div class="row">

		      		<?php if ( $result-> have_posts() ) : 
					 while ( $result->have_posts() ) : $result->the_post(); ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#">
							<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ))[0] ?>" class="img-fluid product-thumbnail">
							<h3 class="product-title"><?php the_title(); ?></h3>
							<strong class="product-price"><?php the_content(); ?></strong>

							<span class="icon-cross">
								<img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<?php endwhile; ?>
					<?php endif; wp_reset_postdata(); ?>

					<?php if ( $result-> have_posts() ) : 
					 while ( $result->have_posts() ) : $result->the_post(); ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#">
							<img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ))[0] ?>" class="img-fluid product-thumbnail">
							<h3 class="product-title"><?php the_title(); ?></h3>
							<strong class="product-price"><?php the_content(); ?></strong>

							<span class="icon-cross">
								<img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/cross.svg" class="img-fluid">
							</span>
						</a>
					</div>
					<?php endwhile; ?>
					<?php endif; wp_reset_postdata(); ?>

		      	</div>
		    </div>
		</div>