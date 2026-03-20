import type { PaginatedResponse } from '../models';

export interface SeoData {
    title: string | null;
    description: string | null;
    image: string | null;
    keywords?: string | null;
    author?: string | null;
}

export interface PublicPost {
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    word_count: number;
    reading_time: number;

    author?: {
        name: string;
        username: string;
        avatar: string | null;
    };

    category?: {
        name: string;
        slug: string;
    };

    tags?: { name: string; slug: string }[];

    cover?: string | null;
    seo: SeoData;
}

export interface PublicPostShow extends PublicPost {
    body: string;
    markdown: string;
    plain_text: string;
    created_at: string | null;
    updated_at: string | null;

    author?: {
        name: string;
        username: string;
        avatar: string | null;
        location?: string | null;
        social?: SocialLink[] | null;
    };
}

export interface PublicProject {
    title: string;
    slug: string;
    description: string | null;
    content: string;
    client: string | null;
    year: string | null;
    stack: string[] | null;
    url: string | null;
    featured: boolean;

    cover?: string | null;
    gallery?: string[];

    seo: SeoData;
}

export interface PublicUser {
    name: string;
    username: string;
    title: string | null;
    bio: string | null;
    location: string | null;

    avatar: string | null;
    cover: string | null;

    social: SocialLink[] | null;

    projects: PaginatedResponse<PublicProject>;
    posts: PaginatedResponse<PublicPost>;

    seo: SeoData;
}

export interface SocialLink {
    platform: string;
    url: string;
}

// export interface PaginatedResponse<T> {
//     data: T[];
//     meta: {
//         current_page: number;
//         last_page: number;
//         per_page: number;
//         total: number;
//     };
//     links: {
//         next: string | null;
//         prev: string | null;
//     };
// }
