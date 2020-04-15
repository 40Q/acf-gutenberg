@wrapper(['block' => $block])
    <div class="container">
        <div class="row">
            @if($title)
                <div class="col-12">
                    <h2>{{ $title }}</h2>
                </div>
            @endif
        </div>
        @if($repeater)
            <pre>
                @php(print_r($repeater))
            </pre>
        <div class="row">
            @foreach($repeater as $card)
                <div class="col-md-3">
                    <h2>{{ $card['text'] }}</h2>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-3">
                <h2>{{ $custom_text }}</h2>
                <p>{{ $block_cols }}</p>
            </div>
        </div>
            @if( $custom_prop )
                <div class="row">
                    <div class="col-12">
                        <pre>
                            @php(print_r($custom_prop->posts))
                        </pre>
                    </div>
                </div>
            @endif
        @endif
    </div><!-- .container -->
@endwrapper
