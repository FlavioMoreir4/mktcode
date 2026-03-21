<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ArrowRight, ArrowLeft, Star } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';
import type { PaginatedResponse, Project } from '@/types';

const props = defineProps<{
    projects: PaginatedResponse<Project>;
}>();

// ─── Featured project (first featured, or first overall) ─────────────────────
const featured = computed(
    () =>
        props.projects.data.find((p) => p.featured) ??
        props.projects.data[0] ??
        null,
);

// ─── Rest (non-featured) ──────────────────────────────────────────────────────
const rest = computed(() =>
    props.projects.data.filter((p) => p !== featured.value),
);

// ─── All unique stack tags across current page ────────────────────────────────
// const allStacks = computed(() => {
//     const set = new Set<string>();
//     props.projects.data.forEach((p) => p.stack?.forEach((s) => set.add(s)));

//     return ['Todos', ...Array.from(set).sort()];
// });

// ─── Client-side stack filter ─────────────────────────────────────────────────
const activeStack = ref('Todos');

const filteredRest = computed(() => {
    if (activeStack.value === 'Todos') {
        return rest.value;
    }

    return rest.value.filter((p) => p.stack?.includes(activeStack.value));
});

// ─── Tag name helper ──────────────────────────────────────────────────────────
// const tagName = (tag: any): string => {
//     if (typeof tag.name === 'string') return tag.name;
//     return tag.name?.en ?? tag.name?.pt ?? Object.values(tag.name)[0] ?? '';
// };

// ─── Pagination helpers ───────────────────────────────────────────────────────
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

    <PublicLayout>
        <div class="px-6 pt-32 pb-32">
            <div class="mx-auto max-w-7xl">
                <!-- ── Header ──────────────────────────────────────────── -->
                <div
                    class="reveal mb-20 flex flex-col gap-6 md:flex-row md:items-end md:justify-between"
                >
                    <div class="max-w-2xl">
                        <p
                            class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                        >
                            Portfólio
                        </p>
                        <h1
                            class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                        >
                            O que já construímos.
                        </h1>
                        <p
                            class="mt-5 text-lg leading-relaxed text-muted-foreground"
                        >
                            Problemas reais, soluções reais. Tudo em produção —
                            nenhum mockup bonito que nunca saiu do Figma.
                        </p>
                    </div>

                    <!-- Counter -->
                    <div class="shrink-0 text-right">
                        <p class="text-5xl font-bold tabular-nums">
                            {{ projects.total
                            }}<span class="text-primary">+</span>
                        </p>
                        <p class="text-sm text-muted-foreground">
                            projetos entregues
                        </p>
                    </div>
                </div>

                <!-- ── Featured Project ────────────────────────────────── -->
                <div v-if="featured" class="reveal mb-16">
                    <p
                        class="mb-4 flex items-center gap-2 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        <Star class="h-3.5 w-3.5 fill-primary" />
                        Destaque
                    </p>

                    <ProjectCard :project="featured" variant="featured" />
                </div>

                <!-- ── Stack filter ────────────────────────────────────── -->
                <!-- <div
                    v-if="allStacks.length > 2"
                    class="reveal mb-10 flex flex-wrap gap-2"
                    role="group"
                    aria-label="Filtrar por tecnologia"
                >
                    <button
                        v-for="stack in allStacks"
                        :key="stack"
                        class="rounded-full border px-4 py-1.5 text-sm font-medium transition-all"
                        :class="
                            activeStack === stack
                                ? 'border-primary bg-primary/10 text-primary'
                                : 'border-border bg-background text-muted-foreground hover:border-border/80 hover:text-foreground'
                        "
                        @click="activeStack = stack"
                    >
                        {{ stack }}
                    </button>
                </div> -->

                <!-- ── Projects grid ───────────────────────────────────── -->
                <div
                    v-if="filteredRest.length"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="(project, i) in filteredRest"
                        :key="project.id"
                        class="reveal"
                        :style="{ '--reveal-delay': `${(i % 3) * 70}ms` }"
                    >
                        <ProjectCard :project="project" />
                    </div>
                </div>

                <!-- Empty filter state -->
                <div
                    v-else-if="activeStack !== 'Todos'"
                    class="reveal flex flex-col items-center gap-3 rounded-2xl border border-dashed border-border py-16 text-center"
                >
                    <p class="text-muted-foreground">
                        Nenhum projeto com
                        <strong>{{ activeStack }}</strong> nesta página.
                    </p>
                    <button
                        class="text-sm font-semibold text-primary hover:underline"
                        @click="activeStack = 'Todos'"
                    >
                        Ver todos
                    </button>
                </div>

                <!-- ── Pagination ──────────────────────────────────────── -->
                <div
                    v-if="lastPage > 1"
                    class="reveal mt-16 flex items-center justify-between gap-4"
                >
                    <button
                        :disabled="!hasPrev"
                        class="flex items-center gap-2 rounded-full border border-border px-5 py-2.5 text-sm font-semibold transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                        @click="goTo(projects.prev_page_url)"
                    >
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

                    <button
                        :disabled="!hasNext"
                        class="flex items-center gap-2 rounded-full border border-border px-5 py-2.5 text-sm font-semibold transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                        @click="goTo(projects.next_page_url)"
                    >
                        Próxima
                        <ArrowRight class="h-4 w-4" />
                    </button>
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
                                    Próximo projeto
                                </p>
                                <h2
                                    class="text-3xl font-bold tracking-tight md:text-4xl"
                                >
                                    O seu pode ser o próximo.
                                </h2>
                                <p
                                    class="mt-3 text-lg leading-relaxed opacity-80"
                                >
                                    Estamos sempre em busca de novos desafios
                                    técnicos. Conta o que você tem em mente.
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
