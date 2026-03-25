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
import { computed, onMounted, onUnmounted, ref } from 'vue';

import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import { contact } from '@/routes/public';
import blog from '@/routes/public/blog';
import type { PaginatedResponse } from '@/types';
import type { PublicPostSummaryData, SeoData } from '@/types/public';

const props = defineProps<{
    posts: PaginatedResponse<PublicPostSummaryData>;
    seo?: SeoData;
}>();

// ─── Tag name ─────────────────────────────────────────────────────────────────
//
// No payload real tag.name sempre chega como string.
// Mantemos o fallback multilingual por segurança, mas sem complexidade desnecessária.
//
const tagName = (tag: any): string => {
    if (typeof tag.name === 'string') {
        return tag.name;
    }

    return tag.name?.pt ?? tag.name?.en ?? Object.values(tag.name)[0] ?? '';
};

// ─── Featured: primeiro post só na página 1 ───────────────────────────────────
const featuredPost = computed(() =>
    props.posts.meta.current_page === 1 ? (props.posts.data[0] ?? null) : null,
);

const restPosts = computed(() =>
    featuredPost.value ? props.posts.data.slice(1) : props.posts.data,
);

// ─── Categorias únicas para o filtro ─────────────────────────────────────────
//
// Calculado sobre todos os posts da página (incluindo o featured).
// Só exibe o filtro se há 2+ categorias distintas.
//
const categories = computed(() => {
    const seen = new Set<string>();
    const list: Array<{ name: string; slug: string }> = [];

    props.posts.data.forEach((p) => {
        if (p.category && !seen.has(p.category.slug)) {
            seen.add(p.category.slug);
            list.push(p.category);
        }
    });

    return list.sort((a, b) => a.name.localeCompare(b.name));
});

const showFilter = computed(() => categories.value.length >= 2);

// ─── Filtro ativo ─────────────────────────────────────────────────────────────
//
// Quando ativo:
//   - o featured fica visível apenas se sua categoria bate
//   - restPosts é filtrado da mesma forma
//
const activeCategory = ref<string | null>(null);

const isFiltering = computed(() => activeCategory.value !== null);

const visibleFeatured = computed(() => {
    if (!featuredPost.value) {
        return null;
    }

    if (!isFiltering.value) {
        return featuredPost.value;
    }

    return featuredPost.value.category?.slug === activeCategory.value
        ? featuredPost.value
        : null;
});

const visibleRest = computed(() => {
    if (!isFiltering.value) {
        return restPosts.value;
    }

    return restPosts.value.filter(
        (p) => p.category?.slug === activeCategory.value,
    );
});

// Contagem por categoria slug (sobre todos os posts da página)
const categoryCounts = computed(() => {
    const counts = new Map<string, number>();
    props.posts.data.forEach((p) => {
        if (p.category) {
            counts.set(p.category.slug, (counts.get(p.category.slug) ?? 0) + 1);
        }
    });

    return counts;
});

