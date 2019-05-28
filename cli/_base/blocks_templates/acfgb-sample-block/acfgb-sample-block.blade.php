@wrapper(['block' => $block])
@container(['block' => $block])
<?php
/*
echo '<pre>';
    // $block | Block object.
    print_r($block);
    // $content | Array of field in Content Tab.
    print_r($content);
    // $design | Array of field in Design Tab.
    print_r($design);
    // $custom_classes | Array of field in Class Tab.
    print_r($custom_classes);
echo '</pre>';
*/
?>
<div class="row">
    <div class="col-12">
        <h2>{{ $content['title'] }}</h2>
    </div>
    <div class="col-12">
        <p>{{ $content['text'] }}</p>
    </div>
    <div class="col-12 col-md-6">
        {!! $content['intro'] !!}
        @button(['block' => $block])@endbutton
    </div>
    <div class="col-12 col-md-6">
        <img src="{{ $content['image'] }}" alt="{{ $content['title'] }}">
    </div>
    <div class="col-12">
        @foreach($content['repeater'] as $item)
            {{ $item['text'] }} <br>
        @endforeach
    </div>
</div>
<p>{{ $content['custom_prop'] }}</p>
@endcontainer
@endwrapper