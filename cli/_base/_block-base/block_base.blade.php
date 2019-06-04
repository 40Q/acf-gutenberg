@wrapper(['block' => $block])
	@container(['block' => $block])
		@if( $content->title )
			<h2>{{ $content->title }}</h2>
		@endif
		@if( $content->intro )
			{!! $content->intro !!}
		@endif
	@endcontainer
@endwrapper
