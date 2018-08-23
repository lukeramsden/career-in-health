@if ($paginator->hasPages())
    <ul class="pagination justify-content-center my-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item page-item-previous disabled"><a class="page-link" href="javascript:" tabindex="-1">&lt;</a></li>
        @else
            <li class="page-item page-item-previous"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item page-item-next"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a></li>
        @else
            <li class="page-item page-item-next disabled"><a class="page-link" href="javascript:">&gt;</a></li>
        @endif
    </ul>
@else
    thats all
@endif
