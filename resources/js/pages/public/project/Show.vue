<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ExternalLink,
    Calendar,
    Briefcase,
    Code2,
    ArrowLeft,
    ArrowRight,
    Share2,
    Check,
    ChevronLeft,
    ChevronRight,
    X,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import { useHighlight } from '@/composables/useHighlight';

import { projects, contact } from '@/routes/public';
import user from '@/routes/public/user';
import type { PublicProjectViewData } from '@/types/public';

interface Props {
    project: PublicProjectViewData;
}

const props = defineProps<Props>();

useHighlight();

// ─── Lightbox com navegação ───────────────────────────────────────────────────
const lightboxIndex = ref<number | null>(null);
const gallery = computed(() => props.project.media?.gallery ?? []);

const openLightbox = (index: number) => {
    lightboxIndex.value = index;
    document.body.style.overflow = 'hidden';
};
const closeLightbox = () => {
    lightboxIndex.value = null;
    document.body.style.overflow = '';
};
const lightboxPrev = () => {
    if (lightboxIndex.value === null) {
        return;
    }

    lightboxIndex.value =
        (lightboxIndex.value - 1 + gallery.value.length) % gallery.value.length;
};
const lightboxNext = () => {
    if (lightboxIndex.value === null) {
        return;
    }

    lightboxIndex.value = (lightboxIndex.value + 1) % gallery.value.length;
};
const onLightboxKey = (e: KeyboardEvent) => {
    if (lightboxIndex.value === null) {
        return;
    }

    if (e.key === 'Escape') {
        closeLightbox();
    }

    if (e.key === 'ArrowLeft') {
        lightboxPrev();
    }

    if (e.key === 'ArrowRight') {
        lightboxNext();
    }
};

// ─── Galeria: featured (primeira) + grid (restante) ──────────────────────────
const featuredImage = computed(() => gallery.value[0] ?? null);
const gridImages = computed(() => gallery.value.slice(1));

// ─── Copy link ────────────────────────────────────────────────────────────────
const copied = ref(false);
const copyLink = async () => {
    await navigator.clipboard.writeText(window.location.href);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

// ─── Reading progress + sticky CTA ───────────────────────────────────────────
const readingProgress = ref(0);
const articleRef = ref<HTMLElement | null>(null);
const showStickyCta = ref(false);
const stickyClosed = ref(false);

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
    showStickyCta.value = readingProgress.value >= 80 && !stickyClosed.value;
};

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('keydown', onLightboxKey);

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
    window.removeEventListener('keydown', onLightboxKey);
    observer?.disconnect();
    document.body.style.overflow = '';
});
</script>

