@wrapper(['block' => $block])
@container(['block' => $block])
<h2>{{ $content['title'] }}</h2>
<p>{{ $content['intro'] }}</p>
<div class="row">
    @foreach($content['members'] as $member)
        <div class="col-3">
            <h4>{{ $member['name'] }}</h4>
            {{--<p>{{ $member['image'] }}</p>--}}
            <p>{{ $member['title'] }}</p>
            <p>{{ $member['text'] }}</p>
            <img src="{{ $member['image'] }}" alt="{{ $member['title'] }}">
        </div>
    @endforeach
</div>
<p>{{ $content['text'] }}</p>
<p>{{ $content['custom_prop'] }}</p>
@button(['block' => $block])@endbutton
@endcontainer
@endwrapper