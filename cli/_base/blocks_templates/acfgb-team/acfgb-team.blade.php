@wrapper(['block' => $block])

    @isset( $block->members )
    <div class="container">
        <div class="row">
            <div class="col-12">
                @isset($block->title)
                <h2 class="featured-title">{{ $block->title }}</h2>
                @endisset
            </div>
        </div>
        <div class="row">
            @php($i = 1)
            @foreach($block->members as $member)
            <div class="column col-xl-3 col-md-4 col-6 text-left">
                @isset($member['image'])
                <a href="" data-toggle="modal" data-target="#TeamModal-{{$i}}">
                    <img src="{{ $member['image']['url'] }}" alt="{{ $member['name'] }}" class="img-fluid">
                </a>
                @endisset
                @isset($member['name'])
                <h4>{{ $member['name'] }}</h4>
                @endisset
                @isset($member['title'])
                <span>{{ $member['title'] }}</span>
                @endisset
                @isset($member['text'])
                <!-- The Modal -->
                <div class="modal" id="TeamModal-{{$i}}" tabindex="-1" role="dialog"  aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"> </button>
                            </div>

                            <!-- Start Modal body -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-1 d-flex flex-column justify-content-center">
                                        <a href="" class="change-modal dark-arrow-left" data-modal-id="{{$i}}" data-modal-action="before" data-block-id="{{ $block->id }}"> </a>
                                    </div>
                                    <div class="col-4">
                                        @isset($member['image'])
                                        <img src="{{ $member['image']['url'] }}" alt="{{ $member['name'] }}" class="img-fluid">
                                    </div>
                                    <div class="col-6">
                                        @endisset
                                        @isset($member['name'])
                                        <h4>{{ $member['name'] }}</h4>
                                        @endisset
                                        @isset($member['title'])
                                        <span>{{ $member['title'] }}</span>
                                        @endisset
                                        <p>{{ $member['text'] }}</p>
                                    </div>
                                    <div class="col-1 d-flex flex-column justify-content-center align-items-end">
                                        <a href="" class="change-modal dark-arrow-right" data-modal-id="{{$i}}" data-modal-action="after" data-block-id="{{ $block->id }}"> </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endisset
            </div>
            @php($i++)
            @endforeach
        </div>
    </div><!-- .container -->
    @endisset

@endwrapper
