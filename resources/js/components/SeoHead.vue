<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useSeo } from '@/composables/useSeo';
import { SITE_OG_IMAGE, SITE_KEYWORDS } from '@/config/site';
import type { SeoProps } from '@/types';

const props = withDefaults(defineProps<SeoProps>(), {
    type: 'website',
    noIndex: false,
    image: SITE_OG_IMAGE,
    keywords: SITE_KEYWORDS,
});

const seo = useSeo(props);
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta name="description" :content="seo.description" />
        <meta name="author" :content="seo.author" />
        <meta name="keywords" :content="seo.keywords" />
        <meta
            name="robots"
            :content="seo.noIndex ? 'noindex, nofollow' : 'index, follow'"
        />

        <!-- Canonical -->
        <link v-if="seo.url" rel="canonical" :href="seo.url" />

        <!-- Open Graph -->
        <meta property="og:site_name" :content="seo.siteName" />
        <meta property="og:locale" content="pt_BR" />
        <meta
            property="og:type"
            :content="seo.type === 'profile' ? 'profile' : seo.type"
        />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:image" :content="seo.image" />
        <meta v-if="seo.url" property="og:url" :content="seo.url" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />

        <!-- Article only -->
        <template v-if="seo.type === 'article' && seo.publishedAt">
            <meta
                property="article:published_time"
                :content="seo.publishedAt"
            />
            <meta property="article:author" :content="seo.author" />
            <meta property="article:keywords" :content="seo.keywords" />
        </template>
    </Head>
</template>
