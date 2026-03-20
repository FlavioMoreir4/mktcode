<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import hljs from 'highlight.js';
import {
    ExternalLink,
    Calendar,
    Briefcase,
    Code2,
    ArrowLeft,
    ArrowRight,
    Share2,
    Check,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { projects, contact } from '@/routes/public';
import type { Project } from '@/types/models';

const props = defineProps<{
    project: Project;
}>();

// ─── Media helpers ────────────────────────────────────────────────────────────
const coverImage = computed(
    () =>
        props.project.media?.find((m) => m.collection_name === 'cover')
            ?.original_url ??
        props.project.media?.[0]?.original_url ??
        null,
);

const screenshots = computed(
    () =>
        props.project.media?.filter(
            (m) => m.collection_name === 'screenshots',
        ) ?? [],
);

// ─── Tag name helper ──────────────────────────────────────────────────────────
const tagName = (tag: any): string => {
    if (typeof tag.name === 'string') {
        return tag.name;
    }

    return tag.name?.en ?? tag.name?.pt ?? Object.values(tag.name)[0] ?? '';
};

// ─── Lightbox ─────────────────────────────────────────────────────────────────
const lightboxSrc = ref<string | null>(null);
const openLightbox = (src: string) => {
    lightboxSrc.value = src;
    document.body.style.overflow = 'hidden';
};
const closeLightbox = () => {
    lightboxSrc.value = null;
    document.body.style.overflow = '';
};

// ─── Copy link ────────────────────────────────────────────────────────────────
const copied = ref(false);
const copyLink = async () => {
    await navigator.clipboard.writeText(window.location.href);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

// ─── Reading progress ─────────────────────────────────────────────────────────
const readingProgress = ref(0);
const articleRef = ref<HTMLElement | null>(null);

const updateProgress = () => {
    if (!articleRef.value) {
        return;
    }

    const el = articleRef.value;
    const scrollTop = window.scrollY - el.offsetTop;
    const height = el.offsetHeight - window.innerHeight;
    readingProgress.value = Math.min(
        100,
        Math.max(0, (scrollTop / height) * 100),
    );
};

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    hljs.highlightAll();

    window.addEventListener('scroll', updateProgress, { passive: true });

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

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
    observer?.disconnect();
    document.body.style.overflow = '';
});
</script>

