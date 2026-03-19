export interface Media {
    id: number;
    model_type: string;
    model_id: number;
    uuid: string;
    collection_name: string;
    name: string;
    file_name: string;
    mime_type: string;
    disk: string;
    conversions_disk: string;
    size: number;
    original_url: string;
    preview_url: string;
    order_column: number;
    created_at: string;
    updated_at: string;
    custom_properties: Record<string, any>;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    created_at: string;
    updated_at: string;
}

export interface Tag {
    id: number;
    name: string | { [key: string]: string };
    slug: string | { [key: string]: string };
    type: string | null;
    order_column: number;
    created_at: string;
    updated_at: string;
}

interface SocialLink {
    platform: string;
    url: string;
}

export interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    avatar?: string;
    profile_photo_url?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    title: string;
    bio: string;
    location: string;
    social_links: SocialLink[];
    cover_photo_url: string;
    posts: Post[];
    projects: Project[];
    media: Media[];
}

export interface Post {
    id: number;
    title: string;
    slug: string;
    body: string;
    excerpt: string | null;
    status: 'draft' | 'published';
    published_at: string | null;
    author_id: number;
    category_id: number | null;
    seo_title: string | null;
    seo_description: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;

    word_count: number;
    reading_time: number;
    plain_text: string;
    markdown: string;
    html: string;

    // Relationships
    author?: User;
    category?: Category;
    tags?: Tag[];
    media?: Media[];
}

export interface Project {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    content: string;
    client: string | null;
    year: string | null;
    status: 'draft' | 'published';
    stack: string[] | null;
    url: string | null;
    featured: boolean;
    sort_order: number;
    seo_title: string | null;
    seo_description: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;

    // UI specific or Missing fields in model but used in components
    category?: string;
    highlights?: string[];

    // Relationships
    tags?: Tag[];
    media?: Media[];
}

export interface Service {
    id: number;
    title: string;
    description: string;
    icon: string;
    features: { item: string }[];
    ideal_for: string | null;
    active: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
}

export interface Inquiry {
    id: number;
    name: string;
    email: string;
    whatsapp: string | null;
    message: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationLink[];
    current_page: number;
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
    meta: {
        current_page: number;
        from: number | null;
        last_page: number;
        links: {
            url: string | null;
            label: string;
            active: boolean;
            page: number | null;
        }[];
        per_page: number;
        to: number | null;
        total: number;
    };
}
