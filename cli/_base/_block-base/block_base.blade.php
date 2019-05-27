@wrapper(['block' => $block])
@container(['block' => $block])
    <h2>{{ $block->title }}</h2>
    <p>{{ $block->text }}</p>
    <p>{{ $block->intro }}</p>
    <p>{{ $block->custom_prop }}</p>
    @button(['button' => $block->button])@endbutton
@endcontainer
@endwrapper
