<!-- Start Product Section -->
<div class="product-section">
  <div class="container">
    <div class="row">
      <!-- Start Column 1 -->
      <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
        <h2 class="mb-4 section-title"><?php echo esc_html(get_post_meta(get_the_ID(), 'product_heading', true)); ?></h2>
        <p class="mb-4">
          <?php echo esc_html(get_post_meta(get_the_ID(), 'product_description', true)); ?>
        </p>
        <p><a href="<?php echo esc_html(get_post_meta(get_the_ID(), 'shop_btn_link', true)); ?>" class="btn">Explore</a></p>
      </div>
      <!-- End Column 1 -->
      
      <!-- Dynamically output the product items -->
      <?php 
      $product_items = get_post_meta(get_the_ID(), 'product_items', true); 
      if ($product_items) : 
        foreach ($product_items as $product) : 
      ?>
        <!-- Start Dynamic Product Column -->
        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
          <a class="product-item" href="cart.html">
            <?php if (!empty($product['image'])) : ?>
              <img src="<?php echo esc_url(wp_get_attachment_image_url($product['image'], 'full')); ?>" class="img-fluid product-thumbnail" />
            <?php endif; ?>
            <?php if (!empty($product['title'])) : ?>
              <h3 class="product-title"><?php echo esc_html($product['title']); ?></h3>
            <?php endif; ?>
            <?php if (!empty($product['price'])) : ?>
              <strong class="product-price"><?php echo esc_html($product['price']); ?></strong>
            <?php endif; ?>

            <span class="icon-cross">
              <img src="<?= get_stylesheet_directory_uri()?>/assets/images/cross.svg" class="img-fluid" />
            </span>
          </a>
        </div>
        <!-- End Dynamic Product Column -->
      <?php 
        endforeach; 
      endif; 
      ?>
      
    </div>
  </div>
</div>
<!-- End Product Section -->
