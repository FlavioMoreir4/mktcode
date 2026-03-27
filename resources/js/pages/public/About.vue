<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';
import type { SeoData } from '@/types';

interface Props {
    seo: SeoData;
}

const props = defineProps<Props>();

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

const timeline = [
    {
        era: '2008–2012',
        label: 'As primeiras linhas',
        desc: 'Internet discada, tabelas como layout e PHP misturado com HTML na mesma linha. Foi assim que começou — na raça, sem Stack Overflow e sem ChatGPT. Cada bug era uma investigação forense.',
    },
    {
        era: '2012–2016',
        label: 'Memória afetiva',
        desc: '$.ajax, Bootstrap 2 e a sensação de que animações com easing eram o ápice do frontend. Era JavaScript espalhado em arquivo separado, sem módulo, sem build. Quem sobreviveu a esse período tem tolerância alta a caos.',
    },
    {
        era: '2016–2020',
        label: 'O caos organizado',
        desc: 'PHP sem padrão, cada framework com seu universo próprio. Foi nesse ambiente que escrever código que outra pessoa consegue ler virou obsessão — não virtude, necessidade.',
    },
    {
        era: 'Hoje',
        label: 'Stack moderno com contexto',
        desc: 'Laravel 12, Vue 3, DDD, análise estática, testes automatizados. Toda essa bagagem histórica serve para uma coisa: saber exatamente por que as decisões de hoje existem — e quais valem a pena no seu projeto.',
    },
];

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.07, rootMargin: '0px 0px -40px 0px' },
    );
    document.querySelectorAll('.reveal').forEach((el) => observer?.observe(el));
});

onUnmounted(() => observer?.disconnect());
</script>

