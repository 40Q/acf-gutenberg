@isset($block->content['button']['link'])
    @isset($block->content['button']['text'])
        <a  href="{{ $block->content['button']['link'] }}"
            class="btn {{ $block->content['button']['class'] }} {{ $block->custom_classes['button_class'] }}"
            @isset($block->content['button']['target'])
            target="{{ $block->content['button']['target'] }}"
                @endisset
        >
            {{ $block->content['button']['text'] }}
            @if($block->content['button']['icon'])
                >
            @endif
        </a>
    @endisset
@endisset