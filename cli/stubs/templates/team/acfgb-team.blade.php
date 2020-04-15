@wrapper(['block' => $block])
    <div class="container">
        <h2>{{ $title }}</h2>
        <p>{{ $intro }}</p>
        <div class="row">
            @foreach($members as $member)
                <div class="col-3">
                    <h4>{{ $member['name'] }}</h4>
                    {{--<p>{{ $member['image'] }}</p>--}}
                    <p>{{ $member['title'] }}</p>
                    <p>{{ $member['text'] }}</p>
                    <img src="{{ $member['image'] }}" alt="{{ $member['title'] }}">
                </div>
            @endforeach
        </div>
        <p>{{ $text }}</p>
        <p>{{ $custom_prop }}</p>
        @button(['block' => $block])@endbutton
    </div><!-- .container -->
@endwrapper