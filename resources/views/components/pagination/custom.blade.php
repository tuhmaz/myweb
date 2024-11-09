@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {{-- First Page Link --}}
            @if (!$paginator->onFirstPage())
                <li class="page-item first">
                    <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">
                        <i class="ti ti-chevrons-left ti-sm"></i>
                    </a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item prev disabled" aria-disabled="true">
                    <a class="page-link" href="javascript:void(0);">
                        <i class="ti ti-chevron-left ti-sm"></i>
                    </a>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                        <i class="ti ti-chevron-left ti-sm"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><a class="page-link" href="javascript:void(0);">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                        <i class="ti ti-chevron-right ti-sm"></i>
                    </a>
                </li>
            @else
                <li class="page-item next disabled" aria-disabled="true">
                    <a class="page-link" href="javascript:void(0);">
                        <i class="ti ti-chevron-right ti-sm"></i>
                    </a>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item last">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">
                        <i class="ti ti-chevrons-right ti-sm"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
