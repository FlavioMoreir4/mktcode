<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Content\Enums\PageStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Política de Privacidade (LGPD) com menção aos serviços da empresa:
     * Google (Search, Analytics, Ads, Fonts, CAPTCHA, Play/Apps),
     * Meta (Pixel, Instagram, Facebook), e tecnologias de tracking.
     */
    public function run(): void
    {
        $body = $this->document([
            $this->heading('1. Quem somos', 2),
            $this->paragraph('Esta Política de Privacidade descreve como a MC — Marketing & Code ("nós", "nosso") coleta, utiliza, armazena e protege os dados pessoais dos usuários ("você") em nosso site e nos serviços digitais que operamos. Estamos em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 — LGPD).'),
            $this->paragraph('Se tiver dúvidas, fale conosco pelo e-mail flavio.moreira@mktcode.digital ou pelos canais oficiais listados no rodapé do site.'),

            $this->heading('2. Dados que coletamos', 2),
            $this->paragraph('Coletamos apenas os dados necessários para operar e melhorar nossos serviços:'),
            $this->bulletList([
                'Dados de identificação: nome, e-mail e telefone/WhatsApp quando você nos contata pelo formulário de orçamento.',
                'Dados de navegação: endereço IP, páginas acessadas, tempo de sessão e dispositivo, reunidos por meio de cookies e tecnologias de tracking.',
                'Dados de mensagens: o conteúdo das mensagens enviadas via formulário de contato, Telegram ou WhatsApp.',
                'Dados técnicos: logs de erro, identificadores de sessão e relatórios (performance) para estabilidade.',
            ]),

            $this->heading('3. Como usamos seus dados', 2),
            $this->paragraph('Utilizamos os dados para: responder solicitações e orçamentos; melhorar conteúdo e usabilidade; mensurar o desempenho das campanhas; prevenir fraudes e abusos; e cumprir obrigações legais.'),

            $this->heading('4. Serviços de terceiros e transferência internacional', 2),
            $this->paragraph('Para operar o site e nossos produtos, utilizamos serviços de terceiros. Alguns processam dados em servidores fora do Brasil, sujeitos a suas próprias políticas:'),
            $this->bulletList([
                'Google: usamos Google Search, Google Analytics (medição de tráfego), Google Ads (anúncios), Google Fonts (tipografia), Google reCAPTCHA (proteção contra bots) e a publicação de apps e conteúdos em plataformas Google. A Google processa dados conforme a Google Privacy Policy.',
                'Meta: utilizamos Meta Pixel, Instagram e Facebook para medição de conversão e divulgação. A Meta processa dados conforme a Meta Privacy Policy.',
                'Tecnologias de tracking: empregamos pixels, tags e cookies de mensuração (incluindo eventos de tracking de conversão) para entender a origem das visitas e otimizar campanhas.',
                'Cloudflare: distribuição de conteúdo (CDN) e proteção de rede.',
            ]),
            $this->paragraph('Esses serviços podem associar sua navegação a perfis próprios. Recomendamos revisar as políticas de privacidade de cada provedor.'),

            $this->heading('5. Cookies e controle', 2),
            $this->paragraph('Usamos cookies essenciais (necessários para o funcionamento) e cookies de analytics/publicidade (com seu consentimento quando exigido). Você pode gerenciar preferências no seu navegador ou via ferramentas de opt-out dos provedores (Google Ads Settings, Meta Pixel opt-out).'),

            $this->heading('6. Seus direitos (LGPD)', 2),
            $this->paragraph('Você pode, a qualquer momento e gratuitamente: confirmar a existência de tratamento; acessar seus dados; corrigir dados incompletos; anonimizar, bloquear ou eliminar dados desnecessários; revogar o consentimento; e solicitar a portabilidade. Para exercer esses direitos, escreva para hello@mktcode.digital.'),

            $this->heading('7. Retenção e segurança', 2),
            $this->paragraph('Mantemos os dados pelo tempo necessário às finalidades ou à obrigação legal, com medidas técnicas e organizacionais de segurança. Mensagens de contato são processadas e notificadas via Telegram/WhatsApp para atendimento.'),

            $this->heading('8. Alterações', 2),
            $this->paragraph('Esta política pode ser atualizada. A versão vigente estará sempre disponível nesta página, com a data de atualização.'),
        ]);

        Page::query()->updateOrCreate(
            ['slug' => 'politica-de-privacidade'],
            [
                'title' => 'Política de Privacidade',
                'excerpt' => 'Como a MC — Marketing & Code coleta, usa e protege seus dados, em conformidade com a LGPD, Google, Meta e tecnologias de tracking.',
                'body' => $body,
                'status' => PageStatus::Published,
                'published_at' => now(),
                'seo_title' => 'Política de Privacidade — MC Marketing & Code',
                'seo_description' => 'Política de Privacidade da MC Marketing & Code: LGPD, Google (Analytics, Ads, Fonts, reCAPTCHA), Meta (Pixel, Instagram) e tecnologias de tracking.',
            ],
        );

        $this->seedTermsOfService();
        $this->seedCookiePolicy();
    }

    /**
     * Termos de Serviço — relação contratual, uso do site, responsabilidades,
     * propriedade intelectual e plataformas de terceiros (Google, Meta, etc).
     */
    private function seedTermsOfService(): void
    {
        $body = $this->document([
            $this->heading('1. Aceitação dos termos', 2),
            $this->paragraph('Ao acessar ou utilizar o site e os serviços da MC — Marketing & Code ("Serviços"), você declara que leu, compreendeu e concorda com estes Termos de Serviço. Se não concordar, não utilize os Serviços.'),

            $this->heading('2. Objeto dos Serviços', 2),
            $this->paragraph('Oferecemos desenvolvimento de sistemas web, aplicativos, portfólio técnico e conteúdo educativo. Os Serviços são fornecidos "no estado em que se encontram", respeitadas as condições descritas neste documento.'),

            $this->heading('3. Uso adequado', 2),
            $this->paragraph('Você se compromete a utilizar os Serviços de forma lícita e a não:'),
            $this->bulletList([
                'Praticar atos ilícitos ou que violem direitos de terceiros;',
                'Tentar comprometer a segurança, estabilidade ou disponibilidade da infraestrutura;',
                'Reproduzir, copiar ou revender conteúdos sem autorização expressa;',
                'Utilizar robôs, scrapers ou automação que sobrecarreguem o site.',
            ]),

            $this->heading('4. Propriedade intelectual', 2),
            $this->paragraph('Todo o conteúdo próprio (textos, código, marca, identidade visual) pertence à MC — Marketing & Code ou a seus licenciadores. Licenças de código aberto seguem seus respectivos termos. Produtos entregues a clientes transferem direitos conforme contrato específico.'),

            $this->heading('5. Serviços e plataformas de terceiros', 2),
            $this->paragraph('Nossos Serviços integram ou dependem de plataformas de terceiros, cujos termos também se aplicam ao uso correlato:'),
            $this->bulletList([
                'Google: poderão ser utilizados Google Search, Google Analytics, Google Ads, Google Fonts, Google reCAPTCHA e a publicação de apps/conteúdos em propriedades Google. Aplicam-se os Google Terms of Service e a Google Privacy Policy.',
                'Meta: poderão ser utilizados Meta Pixel, Instagram e Facebook para divulgação e mensuração. Aplicam-se os Meta Terms e a Meta Privacy Policy.',
                'Cloudflare: distribuição de conteúdo (CDN) e proteção de rede.',
                'Tecnologias de tracking: pixels e tags de mensuração de conversão podem ser ativados conforme a Política de Privacidade.',
            ]),
            $this->paragraph('Não nos responsabilizamos por práticas de terceiros, recomendando a leitura de seus termos e políticas.'),

            $this->heading('6. Limitação de responsabilidade', 2),
            $this->paragraph('Os Serviços são fornecidos sem garantias de resultado específico. Na máxima extensão permitida por lei, a MC — Marketing & Code não se responsabiliza por danos indiretos, lucros cessantes ou prejuízos decorrentes do uso ou impossibilidade de uso.'),

            $this->heading('7. Orçamentos e contratos', 2),
            $this->paragraph('Solicitações de orçamento via formulário não constituem proposta firme. Cada projeto é regido por proposta e contrato específicos, que prevalecem sobre eventuais divergências com estes Termos.'),

            $this->heading('8. Privacidade', 2),
            $this->paragraph('O tratamento de dados pessoais segue nossa Política de Privacidade, disponível neste site.'),

            $this->heading('9. Alterações e foro', 2),
            $this->paragraph('Estes Termos podem ser atualizados a qualquer momento, com a versão vigente nesta página. Fica eleito o foro da comarca da sede da empresa para questões não resolvidas amigavelmente, respeitada a legislação brasileira.'),
        ]);

        Page::query()->updateOrCreate(
            ['slug' => 'termos-de-servico'],
            [
                'title' => 'Termos de Serviço',
                'excerpt' => 'Condições de uso do site e dos serviços da MC Marketing & Code, incluindo plataformas Google, Meta e tecnologias de terceiros.',
                'body' => $body,
                'status' => PageStatus::Published,
                'published_at' => now(),
                'seo_title' => 'Termos de Serviço — MC Marketing & Code',
                'seo_description' => 'Termos de Serviço da MC Marketing & Code: uso do site, propriedade intelectual, Google, Meta, limitação de responsabilidade e foro.',
            ],
        );
    }

    /**
     * Política de Cookies — categorias, finalidades e como gerenciar o consentimento.
     */
    private function seedCookiePolicy(): void
    {
        $body = $this->document([
            $this->heading('O que são cookies', 2),
            $this->paragraph('Cookies são pequenos arquivos de texto armazenados no seu navegador para que o site reconheça seu dispositivo e lembre preferências. Utilizamos cookies próprios e de terceiros (Google, Meta e tecnologias de tracking).'),

            $this->heading('Categorias que utilizamos', 2),
            $this->bulletList([
                'Essenciais: necessários para navegação, segurança (Google reCAPTCHA) e formulários. Sempre ativos.',
                'Analytics: Google Analytics mede tráfego e comportamento para melhorar o conteúdo (tracking de sessão).',
                'Marketing: Google Ads e Meta Pixel mensuram conversão e personalizam anúncios com base em suas visitas.',
            ]),

            $this->heading('Como gerenciar o consentimento', 2),
            $this->paragraph('Ao acessar o site, exibimos um banner de consentimento com opções de Aceitar todos, Rejeitar ou Personalizar. Sua escolha é salva localmente (localStorage) e pode ser alterada a qualquer momento limpando os dados do site no navegador.'),

            $this->heading('Cookies de terceiros', 2),
            $this->paragraph('Google e Meta podem associar sua navegação a perfis próprios conforme suas respectivas políticas (Google Privacy & Terms, Meta Privacy Policy). O uso de pixels e tags de tracking ocorre apenas após consentimento de analytics/marketing.'),

            $this->heading('Mais informações', 2),
            $this->paragraph('Para detalhes sobre tratamento de dados pessoais, consulte nossa Política de Privacidade e nossos Termos de Serviço, disponíveis neste site.'),
        ]);

        Page::query()->updateOrCreate(
            ['slug' => 'politica-de-cookies'],
            [
                'title' => 'Política de Cookies',
                'excerpt' => 'Como usamos cookies e tecnologias de tracking (Google, Meta) e como gerenciar seu consentimento.',
                'body' => $body,
                'status' => PageStatus::Published,
                'published_at' => now(),
                'seo_title' => 'Política de Cookies — MC Marketing & Code',
                'seo_description' => 'Política de Cookies da MC Marketing & Code: essenciais, analytics (Google) e marketing (Meta), e gestão de consentimento.',
            ],
        );
    }

    /**
     * Generates a Tiptap document node from an array of block nodes.
     *
     * @param  array<int, mixed>  $nodes
     */
    private function document(array $nodes): array
    {
        return [
            'type' => 'doc',
            'content' => $nodes,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function heading(string $text, int $level): array
    {
        return [
            'type' => 'heading',
            'attrs' => ['level' => $level],
            'content' => [['type' => 'text', 'text' => $text]],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function paragraph(string $text): array
    {
        return [
            'type' => 'paragraph',
            'content' => [['type' => 'text', 'text' => $text]],
        ];
    }

    /**
     * @param  string[]  $items
     * @return array<string, mixed>
     */
    private function bulletList(array $items): array
    {
        return [
            'type' => 'bulletList',
            'content' => array_map(function (string $item): array {
                return [
                    'type' => 'listItem',
                    'content' => [
                        [
                            'type' => 'paragraph',
                            'content' => [['type' => 'text', 'text' => $item]],
                        ],
                    ],
                ];
            }, $items),
        ];
    }
}
