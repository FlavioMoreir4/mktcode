<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    MapPin,
    Github,
    Linkedin,
    Twitter,
    Instagram,
    Youtube,
    Globe,
    Link as LucideLink,
    ArrowRight,
    Calendar,
    Clock,
    ExternalLink,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted } from 'vue';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import { contact } from '@/routes/public';
import blog from '@/routes/public/blog';
import type { PublicUser } from '@/types';

const props = defineProps<{
    user: PublicUser;
}>();

// ─── Social icon + label ──────────────────────────────────────────────────────
const socialIconMap: Record<string, unknown> = {
    github: Github,
    linkedin: Linkedin,
    twitter: Twitter,
    instagram: Instagram,
    youtube: Youtube,
    website: Globe,
};
const getSocialIcon = (p: string) => socialIconMap[p] ?? LucideLink;
const getSocialLabel = (p: string) => p.charAt(0).toUpperCase() + p.slice(1);

// ─── Has content checks ───────────────────────────────────────────────────────
const hasSocial = computed(() => {
    const social = props.user.social;

    if (!social) {
        return false;
    }

    return Array.isArray(social)
        ? social.length > 0
        : Object.keys(social).length > 0;
});

const hasProjects = computed(() => props.user.projects?.data?.length > 0);
const hasPosts = computed(() => props.user.posts?.data?.length > 0);

