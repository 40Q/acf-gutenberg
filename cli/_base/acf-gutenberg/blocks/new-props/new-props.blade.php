{{--@block(['block' => $block])--}}
@block()
	<div class="container">
    <h1>Components</h1>

    @module([
      'class'            => 'custom-class-from-blade',
      'non_overwritable' => 'Value from Blade',
      'message'          => 'original message',
    ])@endmodule

    @column([
      'class' => 'custom-class-col',
    ])@endcolumn
    <hr>
    <hr>
  </div>
	<div class="container">

		<h5>{{ $title }}</h5>
		<pre>
			<h3>Block var</h3>
			<h1>{{ $title }}</h1>
			<img src="{{ $background_image }}" alt="">
			<p>{!! $intro !!}</p>
			<h3>Blocks Props</h3>
{{--			@php(print_r($block))--}}
		</pre>



	</div>
@endblock
