@if ($paginator->hasPages())
    <nav class="pagination-container">
        <div class="pagination-wrapper">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="page-item">
                                <span class="page-link">{{ $page }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-item" rel="next">
                    <span class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </a>
            @else
                <span class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </span>
            @endif
        </div>

        {{-- Pagination Info --}}
        <div class="pagination-info">
            <span>{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}</span>
        </div>
    </nav>
@endif
