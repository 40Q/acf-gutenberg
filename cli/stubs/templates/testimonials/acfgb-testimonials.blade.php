@wrapper(['block' => $block])
    <div class="container">
        <h2>{{ $text }}</h2>
        <p>{{ $author }}</p>

        <p>{{ $bg_image }}</p>
        @button(['block' => $block])@endbutton
    </div><!-- .container -->
@endwrapper