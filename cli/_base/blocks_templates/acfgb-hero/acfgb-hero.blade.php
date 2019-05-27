@wrapper(['block' => $block])

    <div class="block-foreground">
        <div class="container {{ $block->text_align }}">
            <h2 class="h1 featured-title @if (!$block->intro) no-margin @endif ">{!! $block->heading !!}</h2>
            <div class="intro">{!! $block->intro !!}</div>
        </div><!-- .container-fluid -->
    </div>

    @if ($block->overlay)
        <div class="block-overlay"></div>
    @endif

    <div class="block-background{{ $block->text_align == 'text-left' ? ' custom-hero-bg' : '' }}">
        @if ($block->add_video)
        <video width="100%" src="app/uploads/kdc-hero.mp4" autoplay loop muted playsinline></video>
        @else
            {!! wp_get_attachment_image($block->bg_image, 'full') !!}
        @endif

    </div><!-- .block-background -->

@endwrapper
