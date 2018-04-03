@if ($paginator->hasPages())
    <ul class="list-inline text-center">
        @if ($paginator->onFirstPage())
            <li class="list-inline-item">
                <span class="badge badge-pagination badge-light text-secondary p-3">&leftarrow;</span>
            </li>
        @else
            <li class="list-inline-item">
                <a class="badge badge-pagination badge-light text-primary p-3" href="{{ $paginator->previousPageUrl() }}" rel="prev">&leftarrow;</a>
            </li>
        @endif
        
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="list-inline-item">
                        <span class="text-secondary">{{ $element }}</span>
                    </li>
                @endif
    
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="list-inline-item">
                                <span class="badge badge-pagination badge-primary p-3 px-4">{{ $page }}</span>
                            </li>
                        @else
                            <li class="list-inline-item">
                                <a class="badge badge-pagination badge-pagination-inactive badge-light text-primary p-3 px-4" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        
        @if ($paginator->hasMorePages())
            <li class="list-inline-item">
                <a class="badge badge-pagination badge-light text-primary p-3" href="{{ $paginator->nextPageUrl() }}" rel="next">&rightarrow;</a>
            </li>
        @else
            <li class="list-inline-item">
                <span class="badge badge-pagination badge-light text-secondary p-3">&rightarrow;</span>
            </li>
        @endif
    </ul>
@endif
