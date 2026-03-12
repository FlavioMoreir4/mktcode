<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';

const projects = [
    {
        title: 'Omniaflow — Sistema de Gestão Educacional',
        category: 'ERP · Multi-tenant',
        description:
            'Desenvolvemos um ERP completo cobrindo secretaria (matrículas, contratos, alunos), módulo pedagógico (turmas, presenças, calendário acadêmico) e financeiro (mensalidades, PIX, boletos, conciliação automática via webhooks).',
        image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop',
        highlights: [
            'Multi-tenancy com isolamento total de dados',
            'Integração gateway Asaas: PIX e Boletos',
            'Tempo real com WebSockets (Laravel Reverb)',
            'Engenharia financeira de alta precisão',
        ],
        tags: ['Laravel 12', 'Vue 3', 'Inertia v2', 'TypeScript'],
    },
    {
        title: 'CIM — Plataforma de Gestão Corporativa',
        category: 'SaaS · Corporativo · Gestão',
        description:
            'Uma SPA corporativa com arquitetura orientada a domínio, sistema RBAC granular e mais de 50 componentes de interface acessíveis e reutilizáveis.',
        image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2015&auto=format&fit=crop',
        highlights: [
            'Arquitetura DDD: Domain-Driven Design',
            'Type-safety ponta a ponta',
            'Sistema de permissões RBAC sofisticado',
            'Biblioteca de 50+ componentes UI',
        ],
        tags: ['Tailwind v4', 'shadcn-vue', 'PHPStan', 'Rector'],
    },
];
</script>

<template>
    <Head title="Projetos" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-24">
            <div class="mx-auto max-w-7xl">
                <!-- Header -->
                <div class="mb-16 max-w-3xl space-y-4">
                    <h1 class="text-4xl font-bold tracking-tight md:text-5xl">
                        O que já construímos.
                    </h1>
                    <p class="text-lg leading-relaxed text-muted-foreground">
                        Cada projeto aqui representa um problema real que
                        precisava de solução real. Sem mockups bonitos que nunca
                        foram ao ar — tudo em produção, tudo funcionando.
                    </p>
                </div>

                <!-- Projects Grid -->
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <ProjectCard
                        v-for="project in projects"
                        :key="project.title"
                        :project="project"
                    />
                </div>

                <!-- Empty State / More projects -->
                <div
                    class="mt-20 flex flex-col items-center space-y-4 rounded-3xl border border-dashed border-border p-12 text-center"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-muted"
                    >
                        <span class="text-xl">🚀</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">
                            Seu projeto pode ser o próximo
                        </h3>
                        <p class="mx-auto max-w-sm text-muted-foreground">
                            Estamos sempre em busca de novos desafios técnicos
                            para resolver.
                        </p>
                    </div>
                    <Link
                        :href="contact().url"
                        class="rounded-full bg-foreground px-6 py-3 font-medium text-background transition-opacity hover:opacity-90"
                    >
                        Bora conversar
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
