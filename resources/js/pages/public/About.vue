<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';

const techStack = [
    {
        category: 'Backend',
        items: 'Laravel 12 · PHP 8.2+ · API RESTful · WebSockets',
    },
    {
        category: 'Frontend',
        items: 'Vue 3 · Inertia.js v2 · TypeScript · Tailwind CSS',
    },
    { category: 'Banco de Dados', items: 'MySQL · Redis · PostgreSQL' },
    {
        category: 'Infraestrutura',
        items: 'VPS · Docker · GitHub Actions · Cloudflare · Google Cloud',
    },
    {
        category: 'Qualidade',
        items: 'PHPStan · Rector · Pest · Laravel Pint · ESLint',
    },
];
</script>

<template>
    <Head title="Sobre" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-24">
            <div class="mx-auto max-w-7xl">
                <div
                    class="grid grid-cols-1 items-start gap-16 lg:grid-cols-12"
                >
                    <!-- Text Content -->
                    <div class="space-y-12 lg:col-span-7">
                        <section class="space-y-6">
                            <h1
                                class="text-4xl font-bold tracking-tight md:text-5xl"
                            >
                                Tecnologia com propósito.
                            </h1>
                            <div
                                class="space-y-6 text-lg leading-relaxed text-muted-foreground"
                            >
                                <p>
                                    A MC — Marketing & Code nasceu de uma
                                    convicção simples: a maioria dos problemas
                                    de negócio tem solução técnica elegante. O
                                    que falta, na maior parte das vezes, é um
                                    parceiro que entenda os dois lados — o do
                                    código e o do negócio.
                                </p>
                                <p>
                                    Somos especialistas em desenvolvimento
                                    full-stack com foco no ecossistema Laravel
                                    moderno. Trabalhamos com sistemas de grau
                                    enterprise: ERPs multi-tenant, plataformas
                                    financeiras, monolitos modernos com
                                    arquitetura Domain-Driven Design (DDD).
                                </p>
                                <p>
                                    Nossa filosofia é direta: código limpo não é
                                    perfeccionismo — é respeito pelo próximo
                                    desenvolvedor que vai manter esse sistema, e
                                    pelo cliente que vai depender dele por anos.
                                </p>
                                <p>
                                    Usamos as melhores práticas disponíveis:
                                    análise estática, testes automatizados,
                                    separação de responsabilidades e
                                    documentação. Não como burocracia — como
                                    garantia de que o que entregamos vai
                                    funcionar daqui a dois, cinco, dez anos.
                                </p>
                            </div>
                        </section>

                        <div
                            class="group relative overflow-hidden rounded-3xl border border-primary/10 bg-primary/5 p-8 md:p-10"
                        >
                            <div
                                class="absolute top-0 right-0 rotate-12 p-4 text-6xl opacity-10 transition-transform duration-500 group-hover:rotate-0"
                            >
                                "
                            </div>
                            <blockquote
                                class="relative z-10 text-2xl font-medium text-foreground"
                            >
                                "Não entregamos software. Entregamos sistemas
                                que o seu negócio pode confiar."
                            </blockquote>
                        </div>
                    </div>

                    <!-- Tech Stack Sidebar -->
                    <div class="sticky top-32 space-y-8 lg:col-span-5">
                        <div
                            class="space-y-8 rounded-3xl border border-border bg-card p-8"
                        >
                            <h3 class="text-xl font-bold tracking-tight">
                                Com o que trabalhamos.
                            </h3>

                            <div class="space-y-6">
                                <div
                                    v-for="tech in techStack"
                                    :key="tech.category"
                                    class="group space-y-2"
                                >
                                    <h4
                                        class="text-xs font-bold tracking-wider text-muted-foreground uppercase transition-colors duration-300 group-hover:text-primary"
                                    >
                                        {{ tech.category }}
                                    </h4>
                                    <p class="font-medium text-foreground">
                                        {{ tech.items }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="mt-8 space-y-4 border-t border-border pt-6"
                            >
                                <p
                                    class="text-xs leading-relaxed text-muted-foreground italic"
                                >
                                    Não usamos tecnologia velha só porque é
                                    "confortável". Usamos o que resolve o seu
                                    problema com eficiência e longevidade.
                                </p>
                            </div>
                        </div>

                        <!-- CTA Card -->
                        <div
                            class="space-y-4 rounded-3xl bg-foreground p-8 text-background shadow-xl shadow-foreground/5"
                        >
                            <h3 class="text-xl font-bold">
                                Pronto para começar?
                            </h3>
                            <p
                                class="text-sm leading-relaxed text-background/70"
                            >
                                Vamos falar sobre como transformar seu desafio
                                técnico em um sistema sólido.
                            </p>
                            <Link
                                :href="contact().url"
                                class="inline-flex w-full items-center justify-center rounded-full bg-primary py-3 font-bold text-primary-foreground transition-all hover:scale-[1.02] active:scale-[0.98]"
                            >
                                Iniciar conversa
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
