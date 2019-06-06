@if($block->content->button->link)
    @if($block->content->button->text)
        <a  href="{{ $block->content->button->link }}"
            class="btn {{ $block->content->button->class }} {{ $block->custom_classes->button_class }}"
            @if($block->content->button->target)
            target="{{ $block->content->button->target }}"
                @endif
        >
            {{ $block->content->button->text }}
            @if($block->content->button->icon)
                >
            @endif
        </a>
    @endif
@endif