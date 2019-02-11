
<section class="">
  <div class="container">
    <h2>{{ $block->title }}</h2>
    <div class="row">
    @while ($block->latest_posts->have_posts()) @php( $block->latest_posts->the_post() )
      <div class="col-md-4">
        {{ the_post_thumbnail() }}
        <h3><a href="{{ get_the_permalink() }}">{{ get_the_title() }}</a></h3>
      </div>      
    @endwhile
    </div>
  </div>
</section>
