export interface SeoData {
    title: string | null;
    description: string | null;
    image: string | null;
    keywords?: string[] | null;
    author?: string | null;
}

export interface SocialLink {
    platform: string;
    url: string;
    featured: boolean;
    icon_only: boolean;
    stack_on_mobile: boolean;
}

export interface PublicMediaData {
    cover: { url: string } | null;
    gallery: { url: string }[];
}

export interface PublicAuthorData {
    name: string;
    username: string;
    title: string | null;
    avatar_url: string | null;
    profile_url: string | null;
}

export interface PublicCategoryData {
    name: string;
    slug: string;
}

export interface PublicTagData {
    name: string | Record<string, string>;
    slug: string | Record<string, string>;
}

export interface PublicProjectViewData {
    title: string;
    slug: string;
    description: string | null;
    content: string;
    client: string | null;
    year: string | null;
    stack: string[] | null;
    url: string | null;
    featured: boolean;
    author?: PublicAuthorData | null;
    media: PublicMediaData | null;
}

export interface PublicPostSummaryData {
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    word_count: number;
    reading_time: number;
    media: PublicMediaData | null;
    author: PublicAuthorData | null;
    category: PublicCategoryData | null;
    tags: PublicTagData[];
}

export interface PublicPostDetailData extends PublicPostSummaryData {
    body: string | null;
    markdown: string | null;
    plain_text: string | null;
    created_at: string | null;
    updated_at: string | null;
}

export interface PublicProfileViewData {
    name: string;
    username: string;
    title: string | null;
    bio: string | null;
    location: string | null;
    avatar: string;
    cover: string;
    social: SocialLink[] | null;
    skills: string | null;
    projects_count: number;
    posts_count: number;
    projects: { data: PublicProjectViewData[] };
    posts: { data: PublicPostSummaryData[] };
}

export interface PublicPageDetailData {
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    word_count: number;
    reading_time: number;
    body: string | null;
    markdown: string | null;
    plain_text: string | null;
    created_at: string | null;
    updated_at: string | null;
}
