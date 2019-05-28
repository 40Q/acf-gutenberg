@wrapper(['block' => $block])
@container(['block' => $block])
    @isset($content['title'])
        <h2>{{ $content['title'] }}</h2>
    @endisset
    @isset($content['text'])
        <p>{{ $content['text'] }}</p>
    @endisset
    @isset($content['intro'])
        <p>{!! $content['intro'] !!}</p>
    @endisset
    @isset($content['custom_prop'])
        <p>{{ $content['custom_prop'] }}</p>
    @endisset
    @button(['block' => $block])@endbutton
@endcontainer
@endwrapper