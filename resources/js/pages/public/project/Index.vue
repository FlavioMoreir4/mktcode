<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ArrowRight, ArrowLeft, Star, FolderOpen } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

import ProjectCard from '@/components/marketing/ProjectCard.vue';
import SeoHead from '@/components/SeoHead.vue';

import { contact } from '@/routes/public';
import type { PaginatedResponse } from '@/types';
import type { PublicProjectViewData } from '@/types/public';

const props = defineProps<{
    projects: PaginatedResponse<PublicProjectViewData>;
}>();

// ─── Featured projects (todos marcados como featured) ─────────────────────────
//
// O backend pode retornar mais de um projeto com featured: true.
// Mostramos todos numa seção de destaque em vez de descartar os extras pro grid.
//
const featuredProjects = computed(() =>
    props.projects.data.filter((p) => p.featured),
);

// ─── Restantes (não featured) ─────────────────────────────────────────────────
const rest = computed(() => props.projects.data.filter((p) => !p.featured));

// ─── Filtro ativo ─────────────────────────────────────────────────────────────
const activeStack = ref('Todos');

const filteredRest = computed(() => {
    if (activeStack.value === 'Todos') {
        return rest.value;
    }

    return rest.value.filter((p) => p.stack?.includes(activeStack.value));
});

// ─── Paginação ────────────────────────────────────────────────────────────────
const hasPrev = computed(() => !!props.projects.prev_page_url);
const hasNext = computed(() => !!props.projects.next_page_url);
const currentPage = computed(() => props.projects.current_page);
const lastPage = computed(() => props.projects.last_page);

const goTo = (url: string | null) => {
    if (!url) {
        return;
    }

    router.visit(url, { preserveScroll: false });
};

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
    <SeoHead v-bind="projects.seo" />


    <div class="px-6 pt-32 pb-32">
        <div class="mx-auto max-w-7xl">
            <!-- ════════════════════════════════════════════════════════
                     HEADER
                     Eyebrow + título + subtítulo + contador de projetos
                ════════════════════════════════════════════════════════ -->
            <div class="reveal mb-20 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="max-w-2xl">
                    <h1 class="mb-3 text-xs font-bold tracking-widest text-primary uppercase">
                        Portfólio
                    </h1>
                    <h2 class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl">
                        O que já construímos.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-muted-foreground">
                        Problemas reais, soluções reais. Tudo em produção —
                        nenhum mockup bonito que nunca saiu do Figma.
                    </p>
                </div>

                <!--
                        Counter com pill de contexto.
                        O "+0" some se total === 0 para não ficar "0+".
                    -->
                <div class="flex shrink-0 flex-col items-start gap-1 md:items-end">
                    <p class="text-5xl leading-none font-bold tabular-nums">
                        {{ projects.meta.total
                        }}<span v-if="projects.meta.total > 0" class="text-primary">+</span>
                    </p>
                    <p class="text-sm text-muted-foreground">
                        Projetos entregues
                    </p>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════════
                     FEATURED PROJECTS
                     Todos os projetos com featured: true.
                     — 1 featured  → card horizontal full-width (variant="featured")
                     — 2+ featured → coluna de cards horizontais empilhados
                ════════════════════════════════════════════════════════ -->
            <div v-if="featuredProjects.length" class="reveal mb-16 space-y-6">
                <p class="flex items-center gap-2 text-xs font-bold tracking-widest text-primary uppercase">
                    <Star class="h-3.5 w-3.5 fill-primary" />
                    Destaque
                </p>

                <div class="space-y-6">
                    <ProjectCard v-for="(project, i) in featuredProjects" :key="project.slug" :project="project"
                        variant="featured" :is-priority="i === 0" />
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════════
                     STACK FILTER
                     Só aparece se há ≥ 2 tags distintas nos projetos não-featured.
                     Cada botão mostra a contagem de projetos com aquela tag.
                ════════════════════════════════════════════════════════ -->

            <!-- ════════════════════════════════════════════════════════
                     GRID DE PROJETOS
                ════════════════════════════════════════════════════════ -->
            <div v-if="filteredRest.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="(project, i) in filteredRest" :key="project.slug" class="reveal"
                    :style="{ '--reveal-delay': `${(i % 3) * 70}ms` }">
                    <ProjectCard :project="project" />
                </div>
            </div>

            <!-- ── Empty state: filtro sem resultado ────────────────── -->
            <div v-else-if="activeStack !== 'Todos'"
                class="reveal flex flex-col items-center gap-4 rounded-2xl border border-dashed border-border py-16 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                    <FolderOpen class="h-5 w-5 text-muted-foreground" />
                </div>
                <div>
                    <p class="font-medium text-foreground">
                        Nenhum projeto com
                        <strong class="text-primary">{{
                            activeStack
                        }}</strong>
                        nesta página.
                    </p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Tente outro filtro ou navegue para a próxima página.
                    </p>
                </div>
                <button class="text-sm font-semibold text-primary hover:underline" @click="activeStack = 'Todos'">
                    Ver todos os projetos
                </button>
            </div>

            <!-- ── Empty state: sem projetos na página (raro) ─────── -->
            <div v-else-if="!featuredProjects.length && !rest.length"
                class="reveal flex flex-col items-center gap-4 py-20 text-center">
                <p class="text-muted-foreground">
                    Novos projetos chegando em breve.
                </p>
                <Link :href="contact().url" class="text-sm font-semibold text-primary hover:underline">
                    Fale sobre o seu projeto
                </Link>
            </div>

            <!-- ════════════════════════════════════════════════════════
                     PAGINAÇÃO
                     Só renderiza se há mais de uma página.
                     Desabilitado enquanto filtro local está ativo
                     (filtro só age na página atual).
                ════════════════════════════════════════════════════════ -->
            <div v-if="lastPage > 1" class="reveal mt-16 flex items-center justify-between gap-4">
                <button :disabled="!hasPrev"
                    class="flex items-center gap-2 rounded-full border border-border px-5 py-2.5 text-sm font-semibold transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                    @click="goTo(projects.prev_page_url)">
                    <ArrowLeft class="h-4 w-4" />
                    Anterior
                </button>

                <span class="text-sm text-muted-foreground">
                    Página
                    <strong class="text-foreground">{{
                        currentPage
                    }}</strong>
                    de
                    <strong class="text-foreground">{{ lastPage }}</strong>
                </span>

                <button :disabled="!hasNext"
                    class="flex items-center gap-2 rounded-full border border-border px-5 py-2.5 text-sm font-semibold transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                    @click="goTo(projects.next_page_url)">
                    Próxima
                    <ArrowRight class="h-4 w-4" />
                </button>
            </div>

            <!-- ════════════════════════════════════════════════════════
                     CTA FINAL
                ════════════════════════════════════════════════════════ -->
            <div class="reveal mt-24">
                <div class="overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14">
                    <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-lg">
                            <p class="mb-2 text-xs font-bold tracking-widest uppercase opacity-60">
                                Próximo projeto
                            </p>
                            <h2 class="text-3xl font-bold tracking-tight md:text-4xl">
                                O seu pode ser o próximo.
                            </h2>
                            <p class="mt-3 text-lg leading-relaxed opacity-80">
                                Estamos sempre em busca de novos desafios
                                técnicos. Conta o que você tem em mente.
                            </p>
                        </div>
                        <div class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end">
                            <Link :href="contact().url"
                                class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]">
                                Bora conversar
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                            <a href="https://wa.me/5511982776725" target="_blank" rel="noopener noreferrer"
                                class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20">
                                WhatsApp direto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
