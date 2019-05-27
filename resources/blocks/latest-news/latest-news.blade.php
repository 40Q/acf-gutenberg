@wrapper(['block' => $block])
@container(['block' => $block])
    <h2>{{ $content['title'] }}</h2>
    <div class="row">
        @if($content['latest_posts'])
            @while ($content['latest_posts']->have_posts()) @php( $content['latest_posts']->the_post() )
            <div class="col-md-4">
                {{ the_post_thumbnail() }}
                <h3><a href="{{ get_the_permalink() }}">{{ get_the_title() }}</a></h3>
            </div>
            @endwhile
        @endif
    </div>
    @button(['block' => $block])@endbutton
@endcontainer
@endwrapper