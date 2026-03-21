import { usePage } from '@inertiajs/vue3';
import type { SeoProps } from '@/types';

export function useSeo(props?: SeoProps) {
    const page = usePage();

    const site = page.props.site as any;
    const globalSeo = page.props.seo as SeoProps;
    const currentUrl =
        typeof window !== 'undefined' ? window.location.href : site.url;

    return {
        title: props?.title ?? globalSeo?.title ?? site.name,

        description:
            props?.description ?? globalSeo?.description ?? site.description,

        url: props?.url ?? globalSeo?.url ?? currentUrl,

        canonical: props?.canonical ?? globalSeo?.canonical ?? currentUrl,

        image: props?.image ?? globalSeo?.image ?? site.og_image,

        type: props?.type ?? globalSeo?.type ?? 'website',

        publishedAt: props?.publishedAt ?? globalSeo?.publishedAt,
        updatedAt: props?.updatedAt ?? globalSeo?.updatedAt,

        author: props?.author ?? globalSeo?.author ?? site.author,

        keywords: props?.keywords ?? globalSeo?.keywords ?? site.keywords,

        breadcrumbs: props?.breadcrumbs ?? globalSeo?.breadcrumbs ?? null,

        robots: props?.robots ?? globalSeo?.robots ?? 'index, follow',

        noIndex: props?.noIndex ?? false,
    };
}
