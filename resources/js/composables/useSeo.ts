import { usePage } from '@inertiajs/vue3';
import type { SeoProps } from '@/types/seo';
import type { SiteData } from '@/types/site';

export function useSeo(props?: SeoProps): Required<SeoProps> {
    const page = usePage();

    const site = page.props.site as SiteData;
    const globalSeo = (page.props.seo ?? null) as SeoProps | null;
    const currentUrl =
        typeof window !== 'undefined' ? window.location.href : site.url;

    return {
        title: props?.title ?? globalSeo?.title ?? site.name,

        description:
            props?.description ?? globalSeo?.description ?? site.description,

        url: props?.url ?? globalSeo?.url ?? currentUrl,

        canonical: props?.canonical ?? globalSeo?.canonical ?? currentUrl,

        image: props?.image ?? globalSeo?.image ?? site.og_image,

        imageAlt: props?.imageAlt ?? globalSeo?.imageAlt ?? null,

        type: props?.type ?? globalSeo?.type ?? 'website',

        publishedAt: props?.publishedAt ?? globalSeo?.publishedAt ?? null,
        updatedAt: props?.updatedAt ?? globalSeo?.updatedAt ?? null,

        author: props?.author ?? globalSeo?.author ?? site.author,

        keywords: props?.keywords ?? globalSeo?.keywords ?? site.keywords,

        category: props?.category ?? globalSeo?.category ?? null,
        tags: props?.tags ?? globalSeo?.tags ?? null,

        breadcrumbs: props?.breadcrumbs ?? globalSeo?.breadcrumbs ?? null,

        robots: props?.robots ?? globalSeo?.robots ?? 'index, follow',

        locale: props?.locale ?? globalSeo?.locale ?? site.locale,

        noIndex: props?.noIndex ?? false,
    };
}
