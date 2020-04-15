<div class="{{ $class }}">
  @if( $module )
    @include( $module )
  @endif
</div>


{{--<div class="{{ $class }}">--}}
{{--  <h1>C: Module</h1>--}}
{{--  @isset( $composer )--}}
{{--    <h2 style="background-color: green; color: white; padding: 20px;">Composer says: {{ $composer }}</h2>--}}
{{--  @else--}}
{{--    <h2 style="background-color: red; color: white; padding: 40px;">Not working</h2>--}}
{{--  @endif--}}

{{--  @isset( $composerTest )--}}
{{--    <h2 style="background-color: green; color: white; padding: 20px;">Composer says: {!! $composerTest !!}</h2>--}}
{{--  @else--}}
{{--    <h2 style="background-color: red; color: white; padding: 40px;">Not working from Class</h2>--}}
{{--  @endif--}}

{{--  @isset( $message )--}}
{{--    <h3 style="background-color: green; color: white; padding: 20px;">{{ $message }}</h3>--}}
{{--  @else--}}
{{--    <h3 style="background-color: red; color: white; padding: 40px;">No message</h3>--}}
{{--  @endif--}}

{{--  <h4>{{ $non_overwritable }}</h4>--}}
{{--</div>--}}

