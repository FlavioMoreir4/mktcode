<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/public/PublicLayout.vue';
import { formatDate } from '@/lib/utils';
import type { PublicPageDetailData, SeoData } from '@/types/public';

interface Props {
    page: PublicPageDetailData;
    seo?: SeoData;
}

const props = defineProps<Props>();

const isMounted = ref(false);

const readingProgress = ref(0);
const articleRef = ref<HTMLElement | null>(null);

const minutesRemaining = computed(() =>
    Math.max(1, Math.ceil(props.page.reading_time ?? 1)),
);

const updateProgress = () => {
    if (!articleRef.value) {
        return;
    }

    const el = articleRef.value;
    const scrollTop = window.scrollY - el.offsetTop;
    const height = el.offsetHeight - window.innerHeight;

    readingProgress.value = Math.min(
        100,
        Math.max(0, height > 0 ? (scrollTop / height) * 100 : 0),
    );
};

onMounted(() => {
    isMounted.value = true;
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
});
</script>

<template>
    <SeoHead v-bind="props.seo" />


    <!-- Reading progress bar -->
    <div class="fixed top-0 right-0 left-0 z-[60] h-0.5 bg-transparent" role="progressbar"
        :aria-valuenow="Math.round(readingProgress)" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full bg-primary transition-[width] duration-150 ease-out"
            :style="{ width: `${readingProgress}%` }" />
    </div>

    <div class="px-6 pt-32 pb-32">
        <div class="mx-auto max-w-3xl">
            <!-- Header -->
            <header class="reveal mb-12">
                <h1 class="text-4xl leading-[1.1] font-bold tracking-tight md:text-5xl">
                    {{ page.title }}
                </h1>

                <p v-if="page.excerpt" class="mt-4 text-lg leading-relaxed text-muted-foreground">
                    {{ page.excerpt }}
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-muted-foreground">
                    <span v-if="page.published_at">
                        Publicado em
                        {{ formatDate(page.published_at) }}
                    </span>
                    <span v-if="page.reading_time" class="inline-flex items-center gap-1">
                        · {{ minutesRemaining }} min de leitura
                    </span>
                    <span v-if="page.updated_at && page.updated_at !== page.published_at"
                        class="inline-flex items-center gap-1">
                        · Atualizado em {{ formatDate(page.updated_at) }}
                    </span>
                </div>

                <div class="mt-8 h-px w-full bg-gradient-to-r from-border via-border to-transparent" />
            </header>

            <!-- Body -->
            <article ref="articleRef"
                class="prose prose-lg max-w-prose dark:prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:underline prose-blockquote:border-primary/50 prose-blockquote:text-muted-foreground prose-blockquote:not-italic prose-code:rounded-md prose-code:bg-muted prose-code:px-1.5 prose-code:py-0.5 prose-code:text-sm prose-code:before:content-none prose-code:after:content-none prose-pre:rounded-2xl prose-pre:border prose-pre:border-border"
                v-html="page.body" />
        </div>
    </div>

</template>
