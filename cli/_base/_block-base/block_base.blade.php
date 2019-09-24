@wrapper(['block' => $block])
	<div class="container">
		@if( $title )
			<h2>{{ $title }}</h2>
		@endif
		@if( $intro )
			{!! $intro !!}
		@endif
	</div>
@endwrapper
