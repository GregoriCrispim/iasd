<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doutores_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('cpf', 11)->nullable()->unique();
            $table->string('status', 20)->default('pendente'); // ativo|pendente|inativo
            $table->string('genero', 20)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('cidade')->nullable();
            $table->string('hospital_preferido')->nullable();
            $table->string('dia_preferido')->nullable();
            $table->string('horario_preferido')->nullable();
            $table->string('igreja')->nullable();
            $table->string('cargo')->nullable();
            $table->json('cargos')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('coordenador_responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('termos_aceitos_em')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_polos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cep', 8)->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->default('DF');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('coordenador')->nullable();
            $table->unsignedInteger('quantidade_voluntarios')->default(0);
            $table->string('status', 20)->default('ativo');
            $table->timestamps();
        });

        Schema::create('doutores_hospitais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polo_id')->nullable()->constrained('doutores_polos')->nullOnDelete();
            $table->string('nome');
            $table->string('cep', 8)->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->default('DF');
            $table->string('status', 20)->default('ativo');
            $table->timestamps();
        });

        Schema::create('doutores_coordenador_polo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('polo_id')->constrained('doutores_polos')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'polo_id']);
        });

        Schema::create('doutores_dominios', function (Blueprint $table) {
            $table->id();
            $table->string('categoria', 40); // funcao|igreja|dia_preferido|turno_preferido
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['categoria', 'descricao']);
        });

        Schema::create('doutores_plantoes', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('hora_inicio', 10)->nullable();
            $table->string('hora_fim', 10)->nullable();
            $table->string('hospital');
            $table->string('endereco')->nullable();
            $table->string('tipo', 40)->default('visita_mensal');
            $table->json('voluntarios')->nullable();
            $table->json('equipes')->nullable();
            $table->string('status', 20)->default('aberto');
            $table->text('observacoes')->nullable();
            $table->timestamp('inscricao_inicio')->nullable();
            $table->timestamp('inscricao_fim')->nullable();
            $table->unsignedInteger('quartos_visitados')->default(0);
            $table->unsignedInteger('visitas_realizadas')->default(0);
            $table->unsignedInteger('livros_distribuidos')->default(0);
            $table->timestamp('resultados_registrados_em')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plantao_id')->nullable()->constrained('doutores_plantoes')->nullOnDelete();
            $table->string('hospital')->nullable();
            $table->string('cidade')->nullable();
            $table->date('data')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('turno')->nullable();
            $table->string('papel')->nullable();
            $table->string('status', 20)->default('pendente');
            $table->timestamp('confirmada_por_voluntario_em')->nullable();
            $table->text('justificativa')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipo', 40);
            $table->string('nome_arquivo');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->unsignedInteger('tamanho')->default(0);
            $table->string('status', 20)->default('enviado');
            $table->timestamps();
        });

        Schema::create('doutores_testemunhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantao_id')->constrained('doutores_plantoes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('texto');
            $table->timestamps();
            $table->unique(['plantao_id', 'user_id']);
        });

        Schema::create('doutores_avisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantao_id')->constrained('doutores_plantoes')->cascadeOnDelete();
            $table->unsignedSmallInteger('equipe_numero')->nullable();
            $table->string('titulo');
            $table->text('conteudo')->nullable();
            $table->foreignId('autor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('doutores_aviso_leituras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aviso_id')->constrained('doutores_avisos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('lido_em')->useCurrent();
            $table->unique(['aviso_id', 'user_id']);
        });

        Schema::create('doutores_solicitacoes_estudo', function (Blueprint $table) {
            $table->id();
            $table->string('nome_paciente');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('hospital')->nullable();
            $table->date('data_solicitacao')->nullable();
            $table->string('dia_preferido')->nullable();
            $table->string('horario_preferido')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('status', 20)->default('pendente');
            $table->string('atribuido_a')->nullable();
            $table->foreignId('solicitado_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('solicitado_por_nome')->nullable();
            $table->foreignId('plantao_id')->nullable()->constrained('doutores_plantoes')->nullOnDelete();
            $table->timestamp('encaminhado_em')->nullable();
            $table->timestamp('em_andamento_em')->nullable();
            $table->timestamp('concluido_em')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_solicitacao_estudo_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitacao_id')->constrained('doutores_solicitacoes_estudo')->cascadeOnDelete();
            $table->string('status', 20);
            $table->string('executado_por')->nullable();
            $table->string('executado_por_perfil')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_publicacoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo')->nullable();
            $table->json('imagens')->nullable();
            $table->foreignId('autor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('publicado');
            $table->timestamps();
        });

        Schema::create('doutores_comentarios_publicacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publicacao_id')->constrained('doutores_publicacoes')->cascadeOnDelete();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->text('mensagem');
            $table->string('status', 20)->default('pendente');
            $table->timestamps();
        });

        Schema::create('doutores_portal_config', function (Blueprint $table) {
            $table->string('id')->primary()->default('portal');
            $table->string('titulo_hero')->nullable();
            $table->text('subtitulo_hero')->nullable();
            $table->text('texto_sobre')->nullable();
            $table->string('contato_email')->nullable();
            $table->string('contato_telefone')->nullable();
            $table->string('contato_endereco')->nullable();
            $table->timestamps();
        });

        Schema::create('doutores_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('registrado_em')->useCurrent();
            $table->string('acao');
            $table->string('categoria', 40)->default('sistema');
            $table->text('descricao')->nullable();
            $table->string('executado_por')->nullable();
            $table->string('executado_por_perfil')->nullable();
            $table->string('usuario_alvo')->nullable();
            $table->string('documento_alvo')->nullable();
            $table->string('endereco_ip')->nullable();
            $table->string('severidade', 20)->default('info');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doutores_logs');
        Schema::dropIfExists('doutores_portal_config');
        Schema::dropIfExists('doutores_comentarios_publicacao');
        Schema::dropIfExists('doutores_publicacoes');
        Schema::dropIfExists('doutores_solicitacao_estudo_logs');
        Schema::dropIfExists('doutores_solicitacoes_estudo');
        Schema::dropIfExists('doutores_aviso_leituras');
        Schema::dropIfExists('doutores_avisos');
        Schema::dropIfExists('doutores_testemunhos');
        Schema::dropIfExists('doutores_anexos');
        Schema::dropIfExists('doutores_inscricoes');
        Schema::dropIfExists('doutores_plantoes');
        Schema::dropIfExists('doutores_dominios');
        Schema::dropIfExists('doutores_coordenador_polo');
        Schema::dropIfExists('doutores_hospitais');
        Schema::dropIfExists('doutores_polos');
        Schema::dropIfExists('doutores_profiles');
    }
};
