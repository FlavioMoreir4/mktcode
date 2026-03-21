export interface SeoProps {
    title?: string | null;
    description?: string | null;
    image?: string | null;
    imageAlt?: string | null;
    url?: string | null;
    canonical?: string | null;

    type?: 'website' | 'article' | 'profile' | 'project';

    publishedAt?: string | null;
    updatedAt?: string | null;

    author?: string | null;

    keywords?: string[] | null;

    category?: { name: string; slug: string } | null;
    tags?: { name: string; slug: string }[] | null;

    breadcrumbs?: { name: string; url: string }[] | null;

    robots?: string | null;
    locale?: string | null;
    noIndex?: boolean;
}
