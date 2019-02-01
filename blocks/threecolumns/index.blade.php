<section class="g-py-100">
    <div class="container">
        <div class="row no-gutters">
            <?php if ($block->columns): foreach ($block->columns as $column): ?>
            <div class="col-lg-4 g-px-40 g-mb-50 g-mb-0--lg">
                <!-- Icon Blocks -->
                <div class="text-center">
                    <span class="d-inline-block u-icon-v3 u-icon-size--xl g-bg-primary g-color-white rounded-circle g-mb-30">
                        <i class="{{ $column['icon'] }} u-line-icon-pro"></i>
                    </span>
                    <h3 class="h5 g-color-gray-dark-v2 g-font-weight-600 text-uppercase mb-3">{{ $column['title'] }}</h3>
                    <p class="mb-0">{!! $column['content'] !!}</p>
                </div>
                <!-- End Icon Blocks -->
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>
