import type { SeoProps } from '@/types';

export function useJsonLd(
    seo: SeoProps,
    site: { name: string; url: string; logo?: string },
) {
    const schemas: any[] = [];

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
        logo: site.logo,
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
