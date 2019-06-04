@wrapper(['block' => $block])
@container(['block' => $block])
<?php
/**
 *  This is a sample block.
 *  The content of the block is separated into 3 variables: $content | $design | $custom_classes
 *  You can uncomment the code below to view the objects content.
 *
 *  You can access the entire object of the block using the variable $block
 *
 */

/*
echo '<pre>';
    // $block | Block object.
    print_r($block);
    // $content | Object of field in Content Tab.
    print_r($content);
    // $design | Object of field in Design Tab.
    print_r($design);
    // $custom_classes | Object of field in Class Tab.
    print_r($custom_classes);



    // You can define custom props in init method. This example use \WP_Query().
    print_r($content->custom_prop)
echo '</pre>';
*/


?>
<div class="row">
    @if($content->title)
        <div class="col-12">
            <h2>{{ $content->title }}</h2>
        </div>
    @endif
    @if($content->text)
        <div class="col-12">
            <p>{{ $content->text }}</p>
        </div>
    @endif
    <div class="col-12 col-md-6">
        @if($content->intro)
            {!! $content->intro !!}
            @button(['block' => $block])@endbutton
        @endif
    </div>
    <div class="col-12 col-md-6">
        @if($content->image)
            <img src="{{ $content->image }}" alt="{{ $content->title }}" width="300">
        @endif
    </div>
    <div class="col-12">
        @if($content->repeater)
            @foreach($content->repeater as $item)
                <p>{{ $item['text'] }}</p>
                <img src="{{ $item['image'] }}" alt="{{ $content->title }}" width="300">
            @endforeach
        @endif
    </div>
</div>

@endcontainer
@endwrapper