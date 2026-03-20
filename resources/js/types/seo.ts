export interface SeoProps {
    title?: string | null;
    description?: string | null;
    image?: string | null;
    url?: string | null;
    type?: 'website' | 'article' | 'profile' | 'project';
    publishedAt?: string | null;
    keywords?: string | null;
    author?: string | null;
    noIndex?: boolean;
}
