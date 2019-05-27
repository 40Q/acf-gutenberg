@wrapper(['block' => $block])

<div class="container">
        <div class="grid">
            <div class="grid-sizer"></div>
            <div class="gutter-sizer"></div>
            @foreach ($block->images as $image)
            @php( $image_category = get_field( 'category', $image['ID'] ) )
            <div class="grid-item{{ get_field( 'featured', $image['ID'] ) == 1  ? ' grid-item--width2' : '' }} {{ is_array($image_category) ? implode(" ", $image_category) : '' }}">
                <div class="gallery-image">
                    {!! wp_get_attachment_image($image['ID'],get_field( 'featured', $image['ID'] ) == 1  ? 'large' : 'medium_large') !!}
                </div>
            </div>

            @endforeach
        </div>
    </div>

@endwrapper
