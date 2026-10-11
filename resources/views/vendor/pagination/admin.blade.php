@if ($paginator->hasPages())
    <nav class="adm-pager" role="navigation" aria-label="Paginação">
        <p class="adm-pager__info">
            @if ($paginator->firstItem())
                Exibindo
                <strong>{{ $paginator->firstItem() }}</strong>
                a
                <strong>{{ $paginator->lastItem() }}</strong>
                de
                <strong>{{ number_format($paginator->total(), 0, ',', '.') }}</strong>
                resultados
            @else
                {{ $paginator->count() }} resultados
            @endif
        </p>

        <ul class="adm-pager__list">
            @if ($paginator->onFirstPage())
                <li class="adm-pager__item is-disabled" aria-disabled="true">
                    <span class="adm-pager__btn" aria-hidden="true">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="adm-pager__item">
                    <a class="adm-pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="adm-pager__item is-disabled" aria-disabled="true">
                        <span class="adm-pager__btn">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="adm-pager__item is-active" aria-current="page">
                                <span class="adm-pager__btn">{{ $page }}</span>
                            </li>
                        @else
                            <li class="adm-pager__item">
                                <a class="adm-pager__btn" href="{{ $url }}" aria-label="Ir para página {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="adm-pager__item">
                    <a class="adm-pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>
            @else
                <li class="adm-pager__item is-disabled" aria-disabled="true">
                    <span class="adm-pager__btn" aria-hidden="true">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
