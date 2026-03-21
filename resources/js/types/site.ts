/**
 * Dados globais do site compartilhados pelo middleware Inertia.
 * Corresponde ao objeto `site` em `HandleInertiaRequests::share()`.
 */
export interface SiteData {
    name: string;
    url: string;
    description: string;
    og_image: string;
    keywords: string[];
    author: string;
    locale: string;
    social_links: Record<string, string>;
}
