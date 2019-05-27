@wrapper(['block' => $block])

    <div class="block-foreground">
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-1 quote">
                    <p>{{ $block->text }}</p>
                    <p class="author">- {{ $block->author }}</p>
                </div>
            </div>
        </div><!-- .container-fluid -->
    </div>

    <div class="block-overlay"></div>

    <div class="block-background">
        {!! wp_get_attachment_image($block->bg_image, 'large') !!}
    </div><!-- .block-background -->

@endwrapper
