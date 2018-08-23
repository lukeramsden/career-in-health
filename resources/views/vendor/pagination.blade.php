<ul class="pagination justify-content-center my-4">
    @if ($paginator->onFirstPage())
        <li class="page-item page-item-previous disabled"><a class="page-link" href="javascript:"
                                                             tabindex="-1">&lt;</a></li>
    @else
        <li class="page-item page-item-previous"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"
                                                    rel="prev">&lt;</a></li>
    @endif
    
    @if ($paginator->hasPages())
        {{-- Pagination Elements --}}
        @isset($elements)
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item page-item-ellipses disabled"><a class="page-link"
                                                                         href="javascript:">{{ $element }}</a></li>
                @endif
                
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item page-item-active"><a class="page-link"
                                                                      href="javascript:">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endisset
    @endif
    
    @if ($paginator->hasMorePages())
        <li class="page-item page-item-next"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"
                                                rel="next">&gt;</a>
        </li>
    @else
        <li class="page-item page-item-next disabled"><a class="page-link" href="javascript:">&gt;</a></li>
    @endif
</ul>
