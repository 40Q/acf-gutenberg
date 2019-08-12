@wrapper(['block' => $block])
<div class="container">
        <div class="row">
            @if($title)
                <div class="col-md-4 col-12">
                    <h2>{{ $title }}</h2>
                </div>
                <div class="col-md-4 col-12">
                </div>
                <div class="col-md-4 col-12">
                </div>
            @endif
        </div>
</div><!-- .container -->
@endwrapper
