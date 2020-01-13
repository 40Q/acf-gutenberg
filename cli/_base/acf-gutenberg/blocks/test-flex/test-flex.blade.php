@block()
	<div class="container">
    @if( $rows && is_array( $rows ) )
      @foreach( $rows as $row )
        @row([
          'class' => 'custom-class',
        ])
          @if( isset( $row['columns'] ) && is_array( $row['columns'] ) )
            @foreach( $row['columns'] as $column )
              @column([
                'cols'  => count( $row['columns'] ),
              ])
                @if( isset( $column['modules'] ) && is_array( $column['modules'] ) )
                  @foreach( $column['modules'] as $module )
                    @module([
                      'module' => $module,
                    ])@endmodule
                  @endforeach
                @endif
              @endcolumn
            @endforeach
          @endif
        @endrow
      @endforeach
    @endif
	</div> {{-- end .container--}}
@endblock