<template>
    <SeoHead v-bind="props.seo" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-32">
            <div class="mx-auto max-w-7xl">
                <!-- ── Page header ─────────────────────────────────────── -->
                <div class="reveal mb-20 max-w-2xl">
                    <p
                        class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Sobre
                    </p>
                    <h1
                        class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                    >
                        Tecnologia<br />
                        <span class="text-muted-foreground"
                            >com propósito.</span
                        >
                    </h1>
                </div>

                <!-- ── Main grid ───────────────────────────────────────── -->
                <div
                    class="grid grid-cols-1 items-start gap-16 lg:grid-cols-12"
                >
                    <!-- Left: narrative ──────────────────────────────── -->
                    <div class="space-y-16 lg:col-span-7">
                        <!-- Origin story -->
                        <section
                            class="reveal space-y-6 text-lg leading-relaxed text-muted-foreground"
                        >
                            <p>
                                A MC — Marketing & Code nasceu de uma convicção
                                simples: a maioria dos problemas de negócio tem
                                solução técnica elegante. O que falta, quase
                                sempre, é um parceiro que entenda os dois lados
                                — o do código
                                <em
                                    class="font-medium text-foreground not-italic"
                                    >e</em
                                >
                                o do negócio.
                            </p>
                            <p>
                                Somos especialistas em desenvolvimento
                                full-stack com foco no ecossistema Laravel
                                moderno. Trabalhamos com sistemas de grau
                                enterprise: ERPs multi-tenant, plataformas
                                financeiras, monolitos modernos com arquitetura
                                Domain-Driven Design.
                            </p>
                            <p>
                                Nossa filosofia é direta: código limpo não é
                                perfeccionismo — é respeito pelo próximo
                                desenvolvedor que vai manter esse sistema, e
                                pelo cliente que vai depender dele por anos.
                            </p>
                        </section>

                        <!-- Quote block -->
                        <div
                            class="reveal group relative overflow-hidden rounded-3xl border border-primary/15 bg-primary/5 p-8 md:p-10"
                        >
                            <span
                                class="absolute top-2 right-6 text-8xl leading-none font-bold text-primary/10 transition-transform duration-500 select-none group-hover:rotate-6 group-hover:text-primary/15"
                                aria-hidden="true"
                                >"</span
                            >
                            <blockquote
                                class="relative z-10 text-2xl font-medium text-foreground"
                            >
                                "Qualquer dev entrega código. Eu entrego um
                                sistema que ainda vai funcionar quando você
                                crescer."
                            </blockquote>
                        </div>

                        <!-- Who is behind -->
                        <section class="reveal space-y-6">
                            <h2 class="text-2xl font-bold tracking-tight">
                                O cara que vai atender você.
                            </h2>
                            <div
                                class="space-y-5 text-lg leading-relaxed text-muted-foreground"
                            >
                                <p>
                                    <span class="font-semibold text-foreground"
                                        >Flávio Moreira</span
                                    >
                                    é um desenvolvedor sênior tão velho quanto o
                                    próprio PHP. Viu o surgimento do JavaScript
                                    como "aquela coisa pra fazer pop-up chato",
                                    sobreviveu à era pré-PSR de código sem
                                    convenção, tem memória afetiva com jQuery e
                                    Bootstrap —
                                    <em class="text-foreground not-italic"
                                        >e não tem vergonha nenhuma disso.</em
                                    >
                                </p>
                                <p>
                                    Essa bagagem não é nostalgia. É contexto.
                                    Quem viu o frontend evoluir de tabelas
                                    aninhadas até componentes reativos tipados
                                    entende, fundamentalmente,
                                    <em
                                        class="font-medium text-foreground not-italic"
                                        >por que</em
                                    >
                                    as decisões de hoje existem — e quais valem
                                    a pena no seu projeto.
                                </p>
                                <p>
                                    IA é ferramenta. Ferramenta nas mãos erradas
                                    gera código que parece certo e falha em
                                    produção. Décadas de julgamento é o que
                                    separa usar IA para acelerar de usá-la para
                                    terceirizar o pensamento.
                                </p>
                            </div>
                        </section>

                        <!-- Timeline -->
                        <section class="reveal space-y-6">
                            <h2 class="text-2xl font-bold tracking-tight">
                                Uma linha do tempo honesta.
                            </h2>

                            <div class="relative space-y-0">
                                <!-- Vertical line -->
                                <div
                                    class="absolute top-3 bottom-3 left-[19px] w-px bg-border"
                                    aria-hidden="true"
                                />

                                <div
                                    v-for="(item, i) in timeline"
                                    :key="item.era"
                                    class="reveal relative flex gap-6 pb-8 last:pb-0"
                                    :style="{ '--reveal-delay': `${i * 80}ms` }"
                                >
                                    <!-- Dot -->
                                    <div
                                        class="relative z-10 mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-border bg-background text-[10px] font-bold text-muted-foreground transition-colors hover:border-primary"
                                    >
                                        {{ String(i + 1).padStart(2, '0') }}
                                    </div>

                                    <div class="pt-1.5 pb-2">
                                        <div
                                            class="mb-1 flex items-center gap-3"
                                        >
                                            <span
                                                class="text-xs font-bold tracking-widest text-primary/70 uppercase"
                                                >{{ item.era }}</span
                                            >
                                            <span class="text-xs text-border"
                                                >·</span
                                            >
                                            <span
                                                class="text-xs font-semibold text-muted-foreground"
                                                >{{ item.label }}</span
                                            >
                                        </div>
                                        <p
                                            class="text-base leading-relaxed text-muted-foreground"
                                        >
                                            {{ item.desc }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Right: sticky sidebar ──────────────────────── -->
                    <div class="space-y-6 lg:sticky lg:top-28 lg:col-span-5">
                        <!-- Tech stack card -->
                        <div
                            class="reveal rounded-3xl border border-border bg-card p-8"
                        >
                            <h3 class="mb-7 text-xl font-bold tracking-tight">
                                Com o que trabalhamos.
                            </h3>

                            <div class="space-y-5">
                                <div
                                    v-for="tech in techStack"
                                    :key="tech.category"
                                    class="group space-y-1"
                                >
                                    <h4
                                        class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase transition-colors group-hover:text-primary"
                                    >
                                        {{ tech.category }}
                                    </h4>
                                    <p
                                        class="text-sm leading-relaxed font-medium text-foreground"
                                    >
                                        {{ tech.items }}
                                    </p>
                                </div>
                            </div>

                            <p
                                class="mt-7 border-t border-border pt-5 text-xs leading-relaxed text-muted-foreground italic"
                            >
                                Cada item dessa lista tem cicatriz de produção.
                                Não é hype — é o que funciona quando o sistema
                                precisa estar no ar às 3h da manhã.
                            </p>
                        </div>

                        <!-- Credibility grid — stat "5+" corrigido para "15+" -->
                        <div class="reveal grid grid-cols-2 gap-3">
                            <div
                                class="rounded-2xl border border-border bg-muted/30 p-5 text-center"
                            >
                                <p class="text-3xl font-bold">15+</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Anos de experiência
                                </p>
                            </div>
                            <div
                                class="rounded-2xl border border-border bg-muted/30 p-5 text-center"
                            >
                                <p class="text-3xl font-bold">40+</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Projetos entregues
                                </p>
                            </div>
                            <div
                                class="rounded-2xl border border-border bg-muted/30 p-5 text-center"
                            >
                                <p class="text-3xl font-bold">100%</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Laravel & Vue
                                </p>
                            </div>
                            <div
                                class="rounded-2xl border border-border bg-muted/30 p-5 text-center"
                            >
                                <p class="text-3xl font-bold">&lt;24h</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Tempo de resposta
                                </p>
                            </div>
                        </div>

                        <!-- CTA card — variante outline para não competir com o bottom CTA -->
                        <div
                            class="reveal rounded-3xl border border-border bg-card p-8"
                        >
                            <h3 class="text-xl font-bold">
                                Pronto para começar?
                            </h3>
                            <p
                                class="mt-2 text-sm leading-relaxed text-muted-foreground"
                            >
                                Vamos falar sobre como transformar seu desafio
                                técnico em um sistema sólido.
                            </p>
                            <Link
                                :href="contact().url"
                                class="mt-6 flex items-center justify-center gap-2 rounded-full border border-primary bg-transparent px-6 py-3 text-sm font-bold text-primary transition-all hover:bg-primary hover:text-primary-foreground active:scale-[0.98]"
                            >
                                Iniciar conversa
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- ── Bottom CTA ──────────────────────────────────────── -->
                <div class="reveal mt-24">
                    <div
                        class="overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14"
                    >
                        <div
                            class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between"
                        >
                            <div class="max-w-lg">
                                <p
                                    class="mb-2 text-xs font-bold tracking-widest uppercase opacity-60"
                                >
                                    Próximo passo
                                </p>
                                <h2
                                    class="text-3xl font-bold tracking-tight md:text-4xl"
                                >
                                    Sênior de verdade no seu projeto.
                                </h2>
                                <p
                                    class="mt-3 text-lg leading-relaxed opacity-80"
                                >
                                    Sem júnior entregando e sênior revisando.
                                    Você fala diretamente com quem coloca a mão
                                    no código.
                                </p>
                            </div>
                            <div
                                class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                            >
                                <Link
                                    :href="contact().url"
                                    class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                                >
                                    Bora conversar
                                    <ArrowRight class="h-4 w-4" />
                                </Link>
                                <a
                                    href="https://wa.me/5511982776725"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20"
                                >
                                    WhatsApp direto
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.reveal {
    opacity: 0;
    transform: translateY(20px);
    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
    transition-delay: var(--reveal-delay, 0ms);
}
.reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
