<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1><?php echo esc_html(get_post_meta(get_the_ID(), 'hero_heading', true)); ?></h1>
          <p class="mb-4">
            <?php echo esc_html(get_post_meta(get_the_ID(), 'hero_text', true)); ?>
          </p>
          <p>
            <a href="<?php echo esc_html(get_post_meta(get_the_ID(), 'shop_now_btn_link', true)); ?>" class="btn btn-secondary me-2">Shop Now</a>
            <a href="<?php echo esc_html(get_post_meta(get_the_ID(), 'explore_btn_link', true)); ?>" class="btn btn-white-outline">Explore</a>
          </p>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="hero-img-wrap">
          <?php
          $hero_image_id = get_post_meta(get_the_ID(), 'hero_image', true);
          $hero_image_src = wp_get_attachment_image_src($hero_image_id, 'large');
          if ($hero_image_src) {
              echo '<img src="' . esc_url($hero_image_src[0]) . '" class="img-fluid" />';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->