<template>
    <SeoHead v-bind="project.seo" />

    <PublicLayout>
        <!-- Reading progress bar -->
        <div class="fixed top-0 right-0 left-0 z-50 h-[2px] bg-border/40">
            <div
                class="h-full bg-primary transition-all duration-100 ease-out"
                :style="{ width: `${readingProgress}%` }"
            />
        </div>

        <!-- Floating share actions — desktop -->
        <aside
            class="fixed top-1/2 right-6 z-40 hidden -translate-y-1/2 flex-col gap-2.5 xl:flex"
        >
            <Link
                :href="projects.url()"
                class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all hover:bg-muted"
                title="Voltar a projetos"
            >
                <ArrowLeft
                    class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                />
            </Link>
            <button
                class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all hover:bg-muted"
                :title="copied ? 'Link copiado!' : 'Copiar link'"
                @click="copyLink"
            >
                <Check v-if="copied" class="h-4 w-4 text-primary" />
                <Share2
                    v-else
                    class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                />
            </button>
        </aside>

        <article ref="articleRef" class="pt-28 pb-32">
            <!-- ── Back + share (mobile) ───────────────────────────────── -->
            <div
                class="mx-auto mb-10 flex max-w-7xl items-center justify-between px-6 xl:hidden"
            >
                <Link
                    :href="projects.url()"
                    class="group inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                >
                    <ArrowLeft
                        class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                    />
                    Projetos
                </Link>
                <button
                    class="flex items-center gap-1.5 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                    @click="copyLink"
                >
                    <Check v-if="copied" class="h-4 w-4 text-primary" />
                    <Share2 v-else class="h-4 w-4" />
                    {{ copied ? 'Copiado!' : 'Compartilhar' }}
                </button>
            </div>

            <!-- ── Hero grid ───────────────────────────────────────────── -->
            <div class="mx-auto mb-16 max-w-7xl px-6">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                    <!-- Left: title + meta strip (mobile) -->
                    <div class="reveal space-y-8 lg:col-span-7">
                        <!-- Category badge -->
                        <p
                            v-if="project.category"
                            class="text-xs font-bold tracking-widest text-primary/70 uppercase"
                        >
                            {{ project.category }}
                        </p>

                        <div class="space-y-4">
                            <h1
                                class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                            >
                                {{ project.title }}
                            </h1>
                            <p
                                v-if="project.description"
                                class="text-xl leading-relaxed text-muted-foreground"
                            >
                                {{ project.description }}
                            </p>
                        </div>

                        <!-- Highlights -->
                        <ul v-if="project.highlights?.length" class="space-y-2">
                            <li
                                v-for="h in project.highlights"
                                :key="h"
                                class="flex items-start gap-3 text-base"
                            >
                                <span
                                    class="mt-1 leading-none font-bold text-primary"
                                    >→</span
                                >
                                <span class="text-muted-foreground">{{
                                    h
                                }}</span>
                            </li>
                        </ul>

                        <!-- Tags -->
                        <div
                            v-if="project.tags?.length"
                            class="flex flex-wrap gap-2"
                        >
                            <span
                                v-for="tag in project.tags"
                                :key="tag.id"
                                class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground"
                            >
                                #{{ tagName(tag) }}
                            </span>
                        </div>

                        <!-- Meta strip — mobile/tablet only -->
                        <div
                            class="grid grid-cols-2 gap-5 rounded-2xl border border-border bg-muted/30 p-5 sm:grid-cols-4 lg:hidden"
                        >
                            <div class="space-y-1">
                                <p
                                    class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Cliente
                                </p>
                                <p class="text-sm font-semibold">
                                    {{ project.client || '—' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <p
                                    class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Ano
                                </p>
                                <p class="text-sm font-semibold">
                                    {{ project.year || '—' }}
                                </p>
                            </div>
                            <div
                                v-if="project.url"
                                class="col-span-2 space-y-1 sm:col-span-2"
                            >
                                <p
                                    class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                >
                                    Link
                                </p>
                                <a
                                    :href="project.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center gap-1 text-sm font-semibold text-primary hover:underline"
                                >
                                    Visitar site
                                    <ExternalLink class="h-3.5 w-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: sticky sidebar — lg only -->
                    <div class="hidden lg:col-span-5 lg:block">
                        <div
                            class="sticky top-28 overflow-hidden rounded-3xl border border-border/60 bg-muted/30 p-8 backdrop-blur-sm"
                        >
                            <h3 class="mb-7 font-bold">Sobre o projeto</h3>

                            <dl class="space-y-6">
                                <div
                                    v-if="project.client"
                                    class="flex items-start gap-3.5"
                                >
                                    <Briefcase
                                        class="mt-0.5 h-5 w-5 shrink-0 text-primary"
                                    />
                                    <div>
                                        <dt
                                            class="mb-0.5 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                        >
                                            Cliente
                                        </dt>
                                        <dd class="font-semibold">
                                            {{ project.client }}
                                        </dd>
                                    </div>
                                </div>

                                <div
                                    v-if="project.year"
                                    class="flex items-start gap-3.5"
                                >
                                    <Calendar
                                        class="mt-0.5 h-5 w-5 shrink-0 text-primary"
                                    />
                                    <div>
                                        <dt
                                            class="mb-0.5 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                        >
                                            Ano
                                        </dt>
                                        <dd class="font-semibold">
                                            {{ project.year }}
                                        </dd>
                                    </div>
                                </div>

                                <div
                                    v-if="project.stack?.length"
                                    class="flex items-start gap-3.5"
                                >
                                    <Code2
                                        class="mt-0.5 h-5 w-5 shrink-0 text-primary"
                                    />
                                    <div>
                                        <dt
                                            class="mb-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                                        >
                                            Tecnologias
                                        </dt>
                                        <dd class="flex flex-wrap gap-1.5">
                                            <span
                                                v-for="tech in project.stack"
                                                :key="tech"
                                                class="rounded-lg border border-border bg-background px-2.5 py-1 text-xs font-medium"
                                            >
                                                {{ tech }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </dl>

                            <!-- Sidebar CTAs -->
                            <div
                                class="mt-8 space-y-3 border-t border-border pt-6"
                            >
                                <a
                                    v-if="project.url"
                                    :href="project.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-primary-foreground transition-all hover:scale-[1.02] hover:opacity-90"
                                >
                                    Visitar website
                                    <ExternalLink class="h-4 w-4" />
                                </a>
                                <Link
                                    :href="contact().url"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-background px-4 py-3 text-sm font-bold transition-all hover:bg-muted"
                                >
                                    Quero algo assim
                                    <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Cover image ─────────────────────────────────────────── -->
            <div
                v-if="project.cover"
                class="reveal mx-auto mb-20 max-w-7xl px-6"
            >
                <div
                    class="relative aspect-[21/9] overflow-hidden rounded-[2.5rem] border border-border/50 shadow-2xl"
                >
                    <img
                        :src="project.cover"
                        :alt="project.title"
                        class="h-full w-full object-cover transition-transform duration-700 hover:scale-[1.02]"
                    />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 to-transparent"
                    />
                </div>
            </div>

            <!-- ── Content (case study) ────────────────────────────────── -->
            <div class="reveal mx-auto max-w-3xl px-6">
                <div
                    class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:underline prose-blockquote:border-primary/50 prose-blockquote:text-muted-foreground prose-blockquote:not-italic prose-code:rounded-md prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:rounded-2xl prose-pre:border prose-pre:border-border prose-pre:bg-muted/50 prose-img:rounded-3xl prose-img:shadow-lg prose-hr:border-border"
                >
                    <div v-html="project.content" />
                </div>
            </div>

            <!-- ── Screenshots gallery ────────────────────────────────── -->
            <div
                v-if="screenshots.length"
                class="reveal mx-auto mt-24 max-w-7xl px-6"
            >
                <div class="mb-10 text-center">
                    <p
                        class="mb-2 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Galeria
                    </p>
                    <h2 class="text-3xl font-bold tracking-tight">
                        Interfaces do projeto
                    </h2>
                    <p class="mt-2 text-muted-foreground">
                        Clique para ampliar
                    </p>
                </div>

                <!-- 1 screenshot: full-width; 2+: grid -->
                <div
                    :class="
                        screenshots.length === 1
                            ? 'mx-auto max-w-4xl'
                            : 'grid grid-cols-1 gap-6 md:grid-cols-2'
                    "
                >
                    <button
                        v-for="(img, i) in screenshots"
                        :key="img.id"
                        class="group relative aspect-video w-full overflow-hidden rounded-3xl border border-border/50 bg-muted transition-all duration-300 hover:shadow-xl focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                        :aria-label="`Ver screenshot ${i + 1} em tamanho completo`"
                        @click="openLightbox(img.original_url)"
                    >
                        <img
                            :src="img.original_url"
                            :alt="`${project.title} — screenshot ${i + 1}`"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-300 group-hover:bg-black/10"
                        >
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-lg transition-opacity duration-300 group-hover:opacity-100"
                            >
                                <ExternalLink class="h-4 w-4" />
                            </span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- ── Bottom CTA ──────────────────────────────────────────── -->
            <div class="reveal mx-auto mt-24 max-w-7xl px-6">
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
                                Gostou desse projeto?
                            </h2>
                            <p class="mt-3 text-lg leading-relaxed opacity-80">
                                Vamos transformar o seu desafio em um produto
                                digital de sucesso. Conta o que você precisa.
                            </p>
                        </div>
                        <div
                            class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                        >
                            <Link
                                :href="contact().url"
                                class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                            >
                                Falar agora
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                            <Link
                                :href="projects.url()"
                                class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20"
                            >
                                Ver mais projetos
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- ── Lightbox ──────────────────────────────────────────────────── -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-all duration-300"
                enter-from-class="opacity-0"
                leave-active-class="transition-all duration-200"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="lightboxSrc"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Imagem ampliada"
                    @click.self="closeLightbox"
                    @keydown.escape="closeLightbox"
                >
                    <button
                        class="absolute top-5 right-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                        aria-label="Fechar"
                        @click="closeLightbox"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>

                    <Transition
                        enter-active-class="transition-all duration-300"
                        enter-from-class="opacity-0 scale-95"
                    >
                        <img
                            v-if="lightboxSrc"
                            :src="lightboxSrc"
                            alt="Screenshot ampliada"
                            class="max-h-[90vh] max-w-[95vw] rounded-2xl object-contain shadow-2xl"
                        />
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </PublicLayout>
</template>

<style scoped>
/* ── Scroll reveal ──────────────────────────────────────── */
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

/* ── Prose overrides ────────────────────────────────────── */
:deep(.prose pre) {
    padding: 1.5rem;
    border-radius: 1.25rem;
    font-size: 0.9rem;
    line-height: 1.6;
}

:deep(.prose hr) {
    margin: 4rem 0;
    border-color: hsl(var(--border) / 0.5);
}

:deep(.prose code) {
    background: hsl(var(--muted));
    padding: 0.2rem 0.4rem;
    border-radius: 0.4rem;
    font-size: 0.85em;
    font-weight: 500;
}

:deep(.prose pre code) {
    background: transparent;
    padding: 0;
    border-radius: 0;
}
</style>
