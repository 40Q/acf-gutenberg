@mainwrapper(['block' => $block])
  <div class="container">
      <h2>{{ $block->title }}</h2>
      <p>{{ $block->text }}</p>
      <p>{{ $block->intro }}</p>
      <p>{{ $block->custom_prop }}</p>
  </div>
@endmainwrapper
