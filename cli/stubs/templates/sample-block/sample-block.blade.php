@block()
    <div class="container">
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

            // You can define custom props in init method. This example use \WP_Query().
            print_r($custom_prop)
        echo '</pre>';
        */


        ?>
        <div class="row">
            @if($title)
                <div class="col-12">
                    <h2>{{ $title }}</h2>
                </div>
            @endif
            @if($text)
                <div class="col-12">
                    <p>{{ $text }}</p>
                </div>
            @endif
            <div class="col-12 col-md-6">
                @if($intro)
                    {!! $intro !!}
                    @button(['block' => $block])@endbutton
                @endif
            </div>
            <div class="col-12 col-md-6">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $title }}" width="300">
                @endif
            </div>
            <div class="col-12">
                @if($repeater)
                    @foreach($repeater as $item)
                        <p>{{ $item['text'] }}</p>
                        <img src="{{ $item['image'] }}" alt="{{ $content->title }}" width="300">
                    @endforeach
                @endif
            </div>
        </div>

    </div><!-- .container -->
@endblock
