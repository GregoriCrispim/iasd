<?php

namespace Database\Seeders;

use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Polo;
use App\Models\Doutores\PortalConfig;
use Illuminate\Database\Seeder;

/**
 * Dados referenciais do DoutoresApp que são institucionais (não personas fictícias).
 * Não inclui usuários/CPFs/e-mails de demonstração.
 */
class DoutoresReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedDominios();
        $this->seedPolosEHospitais();
        $this->seedPortal();
    }

    private function seedDominios(): void
    {
        $map = [
            'funcao' => [
                'Música',
                'Intercessão',
                'Clown',
                'Staff',
                'Coordenador de equipes',
                'Comunicação',
            ],
            'igreja' => [
                'Igreja Central',
                'Igreja Asa Norte',
                'Igreja Asa Sul',
                'Igreja Taguatinga',
                'Igreja Águas Claras',
                'Igreja Ceilândia',
            ],
            'dia_preferido' => [
                'Segunda',
                'Terça',
                'Quarta',
                'Quinta',
                'Sexta',
                'Sábado',
                'Domingo',
                'Qualquer dia',
            ],
            'turno_preferido' => [
                'Manhã',
                'Tarde',
                'Noite',
            ],
        ];

        foreach ($map as $categoria => $itens) {
            foreach ($itens as $descricao) {
                Dominio::query()->firstOrCreate(
                    ['categoria' => $categoria, 'descricao' => $descricao],
                    ['ativo' => true],
                );
            }
        }
    }

    private function seedPolosEHospitais(): void
    {
        // Hospitais e regiões reais de Brasília; contato institucional da página pública do projeto.
        $contato = 'doutoresdeesperancabsb@gmail.com';
        $telefone = '(61) 99113-2590';

        $estrutura = [
            [
                'polo' => [
                    'nome' => 'Polo Brasília - Asa Sul',
                    'endereco' => 'SGAS 915',
                    'cidade' => 'Brasília',
                    'estado' => 'DF',
                    'telefone' => $telefone,
                    'email' => $contato,
                    'status' => 'ativo',
                ],
                'hospitais' => [
                    ['nome' => 'Hospital Santa Lúcia Sul', 'endereco' => 'SHLS 716', 'cidade' => 'Brasília'],
                    ['nome' => 'Hospital Santa Lúcia Norte', 'endereco' => 'SHLN 716', 'cidade' => 'Brasília'],
                    ['nome' => 'Hospital Brasília', 'endereco' => 'SGAS 613', 'cidade' => 'Brasília'],
                ],
            ],
            [
                'polo' => [
                    'nome' => 'Polo Taguatinga',
                    'endereco' => 'Taguatinga Norte',
                    'cidade' => 'Taguatinga',
                    'estado' => 'DF',
                    'telefone' => $telefone,
                    'email' => $contato,
                    'status' => 'ativo',
                ],
                'hospitais' => [
                    ['nome' => 'Hospital de Base do Distrito Federal', 'endereco' => 'SMHS Quadra 101', 'cidade' => 'Brasília'],
                    ['nome' => 'Hospital Anchieta', 'endereco' => 'QNG Área Especial 02', 'cidade' => 'Taguatinga'],
                ],
            ],
            [
                'polo' => [
                    'nome' => 'Polo Águas Claras',
                    'endereco' => 'Águas Claras',
                    'cidade' => 'Águas Claras',
                    'estado' => 'DF',
                    'telefone' => $telefone,
                    'email' => $contato,
                    'status' => 'ativo',
                ],
                'hospitais' => [],
            ],
        ];

        foreach ($estrutura as $bloco) {
            $polo = Polo::query()->updateOrCreate(
                ['nome' => $bloco['polo']['nome']],
                $bloco['polo'] + ['quantidade_voluntarios' => 0, 'coordenador' => null],
            );

            foreach ($bloco['hospitais'] as $hospital) {
                Hospital::query()->updateOrCreate(
                    ['nome' => $hospital['nome']],
                    [
                        'polo_id' => $polo->id,
                        'endereco' => $hospital['endereco'],
                        'cidade' => $hospital['cidade'],
                        'uf' => 'DF',
                        'status' => 'ativo',
                    ],
                );
            }
        }
    }

    private function seedPortal(): void
    {
        PortalConfig::query()->updateOrCreate(
            ['id' => 'portal'],
            [
                'titulo_hero' => 'Levando esperança a quem mais precisa',
                'subtitulo_hero' => 'Somos uma rede de voluntários que leva alegria, acolhimento e cuidado humano a pacientes e famílias em hospitais.',
                'texto_sobre' => 'O Ministério Doutores de Esperança é um projeto de humanização hospitalar cristão apoiado pela ASA da Igreja Adventista Central de Brasília. Por meio do voluntariado, levamos riso, música, fé e presença a quem enfrenta momentos difíceis.',
                'contato_email' => 'doutoresdeesperancabsb@gmail.com',
                'contato_telefone' => '(61) 99113-2590',
                'contato_endereco' => 'Brasília · Taguatinga · Águas Claras',
            ],
        );
    }
}
