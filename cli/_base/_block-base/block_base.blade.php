@wrapper(['block' => $block])
@container(['block' => $block])
    <h2>{{ $content['title'] }}</h2>
    <p>{{ $content['text'] }}</p>
    <p>{{ $content['intro'] }}</p>
    <p>{{ $content['custom_prop'] }}</p>
    @button(['block' => $block])@endbutton
@endcontainer
@endwrapper