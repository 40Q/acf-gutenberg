@wrapper(['block' => $block])
    <div class="container">

        @if($title || $intro)
            <div class="row">
                <div class="col-md-5 offset-md-1">
                    @if( $title )
                        <h2>{{ $title }}</h2>
                    @endif
                </div>
                <div class="col-md-5">
                    @if( $intro )
                        <p>{{ $intro }}</p>
                    @endif
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-10 offset-md-1">
                @if( $form_shortcode )
                    <?php echo do_shortcode($block->form_shortcode); ?>
                @endif

                @if( $form_shortcode_cf7)
                    <?php echo do_shortcode($block->cf7); ?>
                @endif
            </div>
        </div>

    </div><!-- .container -->
@endwrapper