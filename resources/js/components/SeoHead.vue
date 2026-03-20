<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useJsonLd } from '@/composables/useJsonLd';
import { useSeo } from '@/composables/useSeo';
import type { SeoProps } from '@/types';

const props = defineProps<SeoProps>();

const seo = useSeo(props);

const page = usePage();
const site = page.props.site;

const fullUrl = computed(() => {
    if (seo.url.startsWith('http')) {
        return seo.url;
    }

    return `${window.location.origin}${seo.url}`;
});
const jsonLd = computed(() => JSON.stringify(useJsonLd(seo, site)));
// const jsonLd = JSON.stringify({
//     '@context': 'https://schema.org',
//     '@type': seo.type === 'article' ? 'Article' : 'WebSite',
//     name: seo.title,
//     description: seo.description,
//     url: fullUrl.value,
//     image: seo.image,
//     author: {
//         '@type': seo.type === 'profile' ? 'Person' : 'Organization',
//         name: seo.author,
//     },
//     datePublished: seo.publishedAt,
// });
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>

        <!-- Basic SEO -->
        <meta name="description" :content="seo.description" />
        <meta name="author" :content="seo.author" />
        <meta name="keywords" :content="seo.keywords" />
        <meta
            name="robots"
            :content="seo.noIndex ? 'noindex, nofollow' : 'index, follow'"
        />

        <!-- Canonical -->
        <link v-if="fullUrl" rel="canonical" :href="fullUrl" />

        <!-- Open Graph -->
        <meta property="og:type" :content="seo.type" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:image" :content="seo.image" />
        <meta property="og:url" :content="fullUrl" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />

        <!-- Article specific -->
        <template v-if="seo.type === 'article'">
            <meta
                v-if="seo.publishedAt"
                property="article:published_time"
                :content="seo.publishedAt"
            />
            <meta property="article:author" :content="seo.author" />
        </template>

        <!-- JSON-LD -->
        <component :is="'script'" type="application/ld+json">
            {{ jsonLd }}
        </component>
    </Head>
</template>