console.log(
    'hasPosts:',
    hasPosts.value,
    'hasProjects:',
    hasProjects.value,
    'hasSocial:',
    hasSocial.value,
);
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
    <SeoHead v-bind="user.seo" />

    <PublicLayout>
        <!-- ══════════════════════════════════════════════
             COVER + IDENTITY
        ══════════════════════════════════════════════ -->
        <section class="relative pt-20">
            <!-- Cover photo -->
            <div class="relative h-56 w-full overflow-hidden bg-muted md:h-80">
                <img
                    v-if="user.cover"
                    :src="user.cover"
                    alt=""
                    aria-hidden="true"
                    class="h-full w-full object-cover"
                />
                <div
                    v-else
                    class="h-full w-full bg-gradient-to-br from-primary/15 via-primary/5 to-transparent"
                />
                <!-- Fade into background -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-background via-background/20 to-transparent"
                />
            </div>

            <!-- Identity row -->
            <div class="mx-auto max-w-7xl px-6">
                <div
                    class="relative -mt-20 flex flex-col items-start gap-6 md:-mt-28 md:flex-row md:items-end"
                >
                    <!-- Avatar -->
                    <div class="shrink-0">
                        <div
                            class="h-36 w-36 overflow-hidden rounded-3xl border-4 border-background bg-muted shadow-2xl md:h-48 md:w-48"
                        >
                            <img
                                v-if="user.avatar"
                                :src="user.avatar"
                                :alt="user.name"
                                class="h-full w-full object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-primary/10 text-5xl font-bold text-primary"
                            >
                                {{ user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>

                    <!-- Name + title + location -->
                    <div class="min-w-0 flex-1 pb-4">
                        <h1
                            class="text-3xl font-bold tracking-tight md:text-5xl"
                        >
                            {{ user.name }}
                        </h1>
                        <p
                            v-if="user.title"
                            class="mt-1.5 text-lg font-medium text-primary/80"
                        >
                            {{ user.title }}
                        </p>
                        <div
                            class="mt-3 flex flex-wrap gap-4 text-sm text-muted-foreground"
                        >
                            <div
                                v-if="user.location"
                                class="flex items-center gap-1.5"
                            >
                                <MapPin class="h-4 w-4 shrink-0" />
                                {{ user.location }}
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="flex gap-3 pb-4">
                        <Link
                            :href="contact().url"
                            class="rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-primary-foreground shadow-lg transition-all hover:scale-[1.02] hover:opacity-90 active:scale-[0.98]"
                        >
                            Contratar
                        </Link>
                        <a
                            v-if="
                                user.social?.find(
                                    (l) => l.platform === 'github',
                                )
                            "
                            :href="
                                user.social.find(
                                    (l) => l.platform === 'github',
                                )!.url
                            "
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-2 rounded-full border border-border bg-background px-5 py-2.5 text-sm font-bold transition-all hover:bg-muted"
                        >
                            <Github class="h-4 w-4" />
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════════════ -->
        <section class="pt-12 pb-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                    <!-- ── Sidebar ──────────────────────────────────── -->
                    <aside class="space-y-10 lg:col-span-4">
                        <!-- Bio -->
                        <div class="reveal">
                            <h3
                                class="mb-4 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Sobre
                            </h3>
                            <div
                                v-if="user.bio"
                                class="prose prose-sm max-w-none text-muted-foreground dark:prose-invert prose-p:leading-relaxed prose-a:font-semibold prose-a:text-primary prose-a:no-underline hover:prose-a:underline"
                                v-html="user.bio"
                            />
                            <p
                                v-else
                                class="text-sm text-muted-foreground/60 italic"
                            >
                                Nenhuma biografia adicionada ainda.
                            </p>
                        </div>

                        <!-- Social links -->
                        <div v-if="hasSocial" class="reveal">
                            <h3
                                class="mb-4 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Conecte-se
                            </h3>
                            <div class="flex flex-col gap-2">
                                <a
                                    v-for="link in user.social"
                                    :key="link.platform"
                                    :href="link.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="group flex items-center gap-3 rounded-xl border border-border bg-card/50 p-3.5 transition-all hover:border-primary/30 hover:bg-primary/5"
                                >
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/8 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground"
                                    >
                                        <component
                                            :is="getSocialIcon(link.platform)"
                                            class="h-4 w-4"
                                        />
                                    </div>
                                    <span class="text-sm font-semibold">
                                        {{ getSocialLabel(link.platform) }}
                                    </span>
                                    <ExternalLink
                                        class="ml-auto h-3.5 w-3.5 text-muted-foreground/40 transition-colors group-hover:text-primary/60"
                                    />
                                </a>
                            </div>
                        </div>

                        <!-- Stack (if no social — fills the sidebar) -->
                        <div
                            v-if="!hasSocial && !hasPosts"
                            class="reveal rounded-2xl border border-border bg-muted/30 p-5"
                        >
                            <p class="text-sm text-muted-foreground italic">
                                Nenhum link social adicionado ainda.
                            </p>
                        </div>
                    </aside>

                    <!-- ── Feed ────────────────────────────────────── -->
                    <div class="space-y-20 lg:col-span-8">
                        <!-- Projects -->
                        <div v-if="hasProjects" class="reveal">
                            <div class="mb-8 flex items-center justify-between">
                                <h2 class="text-2xl font-bold tracking-tight">
                                    Projetos em destaque
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

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div
                                    v-for="(project, i) in user.projects.data"
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

                        <!-- Posts -->
                        <div v-if="hasPosts" class="reveal">
                            <div class="mb-8 flex items-center justify-between">
                                <h2 class="text-2xl font-bold tracking-tight">
                                    Últimos artigos
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

                            <div class="divide-y divide-border">
                                <article
                                    v-for="(post, i) in user.posts.data"
                                    :key="i"
                                    class="group py-6 first:pt-0"
                                >
                                    <Link
                                        :href="blog.show(post.slug)"
                                        class="block space-y-2"
                                    >
                                        <!-- Meta -->
                                        <div
                                            class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted-foreground"
                                        >
                                            <span
                                                v-if="post.category"
                                                class="font-semibold text-primary/70"
                                            >
                                                {{ post.category.name }}
                                            </span>
                                            <span
                                                v-if="post.category"
                                                class="text-border"
                                                >·</span
                                            >
                                            <span
                                                class="flex items-center gap-1"
                                            >
                                                <Calendar class="h-3 w-3" />
                                                {{
                                                    formatDate(
                                                        post.published_at ?? '',
                                                    )
                                                }}
                                            </span>
                                            <span class="text-border">·</span>
                                            <span
                                                class="flex items-center gap-1"
                                            >
                                                <Clock class="h-3 w-3" />
                                                {{ post.reading_time }} min
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3
                                            class="text-xl leading-snug font-bold tracking-tight transition-colors group-hover:text-primary"
                                        >
                                            {{ post.title }}
                                        </h3>

                                        <!-- Excerpt -->
                                        <p
                                            v-if="post.excerpt"
                                            class="line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                                        >
                                            {{ post.excerpt }}
                                        </p>

                                        <!-- Read more -->
                                        <div
                                            class="flex items-center gap-1.5 pt-1 text-sm font-bold text-primary opacity-0 transition-opacity group-hover:opacity-100"
                                        >
                                            Ler artigo
                                            <ArrowRight
                                                class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                                            />
                                        </div>
                                    </Link>
                                </article>
                            </div>
                        </div>

                        <!-- Empty state (no projects + no posts) -->
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
