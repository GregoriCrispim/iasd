@extends('layouts.app')

@section('title', 'Política de Privacidade — IASD Central de Brasília')
@section('meta-description', 'Política de privacidade e tratamento de dados pessoais e biométricos da IASD Central de Brasília.')
@section('page-name', 'Política de Privacidade')

@push('styles')
<style>
    .priv-wrap { max-width: 880px; margin: 0 auto; padding: clamp(24px, 4vw, 56px) 16px; color: #2a2a2a; line-height: 1.7; }
    .priv-wrap h1 { font-family: "Bebas Neue", "Noto Sans JP", sans-serif; color: #003366; font-size: clamp(2.2rem, 4vw, 3rem); letter-spacing: 1px; margin: 0 0 0.5rem; }
    .priv-wrap h2 { color: #003366; font-size: 1.3rem; margin: 2rem 0 0.5rem; }
    .priv-wrap p, .priv-wrap li { font-size: 0.98rem; }
    .priv-wrap ul { padding-left: 1.3rem; }
    .priv-updated { color: #777; font-size: 0.85rem; margin-bottom: 1.5rem; }
    .priv-highlight { background: #f4f8fc; border-left: 4px solid #003366; padding: 1rem 1.25rem; border-radius: 8px; margin: 1.5rem 0; }
</style>
@endpush

@section('content')
<div class="priv-wrap">
    <h1>Política de Privacidade</h1>
    <p class="priv-updated">Última atualização: 25 de julho de 2026.</p>

    <p>A IASD Central de Brasília valoriza a privacidade dos seus membros e visitantes. Esta política descreve como tratamos dados pessoais e, em especial, os dados biométricos (reconhecimento facial) utilizados na galeria de fotos.</p>

    <h2>1. Dados que coletamos</h2>
    <ul>
        <li><strong>Cadastro de membros:</strong> nome, e-mail, telefone, data de nascimento, congregação/vínculo e a informação de ser ou não membro batizado. Não coletamos CPF.</li>
        <li><strong>Fotos de eventos:</strong> imagens publicadas na galeria e os descritores faciais (vetores numéricos) das pessoas presentes nelas.</li>
        <li><strong>Busca facial:</strong> quando um membro usa a busca por selfie, extraímos um descriptor temporário no próprio navegador.</li>
    </ul>

    <h2>2. Finalidade e base legal</h2>
    <p>Os dados são tratados para permitir que membros localizem fotos em que aparecem nas programações da igreja e para a gestão da comunidade. A organização assume a base legal para o tratamento das imagens e dos descritores faciais das fotos já publicadas nos álbuns. O formulário de busca cobre exclusivamente a selfie enviada pelo próprio membro e não substitui a base legal dos rostos já indexados.</p>

    <h2>3. Como funciona a busca facial</h2>
    <div class="priv-highlight">
        <p><strong>A selfie não sai do seu dispositivo.</strong> O processamento facial acontece no seu navegador; enviamos ao servidor apenas um vetor numérico temporário (descriptor), que é usado para a comparação e descartado após a resposta. Não armazenamos a selfie nem o descriptor da busca.</p>
    </div>
    <ul>
        <li>A comparação é feita apenas entre as fotos do álbum consultado.</li>
        <li>O recurso pode apresentar falsos positivos e falsos negativos, não comprova identidade e não possui verificação de vivacidade (liveness).</li>
        <li>A busca é restrita a membros autenticados e maiores de 18 anos, ou a menores mediante declaração de consentimento do responsável legal.</li>
    </ul>

    <h2>4. Retenção</h2>
    <ul>
        <li>Os descritores faciais das fotos são mantidos enquanto a foto permanecer publicada; ao remover a foto ou o álbum, os descritores são apagados em cascata.</li>
        <li>A selfie e o descriptor de busca não são retidos.</li>
        <li>Registramos apenas o consentimento da busca (data, versão do termo e origem), sem imagem ou vetor.</li>
    </ul>

    <h2>5. Segurança</h2>
    <p>Os descritores faciais são armazenados de forma criptografada. O acesso administrativo é restrito por papéis e todas as requisições são autenticadas e protegidas contra abuso.</p>

    <h2>6. Direitos do titular</h2>
    <p>Você pode solicitar acesso, correção ou exclusão dos seus dados, bem como a remoção da sua imagem dos álbuns e dos descritores associados. Menores são representados por seus responsáveis legais.</p>

    <h2>7. Contato</h2>
    <p>Para exercer seus direitos ou tirar dúvidas, escreva para <a href="mailto:comunicacaocentralbsb@gmail.com">comunicacaocentralbsb@gmail.com</a>.</p>
</div>
@endsection
