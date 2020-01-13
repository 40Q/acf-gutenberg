<div class="{{ $design->col_class }}">
	<pre>
		@php($prop = 'column_'.$i)
		{{--@php(print_r($content->{$prop}))--}}
	</pre>
	@foreach($content->{$prop} as $layout)
		{{--Layout: {{ $layout['acf_fc_layout'] }}--}}
		@php($layout_slug = 'test-'.$layout['acf_fc_layout'])
		@include($layout_slug)
	@endforeach
</div>