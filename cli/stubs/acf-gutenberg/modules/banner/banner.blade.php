{{--Usamos Inner divs?--}}

{{--<div class="{{ $class }}">--}}
  @image([
    'image'        => $image,
    'use_caption'  => $use_caption,
    'caption'      => $caption,
    'aspect_ratio' => $aspect_ratio,
    'link'         => $link,
  ])@endimage
  <p>{!! $content !!}</p>
{{--</div>--}}
