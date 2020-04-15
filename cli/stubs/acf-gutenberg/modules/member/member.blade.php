@image([
  'image'        => $image,
  'use_caption'  => $use_caption,
  'caption'      => $caption,
  'aspect_ratio' => $aspect_ratio,
  'link'         => $link,
  'attr'         => $attr,
])@endimage
@if( isset( $link['url'] ) && isset( $link['target'] ) )
  <a href="{{ $link['url'] }}" target="{{ $link['target'] }}">
@endif
    <div class="member__name">{{ $name }}</div>
@if( isset( $link['url'] ) && isset( $link['target'] ) )
  </a>
@endif
<div class="member__position">{{ $position }}</div>
<div class="member__company">{{ $company }}</div>
