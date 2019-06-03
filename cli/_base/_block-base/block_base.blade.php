@wrapper(['block' => $block])
	@container(['block' => $block])
		@if( $content->title )
			<h2>{{ $content->title }}</h2>
		@endif
		@if( $content->text )
			<p>{{ $content->text }}</p>
		@endif
		@if( $content->intro )
			{!! $content->intro !!}
		@endif
		@if( $content->custom_prop )
			<p>{{ $content->custom_prop }}</p>
		@endif
		@button(['block' => $block])@endbutton
	@endcontainer
@endwrapper
