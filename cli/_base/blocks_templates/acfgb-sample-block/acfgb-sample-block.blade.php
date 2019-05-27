@wrapper(['block' => $block])
@container(['block' => $block])
    {{--<pre>--}}
        {{--$block | Block object.--}}
        {{--@php(print_r($block))--}}
        {{--$content | Array of field in Content Tab.--}}
        {{--@php(print_r($content))--}}
        {{--$design | Array of field in Design Tab.--}}
        {{--@php(print_r($design))--}}
        {{--$custom_classes | Array of field in Class Tab.--}}
        {{--@php(print_r($custom_classes))--}}
    {{--</pre>--}}
    <h2>{{ $content['title'] }}</h2>
    <p>{{ $content['text'] }}</p>
    <p>{{ $content['intro'] }}</p>
    <p>{{ $content['custom_prop'] }}</p>
    @button(['block' => $block])@endbutton
@endcontainer
@endwrapper
