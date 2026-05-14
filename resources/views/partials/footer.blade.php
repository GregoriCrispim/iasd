<footer>
    <div class="img_rodape">
        <img src="{{ asset('img/rodape/APlaC_oPlano_LogotipoSimp_White (1).webp') }}" alt="" loading="lazy" decoding="async">
        <div class="footer-copyright">
            © {{ date('Y') }} Igreja Adventista do Sétimo Dia Central de Brasília
        </div>
    </div>
    <div class="contato">
        <h2>contato</h2>
        <div class="info_contato">
            <span>
                <img src="{{ asset('img/rodape/phone-solid-36.webp') }}" alt="" loading="lazy" decoding="async">
                <p style="margin: 0;">(61) 98157-4702</p>
            </span>
            <span style="margin-bottom: 10px;">
                <img src="{{ asset('img/rodape/envelope-solid-36.webp') }}" alt="" style="width: 10%;" loading="lazy" decoding="async">
                <p style="margin: 0;">comunicacaocentralbsb@gmail.com</p>
            </span>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3838.525162661414!2d-47.90483039999999!3d-15.828972299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x935a254853ffffff%3A0x45302746a1b9ef23!2sIgreja%20Adventista%20do%20S%C3%A9timo%20Dia%20Central%20de%20Bras%C3%ADlia!5e0!3m2!1spt-BR!2sbr!4v1744336914378!5m2!1spt-BR!2sbr" style="border:0; width: 80%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="redes_e_atend">
        <h2>Horários de Atendimento<br>pastoral</h2>
        <p style="text-align: center;">Terça a Quinta: 13h00-14h30</p>
        <h2>Redes Sociais</h2>
        <div class="icones_redes">
            <a href="https://www.facebook.com/share/18C9sd7nvQ/" target="_blank" rel="noopener noreferrer" aria-label="Facebook (abre em nova aba)">
                <img src="{{ asset('img/rodape/facebook-circle-logo-36.webp') }}" alt="Facebook" loading="lazy" decoding="async">
            </a>
            <a href="https://www.instagram.com/iasdbrasilia/" target="_blank" rel="noopener noreferrer" aria-label="Instagram (abre em nova aba)">
                <img src="{{ asset('img/rodape/instagram-logo-36.webp') }}" alt="Instagram" loading="lazy" decoding="async">
            </a>
            <a href="https://www.youtube.com/@adventistascentralbrasilia" target="_blank" rel="noopener noreferrer" aria-label="YouTube (abre em nova aba)">
                <img src="{{ asset('img/rodape/youtube-logo-36.webp') }}" alt="YouTube" loading="lazy" decoding="async">
            </a>
            <a href="https://www.tiktok.com/@igrejaadvcentraldebsb?_r=1&_t=ZS-94uacDC2BQ0" target="_blank" rel="noopener noreferrer" aria-label="TikTok (abre em nova aba)">
                <img src="{{ asset('img/rodape/tiktok-logo-36.webp') }}" alt="TikTok" loading="lazy" decoding="async">
            </a>
        </div>

        <h2>Central de Ajuda</h2>
        <a class="footer-devteam-btn footer-devteam-btn--faq" href="{{ route('faq') }}">
            Conheça
        </a>

        <h2>Time de Desenvolvimento</h2>
        <a class="footer-devteam-btn" href="{{ route('time-desenvolvimento') }}">
            Conheça
        </a>
    </div>

</footer>

