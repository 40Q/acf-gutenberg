@wrapper(['block' => $block])
@container(['block' => $block])
<div class="row">
    @if($content->title)
        <div class="col-md-6 col-12">
            <h2>{{ $content->title }}</h2>
        </div>
        <div class="col-md-6 col-12">
        </div>
    @endif
</div>
@endcontainer
@endwrapper