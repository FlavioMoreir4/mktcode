import type { SeoProps } from '@/types/seo';
import type { SiteData } from '@/types/site';

type JsonLdSchema = Record<string, unknown>;

export function useJsonLd(seo: SeoProps, site: SiteData): JsonLdSchema[] {
    const schemas: JsonLdSchema[] = [];

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    */
    schemas.push({
        '@context': 'https://schema.org',
        '@type': 'Organization',
        name: site.name,
        url: site.url,
        logo: site.og_image,
        sameAs: Object.values(site.social_links ?? {}),
    });

    /*
    |--------------------------------------------------------------------------
    | Website
    |--------------------------------------------------------------------------
    */
    if (seo.type === 'website') {
        schemas.push({
            '@context': 'https://schema.org',
            '@type': 'WebSite',
            name: site.name,
            url: site.url,
            inLanguage: seo.locale ?? site.locale,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Article
    |--------------------------------------------------------------------------
    */
    if (seo.type === 'article') {
        schemas.push({
            '@context': 'https://schema.org',
            '@type': 'Article',
            headline: seo.title,
            image: seo.image,
            inLanguage: seo.locale ?? site.locale,
            author: {
                '@type': 'Person',
                name: seo.author,
            },
            publisher: {
                '@type': 'Organization',
                name: site.name,
                logo: site.og_image
                    ? { '@type': 'ImageObject', url: site.og_image }
                    : undefined,
            },
            datePublished: seo.publishedAt,
            dateModified: seo.updatedAt,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    if (seo.type === 'profile') {
        schemas.push({
            '@context': 'https://schema.org',
            '@type': 'Person',
            name: seo.author ?? seo.title,
            url: seo.url,
            image: seo.image,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Project / Portfolio
    |--------------------------------------------------------------------------
    */
    if (seo.type === 'project') {
        schemas.push({
            '@context': 'https://schema.org',
            '@type': 'CreativeWork',
            name: seo.title,
            description: seo.description,
            url: seo.url,
            image: seo.image,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    */
    if (seo.breadcrumbs && seo.breadcrumbs.length > 0) {
        schemas.push({
            '@context': 'https://schema.org',
            '@type': 'BreadcrumbList',
            itemListElement: seo.breadcrumbs.map((item, index) => ({
                '@type': 'ListItem',
                position: index + 1,
                name: item.name,
                item: item.url,
            })),
        });
    }

    return schemas;
}
