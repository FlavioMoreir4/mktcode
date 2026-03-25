<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import hljs from 'highlight.js';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import blog from '@/routes/public/blog';
import user from '@/routes/public/user';
import type { PublicPostDetailData, SeoData } from '@/types/public';

const props = defineProps<{
    post: PublicPostDetailData;
    seo?: SeoData;
}>();

// ─── Reading progress ───────────────────────────────────────────────────────
const readingProgress = ref(0);
const articleRef = ref<HTMLElement | null>(null);
const copied = ref(false);

// ─── TOC ────────────────────────────────────────────────────────────────────
interface TocItem {
    id: string;
    text: string;
    level: number;
}
const tocItems = ref<TocItem[]>([]);
const activeTocId = ref('');
const showAllTags = ref(false);

const TAGS_VISIBLE = 5;
const visibleTags = computed(() =>
    showAllTags.value
        ? (props.post.tags ?? [])
        : (props.post.tags ?? []).slice(0, TAGS_VISIBLE),
);
const hiddenTagCount = computed(() =>
    Math.max(0, (props.post.tags?.length ?? 0) - TAGS_VISIBLE),
);

// ─── Helpers ─────────────────────────────────────────────────────────────────
const getTagName = (tag: any): string => {
    if (typeof tag.name === 'string') {
        return tag.name;
    }

    return tag.name?.en ?? tag.name?.pt ?? Object.values(tag.name)[0] ?? '';
};

const slugify = (text: string) =>
    text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-');

const minutesRemaining = computed(() => {
    const remaining =
        props.post.reading_time * (1 - readingProgress.value / 100);

    return Math.max(1, Math.ceil(remaining));
});

const wordCountFormatted = computed(
    () => props.post.word_count?.toLocaleString('pt-BR') ?? null,
);

// ─── Scroll handler ───────────────────────────────────────────────────────────
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

