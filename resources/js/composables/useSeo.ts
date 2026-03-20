import { usePage } from '@inertiajs/vue3';
import type { SeoProps } from '@/types';

export function useSeo(props?: SeoProps) {
    const page = usePage();

    const site = page.props.site as any;
    const globalSeo = page.props.seo as SeoProps;
    console.log(
        window.location.href,
        'window.location.href',
        props?.url,
        'props.url',
        globalSeo?.url,
        'globalSeo.url',
    );
    const url =
        props?.url ??
        (typeof window !== 'undefined' ? window.location.href : site.url) ??
        globalSeo?.url;

    const image = props?.image ?? globalSeo?.image ?? site.og_image;

    return {
        title: props?.title || globalSeo?.title || site.name,

        description:
            props?.description || globalSeo?.description || site.description,

        url,
        image,

        type: props?.type || globalSeo?.type || 'website',

        publishedAt: props?.publishedAt || globalSeo?.publishedAt,

        author: props?.author || site.author,

        keywords: props?.keywords || site.keywords,

        noIndex: props?.noIndex ?? false,
    };
}
