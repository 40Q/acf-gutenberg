<section
	id="{{ $id }}"
	class="{{ $class }}"
	@if( $styles )
	style="{{ $styles }}"
	@endif
>
	{{ $slot }}
</section>