// ─── TOC: extrai headings do HTML renderizado ─────────────────────────────────
const buildToc = () => {
    const contentEl = document.querySelector('[data-post-body]');

    if (!contentEl) {
        return;
    }

    const headings = contentEl.querySelectorAll<HTMLHeadingElement>('h2, h3');
    const items: TocItem[] = [];

    headings.forEach((h) => {
        const text = h.textContent?.replace(/^#/, '').trim() ?? '';

        if (!text) {
            return;
        }

        // Garante que o heading tem id para scroll-spy
        if (!h.id) {
            h.id = slugify(text);
        }

        items.push({ id: h.id, text, level: parseInt(h.tagName[1]) });
    });

    tocItems.value = items;
};

// ─── TOC scroll-spy via IntersectionObserver ──────────────────────────────────
let tocObserver: IntersectionObserver | null = null;

const initTocObserver = () => {
    tocObserver?.disconnect();

    tocObserver = new IntersectionObserver(
        (entries) => {
            const visible = entries.find((e) => e.isIntersecting);

            if (visible) {
                activeTocId.value = visible.target.id;
            }
        },
        { rootMargin: '-20% 0px -70% 0px', threshold: 0 },
    );

    tocItems.value.forEach(({ id }) => {
        const el = document.getElementById(id);

        if (el) {
            tocObserver!.observe(el);
        }
    });
};

const scrollToHeading = (id: string) => {
    const el = document.getElementById(id);

    if (!el) {
        return;
    }

    window.scrollTo({ top: el.offsetTop - 80, behavior: 'smooth' });
};

// ─── Share ────────────────────────────────────────────────────────────────────
const postUrl = computed(() => blog.show(props.post.slug).url);

const copyLink = async () => {
    await navigator.clipboard.writeText(postUrl.value);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

const shareOnX = () => {
    const url = `https://x.com/intent/tweet?text=${encodeURIComponent(props.post.title)}&url=${encodeURIComponent(postUrl.value)}`;
    window.open(url, '_blank', 'noopener');
};

// ─── Lifecycle ────────────────────────────────────────────────────────────────
onMounted(() => {
    hljs.highlightAll();
    window.addEventListener('scroll', updateProgress, { passive: true });
    buildToc();
    initTocObserver();
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
    tocObserver?.disconnect();
});
</script>

<template>
    <SeoHead v-bind="seo" />

    <PublicLayout>
        <!-- Reading Progress Bar -->
        <div class="fixed top-0 right-0 left-0 z-50 h-[2px] bg-border/40">
            <div
                class="h-full bg-primary transition-all duration-100 ease-out"
                :style="{ width: `${readingProgress}%` }"
                aria-label="Progresso de leitura"
            />
        </div>

        <article ref="articleRef" class="px-6 pt-28 pb-24">
            <div
                class="mx-auto flex max-w-7xl grid-cols-1 gap-8 xl:grid-cols-[auto_minmax(0,1fr)_auto]"
            >
                <!-- ── TOC — Desktop Left Sidebar ───────────────────────────── -->
                <div class="hidden w-52 2xl:block">
                    <div class="sticky top-28 flex flex-col gap-0">
                        <p
                            id="toc-heading"
                            class="mb-3 text-xs tracking-widest text-muted-foreground uppercase"
                        >
                            Neste artigo
                        </p>

                        <nav
                            aria-labelledby="toc-heading"
                            class="flex flex-col gap-0.5"
                        >
                            <button
                                v-for="item in tocItems"
                                :key="item.id"
                                @click="scrollToHeading(item.id)"
                                class="toc-item group flex cursor-pointer items-start gap-2 py-1 text-left transition-colors duration-150"
                                :class="[
                                    item.level === 3 ? 'pl-4' : 'pl-0',
                                    activeTocId === item.id
                                        ? 'active'
                                        : 'text-muted-foreground hover:text-foreground/70',
                                ]"
                                :aria-current="
                                    activeTocId === item.id
                                        ? 'location'
                                        : undefined
                                "
                            >
                                <span
                                    class="mt-1.5 h-1 w-1 shrink-0 rounded-full transition-all duration-150"
                                    :class="
                                        activeTocId === item.id
                                            ? 'scale-125 bg-foreground'
                                            : 'bg-border'
                                    "
                                    aria-hidden="true"
                                />
                                <span
                                    class="line-clamp-2 text-xs leading-relaxed"
                                    :class="
                                        activeTocId === item.id
                                            ? 'font-medium'
                                            : ''
                                    "
                                >
                                    {{ item.text }}
                                </span>
                            </button>
                        </nav>

                        <!-- Mini reading progress -->
                        <div v-if="readingProgress > 0" class="mt-4 space-y-1">
                            <div class="h-px w-full rounded-full bg-border/60">
                                <div
                                    class="h-px rounded-full bg-primary transition-all duration-300"
                                    :style="{ width: `${readingProgress}%` }"
                                    aria-hidden="true"
                                />
                            </div>
                            <p
                                class="text-right text-[10px] text-muted-foreground"
                            >
                                ~{{ minutesRemaining }} min restantes
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ── Central Column: Content ──────────────────────────────── -->
                <div class="mx-auto w-full max-w-3xl min-w-0">
                    <header class="animate-fade-in-up mb-12">
                        <!-- Breadcrumb + Category -->
                        <div
                            class="mb-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm"
                            aria-label="Caminho de navegação"
                        >
                            <Link
                                href="/blog"
                                class="text-muted-foreground transition-colors hover:text-foreground"
                            >
                                Blog
                            </Link>
                            <span class="text-border" aria-hidden="true"
                                >›</span
                            >
                            <Badge v-if="post.category" variant="secondary">
                                {{ post.category.name }}
                            </Badge>
                        </div>

                        <h1
                            class="mb-6 text-center text-4xl leading-[1.1] font-bold tracking-tight md:text-6xl"
                        >
                            {{ post.title }}
                        </h1>

                        <p
                            v-if="post.excerpt"
                            class="mb-8 text-center text-xl leading-relaxed text-muted-foreground"
                        >
                            {{ post.excerpt }}
                        </p>

                        <div
                            class="flex flex-wrap items-center justify-center gap-4 text-sm"
                        >
                            <div
                                v-if="post.author"
                                class="flex items-center gap-2"
                            >
                                <img
                                    v-if="post.author.avatar_url"
                                    :src="post.author.avatar_url"
                                    :alt="post.author.name"
                                    class="h-7 w-7 rounded-full object-cover ring-1 ring-border"
                                    loading="lazy"
                                />
                                <Link
                                    :href="user.show(post.author.username)"
                                    class="font-medium hover:underline"
                                >
                                    {{ post.author.name }}
                                </Link>
                            </div>

                            <span
                                class="hidden text-border sm:block"
                                aria-hidden="true"
                                >·</span
                            >

                            <time
                                v-if="post.published_at"
                                :datetime="post.published_at"
                                class="text-muted-foreground"
                            >
                                {{ formatDate(post.published_at) }}
                            </time>

                            <span
                                class="hidden text-border sm:block"
                                aria-hidden="true"
                                >·</span
                            >

                            <span
                                class="flex items-center gap-1 text-muted-foreground"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {{ post.reading_time }} min de leitura
                                <template v-if="wordCountFormatted">
                                    <span class="text-border" aria-hidden="true"
                                        >·</span
                                    >
                                    {{ wordCountFormatted }} palavras
                                </template>
                            </span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <div
                        v-if="post.media?.cover"
                        class="mb-16 overflow-hidden rounded-3xl bg-muted shadow-lg"
                    >
                        <img
                            :src="post.media?.cover?.url"
                            :alt="post.title"
                            fetchpriority="high"
                            loading="eager"
                            decoding="sync"
                            class="aspect-video w-full object-cover transition-transform duration-700 hover:scale-[1.02]"
                        />
                    </div>
                    <div
                        v-else
                        class="mb-16 h-px bg-border/60"
                        aria-hidden="true"
                    />

                    <!-- Post Body -->
                    <div
                        data-post-body
                        class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:underline prose-blockquote:border-primary/50 prose-blockquote:text-muted-foreground prose-blockquote:not-italic prose-code:rounded-md prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:rounded-2xl prose-pre:border prose-pre:border-border prose-img:rounded-2xl prose-img:shadow-md prose-hr:border-border"
                    >
                        <div v-html="post.body" />
                    </div>

                    <footer class="mt-20">
                        <!-- Tags colapsáveis -->
                        <div
                            v-if="post.tags && post.tags.length > 0"
                            class="mb-10"
                        >
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(tag, i) in visibleTags"
                                    :key="i"
                                    class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    #{{ getTagName(tag) }}
                                </span>

                                <button
                                    v-if="hiddenTagCount > 0 && !showAllTags"
                                    @click="showAllTags = true"
                                    class="rounded-full border border-dashed border-border px-3 py-1 text-xs text-muted-foreground transition-colors hover:text-foreground"
                                    aria-label="Mostrar mais tags"
                                >
                                    +{{ hiddenTagCount }} mais
                                </button>
                            </div>
                        </div>

                        <!-- Share row (mobile) -->
                        <div class="mb-10 flex items-center gap-3 xl:hidden">
                            <span class="text-sm text-muted-foreground"
                                >Compartilhar:</span
                            >
                            <button
                                @click="shareOnX"
                                class="flex items-center gap-1.5 rounded-full border border-border px-3 py-1.5 text-sm transition-colors hover:bg-muted"
                                aria-label="Compartilhar no X (Twitter)"
                            >
                                <svg
                                    class="h-3.5 w-3.5"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.738l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                                    />
                                </svg>
                                X
                            </button>
                            <button
                                @click="copyLink"
                                class="flex items-center gap-1.5 rounded-full border border-border px-3 py-1.5 text-sm transition-colors hover:bg-muted"
                                :aria-label="
                                    copied
                                        ? 'Link copiado!'
                                        : 'Copiar link do post'
                                "
                            >
                                <svg
                                    v-if="!copied"
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-3.5 w-3.5 text-primary"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                {{ copied ? 'Copiado!' : 'Copiar link' }}
                            </button>
                        </div>

                        <div class="border-t border-border pt-10" />

                        <!-- Author Card -->
                        <div
                            v-if="post.author"
                            class="mt-10 flex flex-col items-center gap-5 rounded-3xl border border-border/60 bg-muted/60 p-7 sm:flex-row sm:items-start"
                        >
                            <HoverCard>
                                <HoverCardTrigger as-child>
                                    <Link
                                        :href="user.show(post.author.username)"
                                        class="shrink-0"
                                        aria-label="Perfil do autor"
                                    >
                                        <img
                                            v-if="post.author.avatar_url"
                                            :src="post.author.avatar_url"
                                            :alt="post.author.name"
                                            class="h-16 w-16 rounded-full object-cover ring-2 ring-border transition-opacity hover:opacity-80"
                                            loading="lazy"
                                        />
                                    </Link>
                                </HoverCardTrigger>

                                <HoverCardContent class="w-72">
                                    <div class="flex items-start gap-4">
                                        <Avatar class="h-14 w-14 shrink-0">
                                            <AvatarImage
                                                :src="
                                                    post.author.avatar_url || ''
                                                "
                                                alt=""
                                            />
                                            <AvatarFallback>{{
                                                post.author.name.charAt(0)
                                            }}</AvatarFallback>
                                        </Avatar>
                                        <div class="min-w-0 space-y-1">
                                            <h4 class="text-sm font-semibold">
                                                {{ post.author.name }}
                                            </h4>
                                            <p
                                                v-if="post.author.title"
                                                class="text-xs text-muted-foreground"
                                            >
                                                {{ post.author.title }}
                                            </p>
                                            <Link
                                                :href="
                                                    user.show(
                                                        post.author.username,
                                                    )
                                                "
                                                class="text-xs text-primary hover:underline"
                                            >
                                                Ver perfil →
                                            </Link>
                                        </div>
                                    </div>
                                </HoverCardContent>
                            </HoverCard>

                            <div class="text-center sm:text-left">
                                <p
                                    class="mb-1 text-xs tracking-widest text-muted-foreground uppercase"
                                >
                                    Escrito por
                                </p>
                                <Link
                                    :href="user.show(post.author.username)"
                                    class="text-lg font-bold hover:underline"
                                >
                                    {{ post.author.name }}
                                </Link>
                                <p
                                    class="mt-1.5 text-sm leading-relaxed text-muted-foreground"
                                >
                                    Desenvolvedor e fundador da mktcode.
                                    Apaixonado por transformar ideias em
                                    produtos digitais de alta performance.
                                </p>
                            </div>
                        </div>
                    </footer>
                </div>

                <!-- ── Floating Actions — Desktop Right Sidebar ──────────────── -->
                <div class="hidden w-10 xl:block">
                    <div class="sticky top-28 flex flex-col gap-3">
                        <Link
                            href="/blog"
                            class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                            aria-label="Voltar ao blog"
                            title="Voltar ao blog"
                        >
                            <svg
                                class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                        </Link>

                        <button
                            @click="shareOnX"
                            class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                            aria-label="Compartilhar no X"
                            title="Compartilhar no X"
                        >
                            <svg
                                class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.738l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                                />
                            </svg>
                        </button>

                        <button
                            @click="copyLink"
                            class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                            :aria-label="
                                copied ? 'Link copiado!' : 'Copiar link do post'
                            "
                            :title="copied ? 'Link copiado!' : 'Copiar link'"
                        >
                            <svg
                                v-if="!copied"
                                class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-4 w-4 text-primary"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </article>
    </PublicLayout>
</template>

<style scoped>
@media (prefers-color-scheme: dark) {
    .phiki,
    .phiki span,
    .phiki code {
        color: var(--phiki-dark-color) !important;
        background-color: var(
            --phiki-dark-background-color,
            #0d1117
        ) !important;
        font-style: var(--phiki-dark-font-style) !important;
        font-weight: var(--phiki-dark-font-weight) !important;
        text-decoration: var(--phiki-dark-text-decoration) !important;
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out both;
}
</style>
