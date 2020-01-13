@if( isset( $image ) )

    @if( $link )
      <a href="{{ $link['url'] }}" target="{{ $link['target'] }}">
    @endif

      <div class="{{ $class }} {{ $container }} {{ $aspect_ratio }}">
        {!! \ACF_Gutenberg\Includes\App::display_image( $image, $image_size, $icon, $attr ) !!}

        @if( $caption )
          <div class="{{ $class_base }}__caption">
            {{ $caption }}
          </div> {{-- end caption --}}
        @endif

      </div> {{-- end container --}}

    @if( $link )
      </a>
    @endif

@endif
