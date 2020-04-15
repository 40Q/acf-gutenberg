@block()
    <div class="container">
        <div class="row">
            @if($title)
                <div class="col-12">
                    <h2>{{ $title }}</h2>
                </div>
            @endif
        </div>
    </div><!-- .container -->
@endblock
