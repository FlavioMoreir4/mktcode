import type { SeoProps } from '@/types';

export function useJsonLd(
    seo: SeoProps,
    site: { name: string; url: string; logo?: string },
) {
    switch (seo.type) {
        case 'article':
            return {
                '@context': 'https://schema.org',
                '@type': 'Article',
                headline: seo.title,
                image: seo.image,
                author: {
                    '@type': 'Person',
                    name: seo.author,
                },
                publisher: {
                    '@type': 'Organization',
                    name: site.name,
                    logo: site.logo
                        ? { '@type': 'ImageObject', url: site.logo }
                        : undefined,
                },
                datePublished: seo.publishedAt,
            };

        case 'profile':
            return {
                '@context': 'https://schema.org',
                '@type': 'Person',
                name: seo.author ?? seo.title,
                url: seo.url,
                image: seo.image,
            };

        case 'project':
            return {
                '@context': 'https://schema.org',
                '@type': 'CreativeWork',
                name: seo.title,
                description: seo.description,
                url: seo.url,
                image: seo.image,
            };

        default: // website
            return {
                '@context': 'https://schema.org',
                '@type': 'WebSite',
                name: site.name,
                url: site.url,
                // potentialAction: {
                //     '@type': 'SearchAction',
                //     target: `${site.url}/search?q={search_term_string}`,
                //     'query-input': 'required name=search_term_string',
                // },
            };
    }
}
