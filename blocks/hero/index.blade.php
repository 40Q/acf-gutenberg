<section class="g-pos-rel">
  <div class="dzsparallaxer auto-init height-is-based-on-content use-loading mode-scroll loaded dzsprx-readyall" data-options="{direction: 'reverse', settings_mode_oneelement_max_offset: '150'}">
    <div class="divimage dzsparallaxer--target w-100 g-bg-cover g-bg-pos-top-center g-bg-img-hero g-bg-bluegray-opacity-0_2--after" style="height: 130%; background-image: url(<?= $block->image['url']; ?>); transform: translate3d(0px, -60.1227px, 0px);"></div>

    <div class="container g-bg-cover__inner g-py-100">
      <div class="row align-items-center">
        <div class="col-lg-6 g-mb-30 g-mb-0--lg">
          <h2 class="h1 text-uppercase g-color-white g-mb-30">
            <?= $block->heading; ?>
          </h2>
          <h3 class="h4 g-color-white">
            <?= get_field('intro'); ?>
          </h3>
        </div>
        <div class="col-lg-6">
          <!-- Vimeo Example -->
          <div class="embed-responsive embed-responsive-16by9">
            <iframe src="<?= $block->video_url; ?>" width="530" height="300" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
          </div>
          <!-- End Vimeo Example -->
        </div>
      </div>
    </div>
  </div>
</section>