<template>
    <SeoHead />


    <!-- Reading progress bar -->
    <div class="fixed top-0 right-0 left-0 z-50 h-[2px] bg-border/40">
        <div class="h-full bg-primary transition-all duration-100 ease-out" :style="{ width: `${readingProgress}%` }" />
    </div>

    <!-- Floating actions — desktop -->
    <aside class="fixed top-1/2 right-6 z-40 hidden -translate-y-1/2 flex-col gap-2.5 xl:flex">
        <Link :href="projects.url()"
            class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all hover:bg-muted"
            title="Voltar a projetos">
            <ArrowLeft class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground" />
        </Link>
        <button
            class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all hover:bg-muted"
            :title="copied ? 'Link copiado!' : 'Copiar link'" @click="copyLink">
            <Check v-if="copied" class="h-4 w-4 text-primary" />
            <Share2 v-else class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground" />
        </button>
    </aside>

    <article ref="articleRef" class="pt-28 pb-32">
        <!-- ── Back + share (mobile) ───────────────────────────────── -->
        <div class="mx-auto mb-10 flex max-w-7xl items-center justify-between px-6 xl:hidden">
            <Link :href="projects.url()"
                class="group inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground">
                <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                Projetos
            </Link>
            <button
                class="flex items-center gap-1.5 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                @click="copyLink">
                <Check v-if="copied" class="h-4 w-4 text-primary" />
                <Share2 v-else class="h-4 w-4" />
                {{ copied ? 'Copiado!' : 'Compartilhar' }}
            </button>
        </div>

        <!-- ── Hero grid (bug corrigido) ──────────────────────────── -->
        <div class="mx-auto mb-16 max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                <!-- Left: meta (mobile) + título + descrição — UMA única coluna -->
                <div class="space-y-6 lg:col-span-7">
                    <!-- Meta strip — mobile/tablet only -->
                    <div
                        class="grid grid-cols-2 gap-5 rounded-2xl border border-border bg-muted/30 p-5 sm:grid-cols-4 lg:hidden">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                Cliente
                            </p>
                            <p class="text-sm font-semibold">
                                {{ project.client || '—' }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                Ano
                            </p>
                            <p class="text-sm font-semibold">
                                {{ project.year || '—' }}
                            </p>
                        </div>
                        <div v-if="project.url" class="col-span-2 space-y-1 sm:col-span-2">
                            <p class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                Link
                            </p>
                            <a :href="project.url" target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
                                Visitar site
                                <ExternalLink class="h-3.5 w-3.5" />
                            </a>
                        </div>
                    </div>

                    <!-- Título + descrição -->
                    <div class="space-y-4">
                        <h1 class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl">
                            {{ project.title }}
                        </h1>
                        <p v-if="project.description" class="text-xl leading-relaxed text-muted-foreground">
                            {{ project.description }}
                        </p>
                    </div>

                    <!-- CTAs mobile (abaixo da descrição) -->
                    <div class="flex flex-wrap gap-3 lg:hidden">
                        <a v-if="project.url" :href="project.url" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-primary-foreground transition-all hover:opacity-90">
                            Visitar website
                            <ExternalLink class="h-4 w-4" />
                        </a>
                        <Link :href="contact().url"
                            class="flex items-center gap-2 rounded-xl border border-border bg-background px-5 py-2.5 text-sm font-bold transition-all hover:bg-muted">
                            Quero algo assim
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </div>
                </div>

                <!-- Right: sticky sidebar — lg only -->
                <div class="hidden lg:col-span-5 lg:block">
                    <div
                        class="sticky top-28 overflow-hidden rounded-3xl border border-border/60 bg-muted/30 p-8 backdrop-blur-sm">
                        <h3 class="mb-7 font-bold">Sobre o projeto</h3>

                        <dl class="space-y-6">
                            <div v-if="project.client" class="flex items-start gap-3.5">
                                <Briefcase class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
                                <div>
                                    <dt
                                        class="mb-0.5 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                        Cliente
                                    </dt>
                                    <dd class="font-semibold">
                                        {{ project.client }}
                                    </dd>
                                </div>
                            </div>

                            <div v-if="project.year" class="flex items-start gap-3.5">
                                <Calendar class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
                                <div>
                                    <dt
                                        class="mb-0.5 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                        Ano
                                    </dt>
                                    <dd class="font-semibold">
                                        {{ project.year }}
                                    </dd>
                                </div>
                            </div>

                            <div v-if="project.stack?.length" class="flex items-start gap-3.5">
                                <Code2 class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
                                <div>
                                    <dt
                                        class="mb-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">
                                        Tecnologias
                                    </dt>
                                    <dd class="flex flex-wrap gap-1.5">
                                        <span v-for="tech in project.stack" :key="tech"
                                            class="rounded-lg border border-border bg-background px-2.5 py-1 text-xs font-medium">
                                            {{ tech }}
                                        </span>
                                    </dd>
                                </div>
                            </div>
                        </dl>

                        <div class="mt-8 space-y-3 border-t border-border pt-6">
                            <a v-if="project.url" :href="project.url" target="_blank" rel="noopener noreferrer"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-primary-foreground transition-all hover:scale-[1.02] hover:opacity-90">
                                Visitar website
                                <ExternalLink class="h-4 w-4" />
                            </a>
                            <Link :href="contact().url"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-background px-4 py-3 text-sm font-bold transition-all hover:bg-muted">
                                Quero algo assim
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Cover image ─────────────────────────────────────────── -->
        <div v-if="project.media?.cover" class="mx-auto mb-20 max-w-7xl px-6">
            <div class="relative aspect-[21/9] overflow-hidden rounded-[2.5rem] border border-border/50 shadow-2xl">
                <img :src="project.media?.cover.url" :alt="project.title" fetchpriority="high" loading="eager"
                    decoding="sync"
                    class="h-full w-full object-cover transition-transform duration-700 hover:scale-[1.02]" />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 to-transparent" />
            </div>
        </div>

        <!-- ── Content (case study) ────────────────────────────────── -->
        <div class="mx-auto max-w-3xl px-6">
            <div
                class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:underline prose-blockquote:border-primary/50 prose-blockquote:text-muted-foreground prose-blockquote:not-italic prose-code:rounded-md prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:rounded-2xl prose-pre:border prose-pre:border-border prose-pre:bg-muted/50 prose-img:rounded-3xl prose-img:shadow-lg prose-hr:border-border">
                <div v-html="project.content" />
            </div>
        </div>

        <!-- ── Gallery: featured + grid ───────────────────────────── -->
        <div v-if="gallery.length > 0" class="reveal mx-auto mt-24 max-w-7xl px-6">
            <div class="mb-10 text-center">
                <p class="mb-2 text-xs font-bold tracking-widest text-primary uppercase">
                    Galeria
                </p>
                <h2 class="text-3xl font-bold tracking-tight">
                    Interfaces do projeto
                </h2>
                <p class="mt-2 text-muted-foreground">
                    Clique para ampliar
                </p>
            </div>

            <!-- Imagem destaque (primeira) -->
            <button v-if="featuredImage"
                class="group relative mb-4 aspect-video w-full overflow-hidden rounded-3xl border border-border/50 bg-muted transition-all duration-300 hover:shadow-xl focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                aria-label="Ver screenshot 1 em tamanho completo" @click="openLightbox(0)">
                <img :src="featuredImage.url" :alt="`${project.title} — destaque`"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]" />
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-300 group-hover:bg-black/10">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-lg transition-opacity duration-300 group-hover:opacity-100">
                        <ExternalLink class="h-4 w-4" />
                    </span>
                </div>
            </button>

            <!-- Grid das demais screenshots -->
            <div v-if="gridImages.length > 0" class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <button v-for="(img, i) in gridImages" :key="i"
                    class="group relative aspect-video w-full overflow-hidden rounded-2xl border border-border/50 bg-muted transition-all duration-300 hover:shadow-lg focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                    :aria-label="`Ver screenshot ${i + 2} em tamanho completo`" @click="openLightbox(i + 1)">
                    <img :src="img.url" :alt="`${project.title} — screenshot ${i + 2}`"
                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-300 group-hover:bg-black/15">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-background/90 text-foreground opacity-0 shadow-lg transition-opacity duration-300 group-hover:opacity-100">
                            <ExternalLink class="h-3.5 w-3.5" />
                        </span>
                    </div>
                </button>
            </div>
        </div>

        <!-- ── Author Card ─────────────────────────────────────────── -->
        <div v-if="project.author" class="reveal mx-auto mt-16 max-w-3xl px-6">
            <div
                class="flex flex-col items-center gap-5 rounded-3xl border border-border/60 bg-muted/60 p-7 sm:flex-row sm:items-start">
                <HoverCard>
                    <HoverCardTrigger as-child>
                        <Link :href="user.show(project.author.username)" class="shrink-0" aria-label="Perfil do autor">
                            <img v-if="project.author.avatar_url" :src="project.author.avatar_url"
                                :alt="project.author.name"
                                class="h-16 w-16 rounded-full object-cover ring-2 ring-border transition-opacity hover:opacity-80"
                                loading="lazy" />
                        </Link>
                    </HoverCardTrigger>

                    <HoverCardContent class="w-72">
                        <div class="flex items-start gap-4">
                            <Avatar class="h-14 w-14 shrink-0">
                                <AvatarImage :src="project.author.avatar_url || ''" alt="" />
                                <AvatarFallback>{{
                                    project.author.name.charAt(0)
                                }}</AvatarFallback>
                            </Avatar>
                            <div class="min-w-0 space-y-1">
                                <h4 class="text-sm font-semibold">
                                    {{ project.author.name }}
                                </h4>
                                <p v-if="project.author.title" class="text-xs text-muted-foreground">
                                    {{ project.author.title }}
                                </p>
                                <Link :href="user.show(project.author.username)
                                    " class="text-xs text-primary hover:underline">
                                    Ver perfil →
                                </Link>
                            </div>
                        </div>
                    </HoverCardContent>
                </HoverCard>

                <div class="text-center sm:text-left">
                    <p class="mb-1 text-xs tracking-widest text-muted-foreground uppercase">
                        Desenvolvido por
                    </p>
                    <Link :href="user.show(project.author.username)" class="text-lg font-bold hover:underline">
                        {{ project.author.name }}
                    </Link>
                    <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                        Desenvolvedor e fundador da mktcode. Apaixonado por
                        transformar ideias em produtos digitais de alta
                        performance.
                    </p>
                </div>
            </div>
        </div>

        <!-- ── Bottom CTA ──────────────────────────────────────────── -->
        <div class="reveal mx-auto mt-16 max-w-7xl px-6">
            <div class="overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14">
                <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-lg">
                        <p class="mb-2 text-xs font-bold tracking-widest uppercase opacity-60">
                            Próximo passo
                        </p>
                        <h2 class="text-3xl font-bold tracking-tight md:text-4xl">
                            Gostou desse projeto?
                        </h2>
                        <p class="mt-3 text-lg leading-relaxed opacity-80">
                            Vamos transformar o seu desafio em um produto
                            digital de sucesso. Conta o que você precisa.
                        </p>
                    </div>
                    <div class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end">
                        <Link :href="contact().url"
                            class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]">
                            Falar agora
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                        <Link :href="projects.url()"
                            class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20">
                            Ver mais projetos
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- ── Sticky bottom CTA — aparece após 80% de scroll ──────────── -->
    <Teleport to="body">
        <Transition enter-active-class="transition-all duration-300" enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition-all duration-200"
            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-4">
            <div v-if="showStickyCta" class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 px-4">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-border bg-background/95 px-5 py-3 shadow-lg backdrop-blur-sm">
                    <p class="text-sm font-medium">Gostou do projeto?</p>
                    <Link :href="contact().url"
                        class="flex items-center gap-1.5 rounded-full bg-primary px-4 py-2 text-xs font-bold text-primary-foreground transition-all hover:opacity-90 active:scale-[0.98]">
                        Falar agora
                        <ArrowRight class="h-3.5 w-3.5" />
                    </Link>
                    <button
                        class="flex h-6 w-6 items-center justify-center rounded-full text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        aria-label="Fechar" @click="
                            stickyClosed = true;
                        showStickyCta = false;
                        ">
                        <X class="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>

    <!-- ── Lightbox com navegação ────────────────────────────────────── -->
    <Teleport to="body">
        <Transition enter-active-class="transition-all duration-300" enter-from-class="opacity-0"
            leave-active-class="transition-all duration-200" leave-to-class="opacity-0">
            <div v-if="lightboxIndex !== null"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                role="dialog" aria-modal="true" aria-label="Imagem ampliada" @click.self="closeLightbox">
                <!-- Fechar -->
                <button
                    class="absolute top-5 right-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                    aria-label="Fechar" @click="closeLightbox">
                    <X class="h-5 w-5" />
                </button>

                <!-- Contador -->
                <div
                    class="absolute top-5 left-1/2 -translate-x-1/2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-white">
                    {{ (lightboxIndex ?? 0) + 1 }} / {{ gallery.length }}
                </div>

                <!-- Prev -->
                <button v-if="gallery.length > 1"
                    class="absolute left-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                    aria-label="Anterior" @click="lightboxPrev">
                    <ChevronLeft class="h-5 w-5" />
                </button>

                <!-- Next -->
                <button v-if="gallery.length > 1"
                    class="absolute right-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                    aria-label="Próxima" @click="lightboxNext">
                    <ChevronRight class="h-5 w-5" />
                </button>

                <!-- Imagem -->
                <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100" mode="out-in">
                    <img v-if="lightboxIndex !== null" :key="lightboxIndex" :src="gallery[lightboxIndex].url"
                        :alt="`${project.title} — screenshot ${lightboxIndex + 1}`"
                        class="max-h-[85vh] max-w-[90vw] rounded-2xl object-contain shadow-2xl" />
                </Transition>
            </div>
        </Transition>
    </Teleport>

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

/* ── Tabelas no prose ───────────────────────────────────── */
:deep(.prose table) {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid hsl(var(--border));
}

:deep(.prose table tbody tr:first-child td) {
    background: hsl(var(--muted));
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: hsl(var(--muted-foreground));
}

:deep(.prose table tbody tr:not(:first-child):nth-child(even) td) {
    background: hsl(var(--muted) / 0.4);
}

:deep(.prose table td) {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid hsl(var(--border) / 0.6);
    vertical-align: top;
}

:deep(.prose table tbody tr:last-child td) {
    border-bottom: none;
}
</style>
