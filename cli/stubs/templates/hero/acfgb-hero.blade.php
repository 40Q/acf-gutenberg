@wrapper(['block' => $block])
    <div class="container">
        <div class="block-foreground">
            <div class="container {{ $design['text_align'] }}">
                <h2 class="h1 featured-title @if ($content['intro']) no-margin @endif ">{!! $content['heading'] !!}</h2>
                <div class="intro">{!! $content['intro'] !!}</div>
                @button(['block' => $block])@endbutton
            </div><!-- .container-fluid -->
        </div>

        @if ($design['overlay'])
            <div class="block-overlay"></div>
        @endif

        <div class="block-background{{ $design['text_align'] == 'text-left' ? ' custom-hero-bg' : '' }}">
            @if ($content['add_video'])
            <video width="100%" src="app/uploads/kdc-hero.mp4" autoplay loop muted playsinline></video>
            @else
                {!! wp_get_attachment_image($design['bg_image'], 'full') !!}
            @endif

        </div><!-- .block-background -->
    </div><!-- .container -->
@endwrapper