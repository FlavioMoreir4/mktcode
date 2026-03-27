<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    ArrowUpRight,
    Clock,
    Globe,
    Layers,
    BookOpen,
    MapPin,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted } from 'vue';
import {
    GitHubIcon,
    GitLabIcon,
    BitbucketIcon,
    NpmIcon,
    StackOverflowIcon,
    ProductHuntIcon,
    XIcon,
    InstagramIcon,
    FacebookIcon,
    ThreadsIcon,
    BlueskyIcon,
    MastodonIcon,
    SnapchatIcon,
    PinterestIcon,
    TikTokIcon,
    RedditIcon,
    YouTubeIcon,
    TwitchIcon,
    SpotifyIcon,
    DiscordIcon,
    TelegramIcon,
    WhatsAppIcon,
    MediumIcon,
    DevDottoIcon,
    HashnodeIcon,
    SubstackIcon,
    DribbbleIcon,
    BehanceIcon,
    FigmaIcon,
    PatreonIcon,
    KoFiIcon,
} from 'vue3-simple-icons';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import { contact } from '@/routes/public';
import blog from '@/routes/public/blog';
import type { PublicProfileViewData } from '@/types';

const props = defineProps<{
    user: PublicProfileViewData;
}>();

// ─── Social: Simple Icons mapping ────────────────────────────────────────────
const socialMap: Record<string, { icon: unknown; label: string }> = {
    github: { icon: GitHubIcon, label: 'GitHub' },
    gitlab: { icon: GitLabIcon, label: 'GitLab' },
    bitbucket: { icon: BitbucketIcon, label: 'Bitbucket' },
    npm: { icon: NpmIcon, label: 'npm' },
    stackoverflow: { icon: StackOverflowIcon, label: 'Stack Overflow' },
    linkedin: { icon: Globe, label: 'LinkedIn' },
    producthunt: { icon: ProductHuntIcon, label: 'Product Hunt' },
    x: { icon: XIcon, label: 'X' },
    twitter: { icon: XIcon, label: 'Twitter' },
    instagram: { icon: InstagramIcon, label: 'Instagram' },
    facebook: { icon: FacebookIcon, label: 'Facebook' },
    threads: { icon: ThreadsIcon, label: 'Threads' },
    bluesky: { icon: BlueskyIcon, label: 'Bluesky' },
    mastodon: { icon: MastodonIcon, label: 'Mastodon' },
    snapchat: { icon: SnapchatIcon, label: 'Snapchat' },
    pinterest: { icon: PinterestIcon, label: 'Pinterest' },
    tiktok: { icon: TikTokIcon, label: 'TikTok' },
    reddit: { icon: RedditIcon, label: 'Reddit' },
    youtube: { icon: YouTubeIcon, label: 'YouTube' },
    twitch: { icon: TwitchIcon, label: 'Twitch' },
    spotify: { icon: SpotifyIcon, label: 'Spotify' },
    discord: { icon: DiscordIcon, label: 'Discord' },
    telegram: { icon: TelegramIcon, label: 'Telegram' },
    whatsapp: { icon: WhatsAppIcon, label: 'WhatsApp' },
    medium: { icon: MediumIcon, label: 'Medium' },
    devto: { icon: DevDottoIcon, label: 'Dev.to' },
    'dev.to': { icon: DevDottoIcon, label: 'Dev.to' },
    hashnode: { icon: HashnodeIcon, label: 'Hashnode' },
    substack: { icon: SubstackIcon, label: 'Substack' },
    dribbble: { icon: DribbbleIcon, label: 'Dribbble' },
    behance: { icon: BehanceIcon, label: 'Behance' },
    figma: { icon: FigmaIcon, label: 'Figma' },
    patreon: { icon: PatreonIcon, label: 'Patreon' },
    kofi: { icon: KoFiIcon, label: 'Ko-fi' },
    'ko-fi': { icon: KoFiIcon, label: 'Ko-fi' },
};

const normalizePlatform = (platform: string) =>
    platform.toLowerCase().replace(/[\s_]/g, '');

const getSocialEntry = (platform: string) =>
    socialMap[normalizePlatform(platform)] ?? null;

// ─── Três zonas de social links ───────────────────────────────────────────────
// Zona 1: botões de destaque no header (ao lado do botão "Contratar")
const featuredLinks = computed(
    () => props.user.social?.filter((l) => l.featured) ?? [],
);

