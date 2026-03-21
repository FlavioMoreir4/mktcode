<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useJsonLd } from '@/composables/useJsonLd';
import { useSeo } from '@/composables/useSeo';
import type { SeoProps } from '@/types/seo';
import type { SiteData } from '@/types/site';

const props = defineProps<SeoProps>();

const seo = useSeo(props);

const page = usePage();
const site = page.props.site as SiteData;

const fullUrl = computed((): string => {
    if (!seo.url) {
        return site.url;
    }

    if (seo.url.startsWith('http')) {
        return seo.url;
    }

    return `${window.location.origin}${seo.url}`;
});

const jsonLd = computed((): string => JSON.stringify(useJsonLd(seo, site)));
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>

        <!-- Basic SEO -->
        <meta name="description" :content="seo.description ?? undefined" />
        <meta name="author" :content="seo.author ?? undefined" />

        <meta
            v-if="seo.keywords && seo.keywords.length > 0"
            name="keywords"
            :content="seo.keywords.join(', ')"
        />

        <meta
            name="robots"
            :content="seo.noIndex ? 'noindex, nofollow' : (seo.robots ?? 'index, follow')"
        />

        <!-- Canonical -->
        <link rel="canonical" :href="fullUrl" />

        <!-- OpenGraph -->
        <meta property="og:type" :content="seo.type" />
        <meta property="og:title" :content="seo.title ?? undefined" />
        <meta property="og:description" :content="seo.description ?? undefined" />
        <meta property="og:image" :content="seo.image ?? undefined" />
        <meta v-if="seo.imageAlt" property="og:image:alt" :content="seo.imageAlt" />
        <meta property="og:url" :content="fullUrl" />
        <meta property="og:locale" :content="seo.locale ?? 'pt_BR'" />
        <meta property="og:site_name" :content="site.name" />

        <!-- Article specific -->
        <template v-if="seo.type === 'article'">
            <meta
                v-if="seo.publishedAt"
                property="article:published_time"
                :content="seo.publishedAt"
            />

            <meta
                v-if="seo.updatedAt"
                property="article:modified_time"
                :content="seo.updatedAt"
            />

            <meta property="article:author" :content="seo.author ?? undefined" />
        </template>

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title ?? undefined" />
        <meta name="twitter:description" :content="seo.description ?? undefined" />
        <meta name="twitter:image" :content="seo.image ?? undefined" />

        <!-- JSON-LD (multiple schemas) -->
        <template v-for="(schema, i) in JSON.parse(jsonLd)" :key="i">
            <component :is="'script'" type="application/ld+json">
                {{ JSON.stringify(schema) }}
            </component>
        </template>
    </Head>
</template>
