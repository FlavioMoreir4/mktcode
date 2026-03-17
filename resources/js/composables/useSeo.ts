import {
    SITE_NAME,
    SITE_URL,
    SITE_DESCRIPTION,
    SITE_OG_IMAGE,
    SITE_KEYWORDS,
    SITE_AUTHOR,
} from '@/config/site';
import type { SeoProps } from '@/types';

export function useSeo(props: SeoProps) {
    const canonicalUrl = props.url
        ? props.url.startsWith('http')
            ? props.url
            : `${SITE_URL}${props.url}`
        : undefined;

    return {
        siteName: SITE_NAME,
        title: props.title,
        description: props.description || SITE_DESCRIPTION,
        image: props.image
            ? props.image.startsWith('http')
                ? props.image
                : `${SITE_URL}${props.image}`
            : `${SITE_URL}${SITE_OG_IMAGE}`,
        url: canonicalUrl,
        type: props.type || 'website',
        publishedAt: props.publishedAt,
        keywords: props.keywords || SITE_KEYWORDS,
        author: props.author || SITE_AUTHOR,
        noIndex: props.noIndex ?? false,
    };
}
