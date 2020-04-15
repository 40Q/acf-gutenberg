@if( $tag == 'btn' )
  <button type="button" class="{{ $class }}" onclick="{{ $url }}">{{ $title }}</button>
@else
  <a href="{{ $url }}" class="{{ $class }}" target="{{ $target }}">{{ $title }}</a>
@endif
