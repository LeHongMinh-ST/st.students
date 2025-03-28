<div>
    @if ($totalPages > 1)
        <ul class="mt-4 pagination pagination-flat d-flex justify-content-center">
            {{-- Previous Page Link --}}
            @if ($currentPage == 1)
                <li class="page-item disabled">
                    <span class="rounded page-link">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="rounded page-link" href="javascript:void(0)" wire:click="previousPage">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach (range(1, $totalPages) as $page)
                @if ($page == $currentPage)
                    <li class="page-item active"><span class="rounded page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="rounded page-link" href="javascript:void(0)" wire:click="gotoPage({{ $page }})">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($currentPage == $totalPages)
                <li class="page-item disabled">
                    <span class="rounded page-link">&rsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="rounded page-link" href="javascript:void(0)" wire:click="nextPage">&rsaquo;</a>
                </li>
            @endif
        </ul>
    @endif
</div>
