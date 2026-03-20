<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    Calendar,
    Clock,
    Tag,
    Rss,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import { contact } from '@/routes/public';
import blog from '@/routes/public/blog';
import type { PaginatedResponse, PublicPost } from '@/types';

const props = defineProps<{
    posts: PaginatedResponse<PublicPost>;
}>();

// ─── Featured = first post on page 1, otherwise none ─────────────────────────
const featuredPost = computed(() =>
    props.posts.meta.current_page === 1 ? (props.posts.data[0] ?? null) : null,
);

const restPosts = computed(() =>
    featuredPost.value ? props.posts.data.slice(1) : props.posts.data,
);

// ─── Tag name helper ──────────────────────────────────────────────────────────
const tagName = (tag: any): string => {
    if (typeof tag.name === 'string') {
        return tag.name;
    }

    return tag.name?.en ?? tag.name?.pt ?? Object.values(tag.name)[0] ?? '';
};

// ─── Pagination ───────────────────────────────────────────────────────────────
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
    <SeoHead title="Blog" />

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
                            Blog & Insights
                        </p>
                        <h1
                            class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                        >
                            Insights &amp;<br />
                            <span class="text-muted-foreground"
                                >explorações.</span
                            >
                        </h1>
                        <p
                            class="mt-5 text-lg leading-relaxed text-muted-foreground"
                        >
                            Pensamentos sobre desenvolvimento, design e a
                            interseção entre negócios e tecnologia. Sem firulas,
                            apenas o que aprendemos no campo de batalha.
                        </p>
                    </div>

                    <!-- Counter -->
                    <div class="shrink-0 text-right">
                        <div
                            class="mb-1 flex items-center justify-end gap-2 text-muted-foreground"
                        >
                            <Rss class="h-4 w-4 text-primary" />
                            <span
                                class="text-xs font-semibold tracking-wider uppercase"
                                >Publicações</span
                            >
                        </div>
                        <p class="text-5xl font-bold tabular-nums">
                            {{ posts.total }}<span class="text-primary">+</span>
                        </p>
                        <p class="text-sm text-muted-foreground">
                            artigos publicados
                        </p>
                    </div>
                </div>

                <!-- ── Featured Post ────────────────────────────────────── -->
                <div v-if="featuredPost" class="reveal mb-20">
                    <Link
                        :href="blog.show(featuredPost.slug)"
                        class="group relative grid grid-cols-1 overflow-hidden rounded-[2rem] border border-border bg-card transition-all duration-500 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/8 lg:grid-cols-2"
                    >
                        <!-- Image -->
                        <div
                            class="relative aspect-video overflow-hidden bg-muted lg:aspect-auto"
                        >
                            <img
                                v-if="featuredPost.cover"
                                :src="featuredPost.cover"
                                :alt="featuredPost.title"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                            />
                            <div
                                v-else
                                class="flex h-full min-h-64 items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                            >
                                <span
                                    class="text-7xl font-bold text-primary/15"
                                >
                                    {{ featuredPost.title.charAt(0) }}
                                </span>
                            </div>

                            <!-- Badge -->
                            <div class="absolute top-5 left-5">
                                <span
                                    class="rounded-full bg-primary px-3 py-1.5 text-xs font-bold text-primary-foreground"
                                >
                                    Mais recente
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div
                            class="flex flex-col justify-center gap-5 p-10 lg:p-14"
                        >
                            <!-- Meta -->
                            <div
                                class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-muted-foreground"
                            >
                                <span
                                    v-if="featuredPost.category"
                                    class="font-semibold text-primary"
                                >
                                    {{ featuredPost.category.name }}
                                </span>
                                <span
                                    v-if="featuredPost.category"
                                    class="text-border"
                                    >·</span
                                >
                                <span class="flex items-center gap-1.5">
                                    <Calendar class="h-3.5 w-3.5" />
                                    {{
                                        formatDate(
                                            featuredPost.published_at ?? '',
                                        )
                                    }}
                                </span>
                                <span class="text-border">·</span>
                                <span class="flex items-center gap-1.5">
                                    <Clock class="h-3.5 w-3.5" />
                                    {{ featuredPost.reading_time }} min
                                </span>
                            </div>

                            <div>
                                <h2
                                    class="text-3xl leading-tight font-bold tracking-tight md:text-4xl"
                                >
                                    {{ featuredPost.title }}
                                </h2>
                                <p
                                    v-if="featuredPost.excerpt"
                                    class="mt-4 line-clamp-3 text-lg leading-relaxed text-muted-foreground"
                                >
                                    {{ featuredPost.excerpt }}
                                </p>
                            </div>

                            <!-- Tags -->
                            <div
                                v-if="featuredPost.tags?.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="(tag, i) in featuredPost.tags.slice(
                                        0,
                                        4,
                                    )"
                                    :key="i"
                                    class="rounded-full bg-muted px-2.5 py-1 text-xs font-medium text-muted-foreground"
                                >
                                    #{{ tagName(tag) }}
                                </span>
                            </div>

                            <!-- Author + CTA row -->
                            <div
                                class="flex items-center justify-between border-t border-border pt-2"
                            >
                                <div
                                    v-if="featuredPost.author"
                                    class="flex items-center gap-2.5"
                                >
                                    <img
                                        v-if="featuredPost.author.avatar"
                                        :src="featuredPost.author.avatar"
                                        :alt="featuredPost.author.name"
                                        class="h-8 w-8 rounded-full object-cover ring-1 ring-border"
                                    />
                                    <span class="text-sm font-medium">{{
                                        featuredPost.author.name
                                    }}</span>
                                </div>
                                <span
                                    class="flex items-center gap-1.5 text-sm font-bold text-primary transition-all group-hover:gap-2.5"
                                >
                                    Ler artigo
                                    <ArrowRight
                                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    />
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- ── Grid de posts ───────────────────────────────────── -->
                <div
                    v-if="restPosts.length"
                    class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="(post, i) in restPosts"
                        :key="i"
                        class="reveal group flex flex-col"
                        :style="{ '--reveal-delay': `${(i % 3) * 70}ms` }"
                    >
                        <!-- Thumbnail -->
                        <Link
                            :href="blog.show(post.slug)"
                            class="relative mb-5 block aspect-video overflow-hidden rounded-2xl bg-muted"
                            tabindex="-1"
                            aria-hidden="true"
                        >
                            <img
                                v-if="post.cover"
                                :src="post.cover"
                                :alt="post.title"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                            />
                            <div
                                v-else
                                class="flex h-full items-center justify-center bg-gradient-to-br from-primary/8 to-primary/3"
                            >
                                <span
                                    class="text-5xl font-bold text-primary/15"
                                >
                                    {{ post.title.charAt(0) }}
                                </span>
                            </div>

                            <!-- Category badge -->
                            <div
                                v-if="post.category"
                                class="absolute top-3 left-3"
                            >
                                <span
                                    class="rounded-full bg-background/85 px-2.5 py-1 text-[10px] font-bold tracking-wide uppercase backdrop-blur-sm"
                                >
                                    {{ post.category.name }}
                                </span>
                            </div>
                        </Link>

                        <!-- Content -->
                        <div class="flex flex-1 flex-col gap-3">
                            <!-- Meta row -->
                            <div
                                class="flex flex-wrap items-center gap-x-2.5 gap-y-1 text-xs text-muted-foreground"
                            >
                                <span class="flex items-center gap-1">
                                    <Calendar class="h-3 w-3" />
                                    {{ formatDate(post.published_at ?? '') }}
                                </span>
                                <span class="text-border">·</span>
                                <span class="flex items-center gap-1">
                                    <Clock class="h-3 w-3" />
                                    {{ post.reading_time }} min de leitura
                                </span>
                            </div>

                            <!-- Title -->
                            <h2
                                class="text-xl leading-snug font-bold tracking-tight transition-colors group-hover:text-primary"
                            >
                                <Link :href="blog.show(post.slug)">
                                    {{ post.title }}
                                </Link>
                            </h2>

                            <!-- Excerpt -->
                            <p
                                v-if="post.excerpt"
                                class="line-clamp-2 flex-1 text-sm leading-relaxed text-muted-foreground"
                            >
                                {{ post.excerpt }}
                            </p>

                            <!-- Tags -->
                            <div
                                v-if="post.tags?.length"
                                class="flex flex-wrap gap-1.5"
                            >
                                <span
                                    v-for="(tag, i) in post.tags.slice(0, 3)"
                                    :key="i"
                                    class="rounded-full bg-muted px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
                                >
                                    #{{ tagName(tag) }}
                                </span>
                            </div>

                            <!-- Footer -->
                            <div
                                class="mt-auto flex items-center justify-between border-t border-border/60 pt-4"
                            >
                                <!-- Author -->
                                <div
                                    v-if="post.author"
                                    class="flex items-center gap-2"
                                >
                                    <img
                                        v-if="post.author.avatar"
                                        :src="post.author.avatar"
                                        :alt="post.author.name"
                                        class="h-6 w-6 rounded-full object-cover ring-1 ring-border"
                                    />
                                    <span
                                        class="text-xs font-medium text-muted-foreground"
                                        >{{ post.author.name }}</span
                                    >
                                </div>
                                <Link
                                    :href="blog.show(post.slug)"
                                    class="flex items-center gap-1 text-xs font-bold text-primary opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    Ler
                                    <ArrowRight
                                        class="h-3 w-3 transition-transform group-hover:translate-x-0.5"
                                    />
                                </Link>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- ── Empty state ─────────────────────────────────────── -->
                <div
                    v-else-if="!featuredPost"
                    class="reveal flex flex-col items-center gap-4 py-32 text-center"
                >
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-3xl bg-muted"
                    >
                        <Tag class="h-8 w-8 text-muted-foreground" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">
                            Nenhum post encontrado.
                        </h3>
                        <p class="mt-2 text-muted-foreground">
                            Fique atento, novidades em breve!
                        </p>
                    </div>
                </div>

                <!-- ── Pagination ──────────────────────────────────────── -->
                <div
                    v-if="posts.last_page > 1"
                    class="reveal mt-16 flex flex-col items-center gap-6"
                >
                    <!-- Page numbers -->
                    <div class="flex items-center gap-1.5">
                        <button
                            :disabled="!posts.prev_page_url"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-border transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                            aria-label="Página anterior"
                            @click="goTo(posts.prev_page_url)"
                        >
                            <ArrowLeft class="h-4 w-4" />
                        </button>

                        <template
                            v-for="link in posts.links.slice(1, -1)"
                            :key="link.label"
                        >
                            <!-- Ellipsis -->
                            <span
                                v-if="link.label === '...'"
                                class="flex h-10 w-10 items-center justify-center text-sm text-muted-foreground"
                            >
                                …
                            </span>
                            <!-- Page number -->
                            <button
                                v-else
                                :disabled="!link.url"
                                class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition-all"
                                :class="
                                    link.active
                                        ? 'bg-primary text-primary-foreground'
                                        : 'border border-border hover:bg-muted disabled:opacity-40'
                                "
                                @click="goTo(link.url)"
                            >
                                <span v-html="link.label" />
                            </button>
                        </template>

                        <button
                            :disabled="!posts.next_page_url"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-border transition-all hover:bg-muted disabled:pointer-events-none disabled:opacity-40"
                            aria-label="Próxima página"
                            @click="goTo(posts.next_page_url)"
                        >
                            <ArrowRight class="h-4 w-4" />
                        </button>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Página
                        <strong class="text-foreground">{{
                            posts.current_page
                        }}</strong>
                        de
                        <strong class="text-foreground">{{
                            posts.last_page
                        }}</strong>
                        ·
                        <strong class="text-foreground">{{
                            posts.total
                        }}</strong>
                        artigos
                    </p>
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
                                    Gostou do que leu?
                                </h2>
                                <p
                                    class="mt-3 text-lg leading-relaxed opacity-80"
                                >
                                    Se algum artigo fez sentido pro seu
                                    contexto, bora conversar sobre como
                                    transformar isso em código.
                                </p>
                            </div>
                            <div
                                class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                            >
                                <Link
                                    :href="contact().url"
                                    class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                                >
                                    Fala com a gente
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