// ─── Paginação ────────────────────────────────────────────────────────────────
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
    <SeoHead v-bind="seo" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-32">
            <div class="mx-auto max-w-7xl">
                <!-- ════════════════════════════════════════════════════════
                     HEADER
                ════════════════════════════════════════════════════════ -->
                <div
                    class="reveal mb-16 flex flex-col gap-6 md:flex-row md:items-end md:justify-between"
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

                    <div
                        class="flex shrink-0 flex-col items-start gap-1 md:items-end"
                    >
                        <div
                            class="mb-1 flex items-center gap-2 text-muted-foreground md:justify-end"
                        >
                            <Rss class="h-4 w-4 text-primary" />
                            <span
                                class="text-xs font-semibold tracking-wider uppercase"
                            >
                                Publicações
                            </span>
                        </div>
                        <p class="text-5xl leading-none font-bold tabular-nums">
                            {{ posts.meta.total
                            }}<span
                                v-if="posts.meta.total > 0"
                                class="text-primary"
                                >+</span
                            >
                        </p>
                        <p class="text-sm text-muted-foreground">
                            Artigos publicados
                        </p>
                    </div>
                </div>

                <!-- ════════════════════════════════════════════════════════
                     FILTRO POR CATEGORIA
                     Só aparece se há 2+ categorias distintas na página.
                     Quando ativo, filtra tanto o featured quanto o grid.
                ════════════════════════════════════════════════════════ -->
                <div
                    v-if="showFilter"
                    class="reveal mb-12 flex flex-wrap items-center gap-2"
                    role="group"
                    aria-label="Filtrar por categoria"
                >
                    <button
                        class="inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-sm font-medium transition-all"
                        :class="
                            !isFiltering
                                ? 'border-primary bg-primary/10 text-primary'
                                : 'border-border bg-background text-muted-foreground hover:text-foreground'
                        "
                        @click="activeCategory = null"
                    >
                        Todos
                        <span class="tabular-nums opacity-60">
                            ({{ posts.data.length }})
                        </span>
                    </button>

                    <button
                        v-for="cat in categories"
                        :key="cat.slug"
                        class="inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-sm font-medium transition-all"
                        :class="
                            activeCategory === cat.slug
                                ? 'border-primary bg-primary/10 text-primary'
                                : 'border-border bg-background text-muted-foreground hover:text-foreground'
                        "
                        @click="activeCategory = cat.slug"
                    >
                        {{ cat.name }}
                        <span class="tabular-nums opacity-60">
                            ({{ categoryCounts.get(cat.slug) ?? 0 }})
                        </span>
                    </button>
                </div>

                <!-- ════════════════════════════════════════════════════════
                     FEATURED POST
                     Some quando o filtro ativo não bate com a categoria dele.
                ════════════════════════════════════════════════════════ -->
                <div v-if="visibleFeatured" class="reveal mb-20">
                    <Link
                        :href="blog.show(visibleFeatured.slug)"
                        class="group relative grid grid-cols-1 overflow-hidden rounded-[2rem] border border-border bg-card transition-all duration-500 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/8 lg:grid-cols-2"
                    >
                        <!-- Imagem -->
                        <div
                            class="relative aspect-video overflow-hidden bg-muted lg:aspect-auto"
                        >
                            <img
                                v-if="visibleFeatured.media?.cover"
                                :src="visibleFeatured.media.cover.url"
                                :alt="visibleFeatured.title"
                                fetchpriority="high"
                                loading="eager"
                                decoding="sync"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                            />
                            <div
                                v-else
                                class="flex h-full min-h-64 items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                            >
                                <span
                                    class="text-7xl font-bold text-primary/15"
                                >
                                    {{ visibleFeatured.title.charAt(0) }}
                                </span>
                            </div>

                            <!-- Badge "Mais recente" -->
                            <div class="absolute top-5 left-5">
                                <span
                                    class="rounded-full bg-primary px-3 py-1.5 text-xs font-bold text-primary-foreground"
                                >
                                    Mais recente
                                </span>
                            </div>
                        </div>

                        <!-- Conteúdo -->
                        <div
                            class="flex flex-col justify-center gap-5 p-10 lg:p-14"
                        >
                            <!-- Meta -->
                            <div
                                class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-muted-foreground"
                            >
                                <span
                                    v-if="visibleFeatured.category"
                                    class="font-semibold text-primary"
                                >
                                    {{ visibleFeatured.category.name }}
                                </span>
                                <span
                                    v-if="visibleFeatured.category"
                                    class="text-border"
                                    >·</span
                                >
                                <span class="flex items-center gap-1.5">
                                    <Calendar class="h-3.5 w-3.5" />
                                    {{
                                        formatDate(
                                            visibleFeatured.published_at ?? '',
                                        )
                                    }}
                                </span>
                                <span class="text-border">·</span>
                                <span class="flex items-center gap-1.5">
                                    <Clock class="h-3.5 w-3.5" />
                                    {{ visibleFeatured.reading_time }} min de
                                    leitura
                                </span>
                            </div>

                            <div>
                                <h2
                                    class="text-3xl leading-tight font-bold tracking-tight md:text-4xl"
                                >
                                    {{ visibleFeatured.title }}
                                </h2>
                                <p
                                    v-if="visibleFeatured.excerpt"
                                    class="mt-4 line-clamp-3 text-lg leading-relaxed text-muted-foreground"
                                >
                                    {{ visibleFeatured.excerpt }}
                                </p>
                            </div>

                            <!-- Tags
                                 Limitadas a 3 para não sobrecarregar visualmente.
                                 Tags longas ficam truncadas com max-width.
                            -->
                            <div
                                v-if="visibleFeatured.tags?.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="(
                                        tag, i
                                    ) in visibleFeatured.tags.slice(0, 3)"
                                    :key="i"
                                    class="max-w-[160px] truncate rounded-full bg-muted px-2.5 py-1 text-xs font-medium text-muted-foreground"
                                    :title="tagName(tag)"
                                >
                                    #{{ tagName(tag) }}
                                </span>
                                <!-- "+N mais" quando há tags sobrando -->
                                <span
                                    v-if="visibleFeatured.tags.length > 3"
                                    class="rounded-full bg-muted px-2.5 py-1 text-xs font-medium text-muted-foreground"
                                >
                                    +{{ visibleFeatured.tags.length - 3 }}
                                </span>
                            </div>

                            <!-- Autor + CTA -->
                            <div
                                class="flex items-center justify-between border-t border-border pt-4"
                            >
                                <div
                                    v-if="visibleFeatured.author"
                                    class="flex items-center gap-2.5"
                                >
                                    <img
                                        v-if="visibleFeatured.author.avatar_url"
                                        :src="visibleFeatured.author.avatar_url"
                                        :alt="visibleFeatured.author.name"
                                        class="h-8 w-8 rounded-full object-cover ring-1 ring-border"
                                    />
                                    <span class="text-sm font-medium">
                                        {{ visibleFeatured.author.name }}
                                    </span>
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

                <!-- ════════════════════════════════════════════════════════
                     GRID DE POSTS
                     Quando o filtro some o featured mas há posts no grid,
                     exibimos o cabeçalho de resultado filtrado.
                ════════════════════════════════════════════════════════ -->

                <!-- Cabeçalho de resultado filtrado -->
                <div
                    v-if="isFiltering"
                    class="reveal mb-8 flex items-center justify-between"
                >
                    <p class="text-sm text-muted-foreground">
                        <strong class="text-foreground">
                            {{ (visibleFeatured ? 1 : 0) + visibleRest.length }}
                        </strong>
                        {{
                            (visibleFeatured ? 1 : 0) + visibleRest.length === 1
                                ? 'artigo encontrado'
                                : 'artigos encontrados'
                        }}
                        em
                        <strong class="text-primary">
                            {{
                                categories.find(
                                    (c) => c.slug === activeCategory,
                                )?.name
                            }}
                        </strong>
                    </p>
                    <button
                        class="text-sm font-semibold text-muted-foreground transition-colors hover:text-foreground"
                        @click="activeCategory = null"
                    >
                        Limpar filtro ×
                    </button>
                </div>

                <div
                    v-if="visibleRest.length"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="(post, i) in visibleRest"
                        :key="post.slug"
                        class="reveal group flex flex-col overflow-hidden rounded-3xl border border-border/70 bg-card transition-all duration-300 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :style="{ '--reveal-delay': `${(i % 3) * 70}ms` }"
                    >
                        <!-- Thumbnail -->
                        <Link
                            :href="blog.show(post.slug)"
                            class="relative block aspect-video overflow-hidden bg-muted"
                            tabindex="-1"
                            aria-hidden="true"
                        >
                            <img
                                v-if="post.media?.cover"
                                :src="post.media.cover.url"
                                :alt="post.title"
                                loading="lazy"
                                decoding="async"
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

                        <!-- Conteúdo do card -->
                        <div class="flex flex-1 flex-col gap-3 p-6">
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
                                    {{ post.reading_time }} min
                                </span>
                            </div>

                            <!-- Título -->
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

                            <!-- Tags — max 2 no card pequeno para não poluir -->
                            <div
                                v-if="post.tags?.length"
                                class="flex flex-wrap gap-1.5"
                            >
                                <span
                                    v-for="(tag, i) in post.tags.slice(0, 2)"
                                    :key="i"
                                    class="max-w-[120px] truncate rounded-full bg-muted px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
                                    :title="tagName(tag)"
                                >
                                    #{{ tagName(tag) }}
                                </span>
                                <span
                                    v-if="post.tags.length > 2"
                                    class="rounded-full bg-muted px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
                                >
                                    +{{ post.tags.length - 2 }}
                                </span>
                            </div>

                            <!-- Footer: autor + link "Ler" -->
                            <div
                                class="mt-auto flex items-center justify-between border-t border-border/60 pt-4"
                            >
                                <div
                                    v-if="post.author"
                                    class="flex items-center gap-2"
                                >
                                    <img
                                        v-if="post.author.avatar_url"
                                        :src="post.author.avatar_url"
                                        :alt="post.author.name"
                                        class="h-6 w-6 rounded-full object-cover ring-1 ring-border"
                                    />
                                    <span
                                        class="text-xs font-medium text-muted-foreground"
                                    >
                                        {{ post.author.name }}
                                    </span>
                                </div>
                                <!--
                                    "Ler" sempre visível (não só no hover):
                                    mobile não tem hover state, então esconder com opacity-0
                                    tornava o CTA inacessível em touch.
                                -->
                                <Link
                                    :href="blog.show(post.slug)"
                                    class="flex items-center gap-1 text-xs font-bold text-primary"
                                    aria-label="Ler artigo: {{ post.title }}"
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

                <!-- ── Empty state do filtro ───────────────────────────── -->
                <div
                    v-else-if="isFiltering && !visibleFeatured"
                    class="reveal flex flex-col items-center gap-4 rounded-2xl border border-dashed border-border py-16 text-center"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-muted"
                    >
                        <Tag class="h-5 w-5 text-muted-foreground" />
                    </div>
                    <div>
                        <p class="font-medium text-foreground">
                            Nenhum artigo em
                            <strong class="text-primary">
                                {{
                                    categories.find(
                                        (c) => c.slug === activeCategory,
                                    )?.name
                                }} </strong
                            >.
                        </p>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Tente outra categoria ou navegue para outra página.
                        </p>
                    </div>
                    <button
                        class="text-sm font-semibold text-primary hover:underline"
                        @click="activeCategory = null"
                    >
                        Ver todos os artigos
                    </button>
                </div>

                <!-- ── Empty state global ─────────────────────────────── -->
                <div
                    v-else-if="!visibleFeatured && !visibleRest.length"
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

                <!-- ════════════════════════════════════════════════════════
                     PAGINAÇÃO COM NÚMEROS
                     O filtro é client-side (página atual apenas).
                     Quando filtrando, ocultamos a paginação para evitar
                     confusão: o usuário não sabe que outras páginas podem
                     ter mais posts da categoria filtrada.
                ════════════════════════════════════════════════════════ -->
                <div
                    v-if="posts.last_page > 1 && !isFiltering"
                    class="reveal mt-16 flex flex-col items-center gap-6"
                >
                    <!-- Números de página -->
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
                            <span
                                v-if="link.label === '...'"
                                class="flex h-10 w-10 items-center justify-center text-sm text-muted-foreground"
                            >
                                …
                            </span>
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

                <!--
                    Aviso quando filtrando e há mais páginas:
                    orienta o usuário a limpar o filtro para paginar.
                -->
                <p
                    v-if="posts.last_page > 1 && isFiltering"
                    class="reveal mt-10 text-center text-sm text-muted-foreground"
                >
                    O filtro age somente na página atual.
                    <button
                        class="font-semibold text-primary hover:underline"
                        @click="activeCategory = null"
                    >
                        Limpar filtro
                    </button>
                    para navegar entre páginas.
                </p>

                <!-- ════════════════════════════════════════════════════════
                     CTA FINAL
                ════════════════════════════════════════════════════════ -->
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
    /* opacity: 0; */
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
