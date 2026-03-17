export interface SeoProps {
    title: string;
    description?: string;
    image?: string;
    url?: string;
    type?: 'website' | 'article' | 'profile';
    publishedAt?: string;
    keywords?: string;
    author?: string;
    noIndex?: boolean;
}
