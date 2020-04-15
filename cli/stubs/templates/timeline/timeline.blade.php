@block()
<div class="container">
        <h2>{{ $title }}</h2>
        <p>{{ $intro }}</p>
        <div class="row">
            @foreach($timeline as $milestone)
                <div class="col-3">
                    {{ $milestone['date'] }}
                    {{--{{ $milestone['image'] }}--}}
                    {{ $milestone['text'] }}
                </div>
            @endforeach
        </div>
        <p>{{ $text }}</p>
        <p>{{ $custom_prop }}</p>
        @button(['block' => $block])@endbutton
</div><!-- .container -->
@endblock
