@wrapper(['block' => $block])

    <div class="container">
        @isset( $block->title )
            <div class="row">
                <div class="col-md-12">
                    <h2 class="featured-title">{{ $block->title }}</h2>
                    @isset( $block->intro )
                        <div class="intro">
                            {!! $block->intro !!}
                        </div>
                    @endisset
                </div>
            </div>
        @endisset
        @if( $block->timeline )
            <?php $total = count($block->timeline); ?>
            <div class="row timeline">
                <div class="col-12">
                @php($i = 1)
                @foreach($block->timeline as $milestone)
                    <?php $odd = ($i % 2) ? true : false; ?>
                    <?php $image_order = ($odd) ? '1' : '3'; ?>
                    <?php $text_order = ($odd) ? '3' : '1'; ?>
                    <div class="row timeline">
                        <div class="column col-md-5 col-image order-md-{{$image_order}}">
                            <div class="image-container">
                            @isset($milestone['image']['url'])
                                <img src="{{ $milestone['image']['url'] }}" alt="" class="img-fluid">
                            @endisset
                            </div>
                        </div>
                        <div class="column col-center col-md-2 order-md-2">
                            <div class="arrow"></div>
                            <div class="line{{ $i == 1 ? ' first-line' : '' }}{{ $i == $total ? ' last-line' : '' }}"></div>
                        </div>
                        <div class="column col-md-5 col-text order-md-{{$text_order}}">
                            <div class="bg-gray-lighter">
                                @isset($milestone['date'])
                                    <h4 class="h2 featured-title">{{ $milestone['date'] }}</h4>
                                @endisset
                                @isset($milestone['text'])
                                    <p>{{ $milestone['text'] }}</p>
                                @endisset
                            </div>
                        </div>
                    </div>
                    @php($i++)
                @endforeach
                </div>
            </div>
        @endif

    </div>

@endwrapper
