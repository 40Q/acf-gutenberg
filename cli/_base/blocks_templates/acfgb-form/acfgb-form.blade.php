@wrapper(['block' => $block])
@container(['block' => $block])
    @if(isset($content['title']) || isset($content['intro']))
        <div class="row">
            <div class="col-md-5 offset-md-1">
                @isset( $content['title'] )
                    <h2>{{ $content['title'] }}</h2>
                @endisset
            </div>
            <div class="col-md-5">
                @isset( $content['intro'] )
                    <p>{{ $content['intro'] }}</p>
                @endisset
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-10 offset-md-1">
            @isset( $content['form_shortcode'] )
                <?php echo do_shortcode($block->form_shortcode); ?>
            @endisset

            @isset( $content['form_shortcode_cf7'])
                <?php echo do_shortcode($block->cf7); ?>
            @endisset
        </div>

    </div>
@endcontainer
@endwrapper