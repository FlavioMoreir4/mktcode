<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Sistemas Web & ERPs Customizados',
                'description' => 'Para empresas que cresceram além das ferramentas prontas. Desenvolvemos sistemas sob medida que se adaptam ao seu processo — não o contrário.',
                'icon' => 'layout',
                'features' => [
                    'Levantamento de requisitos e modelagem do negócio',
                    'Arquitetura pensada para escalar com você',
                    'Módulos customizados: financeiro, operacional, etc.',
                    'Integrações com APIs externas (gateways, legados)',
                    'Painel administrativo completo com controle de acesso',
                    'Documentação e treinamento da equipe',
                ],
                'ideal_for' => 'Redes com múltiplas unidades, operações financeiras complexas, empresas que precisam substituir planilhas ou sistemas legados.',
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Plataformas Digitais & SaaS',
                'description' => 'Para quem quer transformar um processo em produto. Construímos do MVP validado até a plataforma escalável com múltiplos clientes.',
                'icon' => 'layers',
                'features' => [
                    'Definição de escopo e arquitetura do produto',
                    'MVP funcional com foco em validação rápida',
                    'Multi-tenancy para múltiplos clientes (B2B)',
                    'Sistema de assinaturas e pagamentos recorrentes',
                    'Dashboard analítico e relatórios',
                    'Escalabilidade planejada desde o início',
                ],
                'ideal_for' => 'Empreendedores com ideia de SaaS, empresas que querem criar um produto digital baseado em seu know-how.',
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Sites Institucionais & Landing Pages',
                'description' => 'Um site bonito que não aparece no Google ou não gera contato não serve para nada. Desenvolvemos com foco em performance, SEO e conversão.',
                'icon' => 'monitor',
                'features' => [
                    'Design moderno e responsivo (mobile-first)',
                    'Performance otimizada (Core Web Vitals)',
                    'SEO técnico configurado desde o início',
                    'Integração com CRM, WhatsApp e Analytics',
                    'Painel para edição de conteúdo (CMS)',
                    'Hospedagem configurada e otimizada',
                ],
                'ideal_for' => 'Empresas sem presença digital, rebranding, campanhas de lançamento, páginas de captação de leads.',
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Consultoria & Arquitetura de Software',
                'description' => 'Sistemas mal construídos custam caro. Ajudamos a entender o que está errado e como consertar sua base de código ou plano de expansão.',
                'icon' => 'search-code',
                'features' => [
                    'Auditoria de código e arquitetura',
                    'Identificação de gargalos de performance e segurança',
                    'Plano de modernização com prioridades',
                    'Definição de stack e padrões para times',
                    'Acompanhamento da execução técnica',
                ],
                'ideal_for' => 'CTOs que herdaram um sistema problemático, startups que precisam escalar base legada, avaliação de modernização.',
                'active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