// Zona 2: pills inline (não-featured E não-stacked)
const pillLinks = computed(
    () =>
        props.user.social?.filter((l) => !l.featured && !l.stack_on_mobile) ??
        [],
);

// Zona 3: links empilhados — ocultos no mobile do bloco de pills, exibidos em lista própria
const stackedLinks = computed(
    () =>
        props.user.social?.filter((l) => !l.featured && l.stack_on_mobile) ??
        [],
);

const hasPillLinks = computed(() => pillLinks.value.length > 0);
const hasStackedLinks = computed(() => stackedLinks.value.length > 0);

// ─── Bio ──────────────────────────────────────────────────────────────────────
const bioFirstParagraph = computed(() => {
    if (!props.user.bio) {
        return null;
    }

    const match = props.user.bio.match(/<p>([\s\S]*?)<\/p>/);

    return match ? `<p>${match[1]}</p>` : props.user.bio;
});

// ─── Has content ──────────────────────────────────────────────────────────────
const hasProjects = computed(
    () => (props.user.projects?.data?.length ?? 0) > 0,
);
const hasPosts = computed(() => (props.user.posts?.data?.length ?? 0) > 0);

const featuredProject = computed(
    () => props.user.projects?.data?.find((p) => p.featured) ?? null,
);
const regularProjects = computed(
    () => props.user.projects?.data?.filter((p) => !p.featured) ?? [],
);

