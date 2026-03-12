<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Layout,
    Layers,
    Monitor,
    SearchCode,
    CheckCircle2,
} from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';

const services = [
    {
        title: 'Sistemas Web & ERPs Customizados',
        description:
            'Para empresas que cresceram além das ferramentas prontas. Desenvolvemos sistemas sob medida que se adaptam ao seu processo — não o contrário.',
        icon: Layout,
        features: [
            'Levantamento de requisitos e modelagem do negócio',
            'Arquitetura pensada para escalar com você',
            'Módulos customizados: financeiro, operacional, etc.',
            'Integrações com APIs externas (gateways, legados)',
            'Painel administrativo completo com controle de acesso',
            'Documentação e treinamento da equipe',
        ],
        idealFor:
            'Redes com múltiplas unidades, operações financeiras complexas, empresas que precisam substituir planilhas ou sistemas legados.',
    },
    {
        title: 'Plataformas Digitais & SaaS',
        description:
            'Para quem quer transformar um processo em produto. Construímos do MVP validado até a plataforma escalável com múltiplos clientes.',
        icon: Layers,
        features: [
            'Definição de escopo e arquitetura do produto',
            'MVP funcional com foco em validação rápida',
            'Multi-tenancy para múltiplos clientes (B2B)',
            'Sistema de assinaturas e pagamentos recorrentes',
            'Dashboard analítico e relatórios',
            'Escalabilidade planejada desde o início',
        ],
        idealFor:
            'Empreendedores com ideia de SaaS, empresas que querem criar um produto digital baseado em seu know-how.',
    },
    {
        title: 'Sites Institucionais & Landing Pages',
        description:
            'Um site bonito que não aparece no Google ou não gera contato não serve para nada. Desenvolvemos com foco em performance, SEO e conversão.',
        icon: Monitor,
        features: [
            'Design moderno e responsivo (mobile-first)',
            'Performance otimizada (Core Web Vitals)',
            'SEO técnico configurado desde o início',
            'Integração com CRM, WhatsApp e Analytics',
            'Painel para edição de conteúdo (CMS)',
            'Hospedagem configurada e otimizada',
        ],
        idealFor:
            'Empresas sem presença digital, rebranding, campanhas de lançamento, páginas de captação de leads.',
    },
    {
        title: 'Consultoria & Arquitetura de Software',
        description:
            'Sistemas mal construídos custam caro. Ajudamos a entender o que está errado e como consertar sua base de código ou plano de expansão.',
        icon: SearchCode,
        features: [
            'Auditoria de código e arquitetura',
            'Identificação de gargalos de performance e segurança',
            'Plano de modernização com prioridades',
            'Definição de stack e padrões para times',
            'Acompanhamento da execução técnica',
        ],
        idealFor:
            'CTOs que herdaram um sistema problemático, startups que precisam escalar base legada, avaliação de modernização.',
    },
];
</script>

<template>
    <Head title="Serviços" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-24">
            <div class="mx-auto max-w-7xl">
                <!-- Header -->
                <div class="mb-20 max-w-3xl space-y-4">
                    <h1 class="text-4xl font-bold tracking-tight md:text-5xl">
                        O que fazemos — e como fazemos.
                    </h1>
                    <p class="text-lg leading-relaxed text-muted-foreground">
                        Cada serviço aqui tem uma forma de trabalho pensada para
                        entregar valor real, no prazo combinado, com comunicação
                        transparente do início ao fim.
                    </p>
                </div>

                <!-- Services List -->
                <div class="space-y-24">
                    <section
                        v-for="(service, index) in services"
                        :key="service.title"
                        class="grid grid-cols-1 items-start gap-12 lg:grid-cols-2"
                    >
                        <div
                            class="space-y-6"
                            :class="index % 2 === 1 ? 'lg:order-last' : ''"
                        >
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary"
                            >
                                <component :is="service.icon" class="h-7 w-7" />
                            </div>
                            <h2 class="text-3xl font-bold tracking-tight">
                                {{ service.title }}
                            </h2>
                            <p
                                class="text-lg leading-relaxed text-muted-foreground"
                            >
                                {{ service.description }}
                            </p>

                            <div
                                class="rounded-2xl border border-border/50 bg-muted/50 p-6 pt-4"
                            >
                                <h4
                                    class="mb-3 text-sm font-bold tracking-wider text-muted-foreground uppercase"
                                >
                                    Ideal para:
                                </h4>
                                <p
                                    class="text-sm leading-relaxed text-foreground"
                                >
                                    {{ service.idealFor }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="space-y-8 rounded-3xl border border-border bg-card p-8 md:p-10"
                        >
                            <h3 class="text-xl font-bold">
                                O que está incluso:
                            </h3>
                            <ul class="space-y-4">
                                <li
                                    v-for="feature in service.features"
                                    :key="feature"
                                    class="group flex items-start gap-3"
                                >
                                    <div
                                        class="mt-1 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-primary/5 text-primary transition-colors duration-300 group-hover:bg-primary group-hover:text-primary-foreground"
                                    >
                                        <CheckCircle2 class="h-3.5 w-3.5" />
                                    </div>
                                    <span
                                        class="text-muted-foreground transition-colors duration-300 group-hover:text-foreground"
                                        >{{ feature }}</span
                                    >
                                </li>
                            </ul>

                            <div class="mt-8 border-t border-border pt-6">
                                <Link
                                    :href="contact().url"
                                    class="inline-flex items-center gap-2 font-bold text-primary underline decoration-primary/20 underline-offset-4 transition-all hover:gap-3"
                                >
                                    Fazer orçamento para este serviço →
                                </Link>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
