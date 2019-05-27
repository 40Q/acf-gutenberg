@wrapper(['block' => $block])
@container(['block' => $block])
    <h2>{{ $content['text'] }}</h2>
    <p>{{ $content['author'] }}</p>

    <p>{{ $design['bg_image'] }}</p>
@button(['block' => $block])@endbutton
@endcontainer
@endwrapper