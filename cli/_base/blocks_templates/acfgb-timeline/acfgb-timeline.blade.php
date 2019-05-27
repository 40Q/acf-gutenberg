@wrapper(['block' => $block])
@container(['block' => $block])
<h2>{{ $content['title'] }}</h2>
<p>{{ $content['intro'] }}</p>
<div class="row">
    @foreach($content['timeline'] as $milestone)
        <div class="col-3">
            {{ $milestone['date'] }}
            {{ $milestone['image'] }}
            {{ $milestone['text'] }}
        </div>
    @endforeach
</div>
<p>{{ $content['text'] }}</p>
<p>{{ $content['custom_prop'] }}</p>
@button(['block' => $block])@endbutton
@endcontainer
@endwrapper