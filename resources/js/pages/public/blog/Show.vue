<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import hljs from 'highlight.js';
import {
    MapPinIcon,
    Github,
    Linkedin,
    Twitter,
    Instagram,
    Youtube,
    Globe,
    Link as LucideLink,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import blog from '@/routes/public/blog';
import user from '@/routes/public/user';
import type { Post } from '@/types/models';

const props = defineProps<{
    post: Post;
}>();

// Reading progress
const readingProgress = ref(0);
const articleRef = ref<HTMLElement | null>(null);
const copied = ref(false);

// Estimated reading time (avg 200 words/min)
const readingTime = computed(() => {
    const text = props.post.body?.replace(/<[^>]*>/g, '') ?? '';
    const words = text.trim().split(/\s+/).length;

    return Math.max(1, Math.ceil(words / 200));
});

// Tag name helper
const getTagName = (tag: any): string => {
    if (typeof tag.name === 'string') {
        return tag.name;
    }

    return tag.name?.en ?? tag.name?.pt ?? Object.values(tag.name)[0] ?? '';
};

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

const postUrl = computed(() => {
    return blog.show(props.post.slug).url;
});

const copyLink = async () => {
    await navigator.clipboard.writeText(postUrl.value);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};

const shareOnX = () => {
    const url = `https://x.com/intent/tweet?text=${encodeURIComponent(props.post.title)}&url=${encodeURIComponent(postUrl.value)}`;
    window.open(url, '_blank', 'noopener');
};

const socialIconMap: Record<string, unknown> = {
    github: Github,
    linkedin: Linkedin,
    twitter: Twitter,
    instagram: Instagram,
    youtube: Youtube,
    website: Globe,
};
const getSocialIcon = (p: string) => socialIconMap[p] ?? LucideLink;

onMounted(() => {
    hljs.highlightAll();
    window.addEventListener('scroll', updateProgress, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
});
</script>

<template>
    <SeoHead
        :title="post.seo_title ?? post.title"
        :description="post.seo_description ?? post.excerpt ?? ''"
        :image="post.media?.[0]?.original_url"
        :url="postUrl"
        :keywords="post.tags?.map((tag) => getTagName(tag))?.join(', ')"
        :publishedAt="post.published_at ?? ''"
        :type="'article'"
        :author="post.author?.name"
    />

    <PublicLayout>
        <!-- Reading Progress Bar -->
        <div class="fixed top-0 right-0 left-0 z-50 h-[2px] bg-border/40">
            <div
                class="h-full bg-primary transition-all duration-100 ease-out"
                :style="{ width: `${readingProgress}%` }"
            />
        </div>

        <article ref="articleRef" class="px-6 pt-28 pb-24">
            <!-- Floating Actions — Desktop Sidebar -->
            <aside
                class="fixed top-1/2 right-6 z-40 hidden -translate-y-1/2 flex-col gap-3 xl:flex"
            >
                <!-- Back -->
                <Link
                    href="/blog"
                    class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                    title="Voltar ao blog"
                >
                    <svg
                        class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                </Link>

                <!-- Share X -->
                <button
                    @click="shareOnX"
                    class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                    title="Compartilhar no X"
                >
                    <svg
                        class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.738l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                        />
                    </svg>
                </button>

                <!-- Copy Link -->
                <button
                    @click="copyLink"
                    class="group flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                    :title="copied ? 'Link copiado!' : 'Copiar link'"
                >
                    <svg
                        v-if="!copied"
                        class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
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
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </button>
            </aside>

            <div class="mx-auto max-w-3xl">
                <!-- Header -->
                <header class="animate-fade-in-up mb-12">
                    <!-- Breadcrumb + Meta -->
                    <div
                        class="mb-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm"
                    >
                        <Link
                            href="/blog"
                            class="text-muted-foreground transition-colors hover:text-foreground"
                        >
                            Blog
                        </Link>
                        <span class="text-border">›</span>
                        <span
                            v-if="post.category"
                            class="font-medium text-foreground"
                        >
                            {{ post.category.name }}
                        </span>
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

                    <!-- Author row -->
                    <div
                        class="flex flex-wrap items-center justify-center gap-4 text-sm"
                    >
                        <div v-if="post.author" class="flex items-center gap-2">
                            <img
                                v-if="post.author.profile_photo_url"
                                :src="post.author.profile_photo_url"
                                :alt="post.author.name"
                                class="h-7 w-7 rounded-full object-cover ring-1 ring-border"
                            />
                            <Link
                                :href="user.show(post.author.username)"
                                class="font-medium hover:underline"
                            >
                                {{ post.author.name }}
                            </Link>
                        </div>

                        <span class="hidden text-border sm:block">·</span>

                        <time
                            v-if="post.published_at"
                            :datetime="post.published_at"
                            class="text-muted-foreground"
                        >
                            {{ formatDate(post.published_at) }}
                        </time>

                        <span class="hidden text-border sm:block">·</span>

                        <span
                            class="flex items-center gap-1 text-muted-foreground"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ readingTime }} min de leitura
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                <div
                    v-if="post.media && post.media.length > 0"
                    class="mb-16 overflow-hidden rounded-3xl bg-muted shadow-lg"
                >
                    <img
                        :src="post.media[0].original_url"
                        :alt="post.title"
                        class="aspect-video w-full object-cover transition-transform duration-700 hover:scale-[1.02]"
                    />
                </div>

                <!-- Divider when no image -->
                <div v-else class="mb-16 h-px bg-border/60" />

                <!-- Content -->
                <div
                    class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:underline prose-blockquote:border-primary/50 prose-blockquote:text-muted-foreground prose-blockquote:not-italic prose-code:rounded-md prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:rounded-2xl prose-pre:border prose-pre:border-border prose-img:rounded-2xl prose-img:shadow-md prose-hr:border-border"
                >
                    <div v-html="post.body" />
                </div>

                <!-- Footer -->
                <footer class="mt-20">
                    <!-- Tags -->
                    <div
                        v-if="post.tags && post.tags.length > 0"
                        class="mb-10 flex flex-wrap gap-2"
                    >
                        <span
                            v-for="tag in post.tags"
                            :key="tag.id"
                            class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground transition-colors hover:text-foreground"
                        >
                            #{{ getTagName(tag) }}
                        </span>
                    </div>

                    <!-- Share row (mobile) -->
                    <div class="mb-10 flex items-center gap-3 xl:hidden">
                        <span class="text-sm text-muted-foreground"
                            >Compartilhar:</span
                        >
                        <button
                            @click="shareOnX"
                            class="flex items-center gap-1.5 rounded-full border border-border px-3 py-1.5 text-sm transition-colors hover:bg-muted"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                viewBox="0 0 24 24"
                                fill="currentColor"
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
                        >
                            <svg
                                v-if="!copied"
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
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
                            <HoverCardTrigger>
                                <div
                                    v-if="post.author.profile_photo_url"
                                    class="h-16 w-16 shrink-0 overflow-hidden rounded-full ring-2 ring-border"
                                >
                                    <img
                                        :src="post.author.profile_photo_url"
                                        :alt="post.author.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </HoverCardTrigger>

                            <div class="text-center sm:text-left">
                                <p
                                    class="mb-1 text-xs tracking-widest text-muted-foreground uppercase"
                                >
                                    Escrito por
                                </p>
                                <HoverCardTrigger as-child>
                                    <Link
                                        :href="user.show(post.author.username)"
                                        class="text-lg font-bold hover:underline"
                                    >
                                        {{ post.author.name }}
                                    </Link>
                                </HoverCardTrigger>
                                <p
                                    class="mt-1.5 text-sm leading-relaxed text-muted-foreground"
                                >
                                    Desenvolvedor e fundador da mktcode.
                                    Apaixonado por transformar ideias em
                                    produtos digitais de alta performance.
                                </p>
                            </div>
                            <HoverCardContent>
                                <div
                                    class="flex items-center justify-start gap-4"
                                >
                                    <Avatar class="h-16 w-16">
                                        <AvatarImage
                                            :src="
                                                post.author.profile_photo_url ||
                                                ''
                                            "
                                        />
                                        <AvatarFallback>{{
                                            post.author.name.charAt(0)
                                        }}</AvatarFallback>
                                    </Avatar>
                                    <div class="space-y-1">
                                        <h4 class="text-lg font-semibold">
                                            {{ post.author.name }}
                                        </h4>
                                        <div class="flex items-center">
                                            <MapPinIcon
                                                class="mr-2 h-3 w-3 opacity-70"
                                            />
                                            <span
                                                class="text-xs text-muted-foreground"
                                            >
                                                {{ post.author.location }}
                                            </span>
                                        </div>
                                        <!-- Social Links -->
                                        <div
                                            class="flex items-center gap-2 pt-2"
                                        >
                                            <a
                                                v-for="(link, index) in post
                                                    .author.social_links"
                                                :key="index"
                                                :href="link.url"
                                                target="_blank"
                                                class="text-muted-foreground hover:text-primary"
                                            >
                                                <component
                                                    :is="
                                                        getSocialIcon(
                                                            link.platform,
                                                        )
                                                    "
                                                    class="h-4 w-4"
                                                />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </HoverCardContent>
                        </HoverCard>
                    </div>
                </footer>
            </div>
        </article>
    </PublicLayout>
</template>

<style scoped>
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