const stats = computed(() => [
    {
        value: props.user.projects_count,
        label: 'Projetos',
        icon: Layers,
    },
    {
        value: props.user.posts_count,
        label: 'Artigos',
        icon: BookOpen,
    },
]);

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
    <SeoHead />

    <PublicLayout>
        <!-- ══════════════════════════════════════════════
             COVER + IDENTITY
        ══════════════════════════════════════════════ -->
        <section class="relative pt-20">
            <!-- Cover -->
            <div class="relative h-52 w-full overflow-hidden bg-muted md:h-72">
                <img
                    v-if="user.cover"
                    :src="user.cover"
                    alt=""
                    aria-hidden="true"
                    class="h-full w-full object-cover"
                />
                <div
                    v-else
                    class="h-full w-full bg-gradient-to-br from-primary/20 via-primary/5 to-transparent"
                />
                <!-- Gradiente mais suave → apenas bottom fade, sem cortar o cover -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent"
                />
            </div>

            <div class="mx-auto max-w-7xl px-6">
                <div class="relative -mt-16 md:-mt-24">
                    <!-- ── Linha: Avatar + CTAs ──────────────────────────── -->
                    <div class="flex items-end justify-between gap-4">
                        <!-- Avatar -->
                        <div class="relative shrink-0">
                            <div
                                class="h-28 w-28 overflow-hidden rounded-2xl border-4 border-background bg-muted shadow-xl ring-1 ring-border/50 md:h-40 md:w-40"
                            >
                                <img
                                    v-if="user.avatar"
                                    :src="user.avatar"
                                    :alt="user.name"
                                    class="h-full w-full object-cover"
                                />
                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center bg-primary/10 text-4xl font-bold text-primary"
                                >
                                    {{ user.name.charAt(0) }}
                                </div>
                            </div>
                        </div>

                        <!-- CTAs: featured links + botão Contratar -->
                        <div class="flex items-center gap-2.5 pb-2">
                            <!-- Zona 1: featured links -->
                            <template
                                v-for="link in featuredLinks"
                                :key="link.platform"
                            >
                                <a
                                    :href="link.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    :aria-label="
                                        getSocialEntry(link.platform)?.label ??
                                        link.platform
                                    "
                                    class="flex items-center gap-2 rounded-full border border-border bg-background px-4 py-2 text-sm font-semibold text-foreground shadow-sm transition-all hover:border-primary/30 hover:bg-primary/5 hover:text-primary active:scale-[0.97]"
                                >
                                    <component
                                        :is="
                                            getSocialEntry(link.platform)
                                                ?.icon ?? Globe
                                        "
                                        class="h-4 w-4 shrink-0"
                                    />
                                    <span
                                        v-if="!link.icon_only"
                                        class="hidden sm:inline"
                                    >
                                        {{
                                            getSocialEntry(link.platform)
                                                ?.label ?? link.platform
                                        }}
                                    </span>
                                </a>
                            </template>

                            <!-- Botão principal -->
                            <Link
                                :href="contact().url"
                                class="rounded-full bg-primary px-5 py-2 text-sm font-bold text-primary-foreground shadow-md transition-all hover:opacity-90 hover:shadow-primary/25 active:scale-[0.97]"
                            >
                                Contratar
                            </Link>
                        </div>
                    </div>

                    <!-- ── Identidade ─────────────────────────────────────── -->
                    <div class="mt-5 space-y-4">
                        <!-- Nome + título + localização -->
                        <div>
                            <h1
                                class="text-3xl font-bold tracking-tight md:text-4xl"
                            >
                                {{ user.name }}
                            </h1>
                            <div
                                class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm"
                            >
                                <span
                                    v-if="user.title"
                                    class="font-semibold text-primary/80"
                                    >{{ user.title }}</span
                                >
                                <span
                                    v-if="user.title && user.location"
                                    class="text-border"
                                    >·</span
                                >
                                <span
                                    v-if="user.location"
                                    class="flex items-center gap-1.5 text-muted-foreground"
                                >
                                    <MapPin class="h-3.5 w-3.5 shrink-0" />
                                    {{ user.location }}
                                </span>
                            </div>
                        </div>

                        <!-- ── Zona 2: Pills inline (não-featured, não-stacked) ── -->
                        <div v-if="hasPillLinks" class="flex flex-wrap gap-2">
                            <template
                                v-for="link in pillLinks"
                                :key="link.platform"
                            >
                                <a
                                    :href="link.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    :aria-label="
                                        getSocialEntry(link.platform)?.label ??
                                        link.platform
                                    "
                                    :title="
                                        getSocialEntry(link.platform)?.label ??
                                        link.platform
                                    "
                                    class="group flex items-center gap-1.5 rounded-full border border-border bg-background px-3 py-1.5 text-xs font-medium text-muted-foreground transition-all hover:border-primary/30 hover:bg-primary/5 hover:text-primary active:scale-[0.96]"
                                >
                                    <component
                                        :is="
                                            getSocialEntry(link.platform)
                                                ?.icon ?? Globe
                                        "
                                        class="h-3.5 w-3.5 shrink-0"
                                    />
                                    <!-- icon_only: sem label em nenhuma viewport -->
                                    <span
                                        v-if="!link.icon_only"
                                        class="hidden sm:inline"
                                    >
                                        {{
                                            getSocialEntry(link.platform)
                                                ?.label ?? link.platform
                                        }}
                                    </span>
                                </a>
                            </template>
                        </div>

                        <!-- ── Zona 3: Links empilhados (stack_on_mobile) ────── -->
                        <!--
                            Esses links ficam FORA dos pills e são exibidos como
                            linhas clicáveis — útil para links "importantes demais
                            para serem só um ícone" (ex: LinkedIn, site pessoal).
                            Em desktop aparecem como lista compacta;
                            em mobile ocupam linha inteira com boa área de toque.
                        -->
                        <div
                            v-if="hasStackedLinks"
                            class="flex flex-col gap-1.5 sm:flex-row sm:flex-wrap sm:gap-2"
                        >
                            <a
                                v-for="link in stackedLinks"
                                :key="link.platform"
                                :href="link.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                :aria-label="
                                    getSocialEntry(link.platform)?.label ??
                                    link.platform
                                "
                                class="group flex w-full items-center justify-between gap-3 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium text-foreground transition-all hover:border-primary/30 hover:bg-primary/5 hover:text-primary active:scale-[0.98] sm:w-auto sm:justify-start sm:rounded-full sm:px-3 sm:py-1.5 sm:text-xs"
                            >
                                <span
                                    class="flex items-center gap-2.5 sm:gap-1.5"
                                >
                                    <component
                                        :is="
                                            getSocialEntry(link.platform)
                                                ?.icon ?? Globe
                                        "
                                        class="h-4 w-4 shrink-0 sm:h-3.5 sm:w-3.5"
                                    />
                                    {{
                                        getSocialEntry(link.platform)?.label ??
                                        link.platform
                                    }}
                                </span>
                                <!-- Seta apenas no mobile (linha inteira) -->
                                <ArrowUpRight
                                    class="h-4 w-4 shrink-0 text-muted-foreground/40 transition-all group-hover:text-primary sm:hidden"
                                />
                            </a>
                        </div>

                        <!-- Stats -->
                        <div
                            class="flex flex-wrap gap-6 border-t border-border pt-5"
                        >
                            <div
                                v-for="stat in stats"
                                :key="stat.label"
                                class="flex items-center gap-2.5"
                            >
                                <component
                                    :is="stat.icon"
                                    class="h-4 w-4 text-muted-foreground"
                                />
                                <span
                                    class="text-lg leading-none font-bold tabular-nums"
                                >
                                    {{ stat.value }}
                                </span>
                                <span class="text-sm text-muted-foreground">
                                    {{ stat.label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════════════ -->
        <section class="pt-14 pb-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                    <!-- ── Sidebar ───────────────────────────────────────── -->
                    <aside class="space-y-8 lg:col-span-4">
                        <!-- Bio -->
                        <div class="reveal">
                            <h3
                                class="mb-3 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Sobre
                            </h3>
                            <div
                                v-if="bioFirstParagraph"
                                class="prose prose-sm max-w-none text-muted-foreground dark:prose-invert prose-p:leading-relaxed prose-a:font-semibold prose-a:text-primary prose-a:no-underline hover:prose-a:underline"
                                v-html="bioFirstParagraph"
                            />
                            <p
                                v-else
                                class="text-sm text-muted-foreground/60 italic"
                            >
                                Nenhuma biografia adicionada ainda.
                            </p>
                        </div>

                        <!-- Stack -->
                        <div class="reveal" v-if="user.skills">
                            <h3
                                class="mb-3 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Stack principal
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="tech in user.skills?.split(',')"
                                    :key="tech"
                                    class="rounded-lg border border-border bg-muted/50 px-2.5 py-1 text-xs font-medium text-muted-foreground"
                                >
                                    {{ tech }}
                                </span>
                            </div>
                        </div>

                        <!-- CTA card -->
                        <div
                            class="reveal rounded-2xl border border-border bg-card p-6"
                        >
                            <div class="mb-1 flex items-center gap-2">
                                <!-- Indicador de disponibilidade -->
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"
                                    />
                                    <span
                                        class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"
                                    />
                                </span>
                                <h3 class="font-bold">
                                    Disponível para projetos
                                </h3>
                            </div>
                            <p
                                class="mb-5 text-sm leading-relaxed text-muted-foreground"
                            >
                                Quer uma conversa sobre o seu desafio técnico?
                                Sem ticket, sem formulário com 20 campos.
                            </p>
                            <Link
                                :href="contact().url"
                                class="flex w-full items-center justify-center gap-2 rounded-full border border-primary bg-transparent px-4 py-2.5 text-sm font-bold text-primary transition-all hover:bg-primary hover:text-primary-foreground active:scale-[0.98]"
                            >
                                Iniciar conversa
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                        </div>
                    </aside>

                    <!-- ── Feed ──────────────────────────────────────────── -->
                    <div class="space-y-16 lg:col-span-8">
                        <!-- Projetos -->
                        <div v-if="hasProjects" class="reveal">
                            <div class="mb-8 flex items-center justify-between">
                                <h2 class="text-2xl font-bold tracking-tight">
                                    Projetos
                                </h2>
                                <Link
                                    href="/projetos"
                                    class="group flex items-center gap-1.5 text-sm font-bold text-primary"
                                >
                                    Ver todos
                                    <ArrowRight
                                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    />
                                </Link>
                            </div>

                            <!-- Featured project -->
                            <div v-if="featuredProject" class="mb-5">
                                <div
                                    class="reveal group relative overflow-hidden rounded-3xl border border-border bg-card p-7 transition-all duration-300 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                                >
                                    <div class="mb-2 flex items-center gap-2">
                                        <span
                                            class="rounded-full bg-primary/10 px-2.5 py-0.5 text-[10px] font-bold tracking-widest text-primary uppercase"
                                        >
                                            Destaque
                                        </span>
                                        <span
                                            v-if="featuredProject.year"
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ featuredProject.year }}
                                        </span>
                                    </div>
                                    <h3
                                        class="mb-2 text-xl font-bold tracking-tight"
                                    >
                                        {{ featuredProject.title }}
                                    </h3>
                                    <p
                                        v-if="featuredProject.description"
                                        class="mb-5 line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                                    >
                                        {{ featuredProject.description }}
                                    </p>
                                    <p
                                        v-else
                                        class="mb-5 line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                                    >
                                        {{ featuredProject.client }} —
                                        plataforma construída com arquitetura
                                        enterprise e código preparado para
                                        crescer.
                                    </p>
                                    <div
                                        class="flex flex-wrap items-center gap-3"
                                    >
                                        <Link
                                            :href="`/projetos/${featuredProject.slug}`"
                                            class="flex items-center gap-1.5 text-sm font-bold text-primary group-hover:underline"
                                        >
                                            Ver case completo
                                            <ArrowRight
                                                class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                                            />
                                        </Link>
                                        <div
                                            v-if="featuredProject.stack?.length"
                                            class="flex flex-wrap gap-1.5"
                                        >
                                            <span
                                                v-for="tech in featuredProject.stack.slice(
                                                    0,
                                                    3,
                                                )"
                                                :key="tech"
                                                class="rounded-md border border-border bg-muted/50 px-2 py-0.5 text-[10px] font-medium text-muted-foreground"
                                            >
                                                {{ tech }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Regular projects -->
                            <div
                                v-if="regularProjects.length > 0"
                                class="grid grid-cols-1 gap-5 sm:grid-cols-2"
                            >
                                <div
                                    v-for="(project, i) in regularProjects"
                                    :key="project.slug"
                                    class="reveal"
                                    :style="{
                                        '--reveal-delay': `${(i % 2) * 70}ms`,
                                    }"
                                >
                                    <ProjectCard :project="project" />
                                </div>
                            </div>
                        </div>

                        <!-- Artigos -->
                        <div v-if="hasPosts" class="reveal">
                            <div class="mb-8 flex items-center justify-between">
                                <h2 class="text-2xl font-bold tracking-tight">
                                    Artigos
                                </h2>
                                <Link
                                    href="/blog"
                                    class="group flex items-center gap-1.5 text-sm font-bold text-primary"
                                >
                                    Ver blog
                                    <ArrowRight
                                        class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    />
                                </Link>
                            </div>

                            <div class="space-y-0">
                                <article
                                    v-for="(post, i) in user.posts.data"
                                    :key="post.slug"
                                    class="group relative"
                                >
                                    <Link
                                        :href="blog.show(post.slug)"
                                        class="flex gap-6 border-b border-border py-7 transition-colors first:border-t hover:bg-muted/30"
                                    >
                                        <span
                                            class="mt-0.5 w-8 shrink-0 text-right text-2xl leading-none font-bold text-border transition-colors group-hover:text-primary/20"
                                        >
                                            {{ String(i + 1).padStart(2, '0') }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div
                                                class="mb-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted-foreground"
                                            >
                                                <span
                                                    v-if="post.category"
                                                    class="text-[10px] font-bold tracking-wider text-primary/70 uppercase"
                                                    >{{
                                                        post.category.name
                                                    }}</span
                                                >
                                                <span
                                                    v-if="post.category"
                                                    class="text-border"
                                                    >·</span
                                                >
                                                <time
                                                    :datetime="
                                                        post.published_at ?? ''
                                                    "
                                                >
                                                    {{
                                                        formatDate(
                                                            post.published_at ??
                                                                '',
                                                        )
                                                    }}
                                                </time>
                                                <span class="text-border"
                                                    >·</span
                                                >
                                                <span
                                                    class="flex items-center gap-1"
                                                >
                                                    <Clock class="h-3 w-3" />
                                                    {{ post.reading_time }} min
                                                </span>
                                            </div>
                                            <h3
                                                class="mb-2 text-lg leading-snug font-bold tracking-tight transition-colors group-hover:text-primary"
                                            >
                                                {{ post.title }}
                                            </h3>
                                            <p
                                                v-if="post.excerpt"
                                                class="line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                                            >
                                                {{ post.excerpt }}
                                            </p>
                                        </div>
                                        <div
                                            class="flex shrink-0 items-center self-center"
                                        >
                                            <ArrowRight
                                                class="h-4 w-4 text-muted-foreground/30 transition-all group-hover:translate-x-0.5 group-hover:text-primary"
                                            />
                                        </div>
                                    </Link>
                                </article>
                            </div>
                        </div>

                        <!-- Estado vazio -->
                        <div
                            v-if="!hasProjects && !hasPosts"
                            class="reveal flex flex-col items-center gap-4 rounded-3xl border border-dashed border-border py-20 text-center"
                        >
                            <p class="text-muted-foreground">
                                Nenhum conteúdo publicado ainda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             BOTTOM CTA
        ══════════════════════════════════════════════ -->
        <div class="reveal mx-6 mb-24">
            <div
                class="mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14"
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
                            Quer trabalhar com {{ user.name.split(' ')[0] }}?
                        </h2>
                        <p class="mt-3 text-lg leading-relaxed opacity-80">
                            Manda uma mensagem e vamos conversar sobre como
                            posso ajudar no seu projeto.
                        </p>
                    </div>
                    <div
                        class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                    >
                        <Link
                            :href="contact().url"
                            class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                        >
                            Entrar em contato
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
