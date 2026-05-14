<header>
    <a href="{{ route('home') }}" class="header-brand" aria-label="Ir para a página inicial">
        <img src="{{ asset('img/CENTRAL DE BRASÍLIA (200 x 67 px).svg') }}" alt="Logo IASD Central de Brasília">
    </a>
    <nav class="navegation">
        <ul>
            <li><a href="{{ route('home') }}" style="margin: 0;">Início</a></li>
            <li><a href="{{ route('igreja') }}">A Igreja</a></li>
            <li class="btn_drop"><a href="javascript:void(0)">Participe <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown" style="margin-top: 6px;">
                    <li><a href="{{ route('cemab') }}">CEMAB</a></li>
                    <li><a href="{{ route('classe-novo-tempo') }}">Classe Novo Tempo</a></li>
                    <li><a href="{{ route('classe-saude') }}">Classe de Saúde</a></li>
                    <li><a href="{{ route('clube-do-livro') }}">Clube do Livro</a></li>
                    <!-- <li><a href="">Comunidades</a></li> -->
                    <li><a href="{{ route('corais') }}">Corais</a></li>
                    <!-- <li><a href="">Cursos</a></li> -->
                    <li><a href="{{ route('doutores-da-esperanca') }}">Doutores da esperança</a></li>
                    <!-- <li><a href="">MAP</a></li> -->
                    <!-- <li><a href="">Ministério de Oração</a></li> -->
                    <!-- <li><a href="">Mulher Plena</a></li> -->
                </ul>
            </li>
            <li class="btn_drop"><a href="#" onclick="return false;">Mídias <i class='bx bx-chevron-down'></i></a>
                <ul class="dropdown" style="margin-top: 6px;">
                    <li><a href="{{ route('cpb') }}">CPB</a></li>
                    <li><a href="{{ route('criacionismo') }}">Criacionismo</a></li>
                    <li><a href="{{ route('evidencias-biblicas') }}">Evidências bíblicas</a></li>
                    <li><a href="{{ route('filmes-series') }}">Filmes e séries</a></li>
                    <li><a href="{{ route('profecias') }}">Profecias</a></li>
                    <li><a href="{{ route('radio-tv-novo-tempo') }}">Rádio e TV Novo Tempo</a></li>
                </ul>
            </li>
            <li><a href="{{ route('dizimos-ofertas') }}">Dízimos e Ofertas</a></li>
        </ul>
    </nav>

    <div class="container_por_do_sol">
        <div class="container_info_por_do_sol">
            <h5>Pôr do Sol</h5>
            <div class="container_icon_por_do_sol" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                <div class="container_horario_data">
                    <h5 id="horario"></h5>
                    <h5 id="data"></h5>
                </div>
                <i class="bi bi-sunset"></i>
            </div>
        </div>
    </div>

    <div class="menu-toggle" onclick="toggleMenu()">
        <span id="menu-icon" class="icon-transition">
            <i id="bxicon" class='bx bx-menu'></i>
        </span>
        <span id="close-icon" class="icon-transition" style="display: none;">
            <i class='bx bx-x'></i>
        </span>
    </div>
</header>

