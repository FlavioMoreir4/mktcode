<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import logo from '@/../images/logo.png';
import { Button } from '@/components/ui/button';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
} from '@/components/ui/navigation-menu';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';

import { services, projects, about, contact } from '@/routes/public';
import blog from '@/routes/public/blog';
import type { RouteDefinition } from '@/wayfinder';

const isMounted = ref(false);
// ─── Scroll state ───────────────────────────────────────────────────────────
const isScrolled = ref(false);
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    isMounted.value = true; // ← libera os componentes client-only
    window.addEventListener('scroll', handleScroll, { passive: true });
});
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

// ─── Mobile menu (Sheet) ────────────────────────────────────────────────────
const mobileOpen = ref(false);
const closeMobile = () => {
    mobileOpen.value = false;
};

const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

// Close on route change
const page = usePage();

// ─── Active link detection ───────────────────────────────────────────────────
const currentUrl = computed(() => page.url);
const isActive = (href: RouteDefinition<any>) => {
    if (href.url === '/') {
        return currentUrl.value === '/';
    }

    return currentUrl.value.startsWith(href.url);
};

// ─── Nav links ───────────────────────────────────────────────────────────────
const navLinks = computed(() => [
    { label: 'Serviços', href: services() },
    { label: 'Projetos', href: projects() },
    { label: 'Sobre', href: about() },
    { label: 'Blog', href: blog.index() },
    { label: 'Contato', href: contact() },
]);
</script>

<template>
    <div class="min-h-screen bg-background font-sans selection:bg-primary/10">
        <!-- Skip to content (accessibility) -->
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[200] focus:rounded-lg focus:bg-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-primary-foreground focus:shadow-lg focus:outline-none"
        >
            Ir para o conteúdo
        </a>

        <!-- ─── Navigation ──────────────────────────────────────────────── -->
        <nav
            role="navigation"
            aria-label="Navegação principal"
            class="fixed top-0 z-50 w-full px-6 transition-all duration-300"
            :class="
                isScrolled
                    ? 'border-b border-border bg-background/80 py-3 shadow-sm backdrop-blur-md'
                    : 'bg-transparent py-4'
            "
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <!-- Logo -->
                <Link
                    href="/"
                    class="group flex items-center gap-2 rounded-lg focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:outline-none"
                >
                    <div
                        class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg transition-transform group-hover:scale-105"
                    >
                        <img
                            :src="logo"
                            alt=""
                            aria-hidden="true"
                            class="h-full w-full object-contain"
                            @error="
                                (e) =>
                                    ((
                                        e.target as HTMLImageElement
                                    ).style.display = 'none')
                            "
                        />
                        <div
                            class="logo-fallback flex h-full w-full items-center justify-center bg-primary text-lg font-bold text-primary-foreground"
                        >
                            MC
                        </div>
                    </div>
                    <span class="text-xl font-bold tracking-tight"
                        >Marketing & Code</span
                    >
                </Link>

                <!-- Desktop: NavigationMenu — só no cliente para evitar hydration mismatch -->
                <template v-if="isMounted">
                    <!-- Desktop: NavigationMenu -->
                    <NavigationMenu class="hidden w-full items-center md:flex">
                        <NavigationMenuList class="mx-auto flex gap-2">
                            <!-- Links regulares (sem dropdown) -->
                            <NavigationMenuItem
                                v-for="link in navLinks.slice(0, -1)"
                                :key="link.href.url"
                            >
                                <NavigationMenuLink as-child>
                                    <Link
                                        :href="link.href"
                                        class="relative px-3 py-2 text-sm font-medium transition-colors hover:bg-transparent hover:text-foreground data-[active]:text-foreground data-[active]:after:absolute data-[active]:after:right-3 data-[active]:after:bottom-1 data-[active]:after:left-3 data-[active]:after:h-px data-[active]:after:rounded-full data-[active]:after:bg-primary"
                                        :class="
                                            isActive(link.href)
                                                ? 'text-foreground'
                                                : 'text-muted-foreground'
                                        "
                                        :data-active="
                                            isActive(link.href) ? '' : undefined
                                        "
                                    >
                                        {{ link.label }}
                                    </Link>
                                </NavigationMenuLink>
                            </NavigationMenuItem>

                            <!-- Link "Contato" com estilo de botão (CTA) -->
                            <NavigationMenuItem>
                                <NavigationMenuLink as-child>
                                    <Link
                                        :href="contact().url"
                                        class="rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition-all hover:scale-[1.02] hover:opacity-90 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:outline-none active:scale-[0.98]"
                                    >
                                        Bora conversar
                                    </Link>
                                </NavigationMenuLink>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </template>
                <!-- Placeholder SSR: mantém o espaço no layout sem divergência -->
                <div v-else class="hidden md:flex" aria-hidden="true" />

                <!-- Mobile: Sheet Trigger (hambúrguer) -->
                <Sheet v-model:open="mobileOpen">
                    <SheetTrigger as-child>
                        <Button
                            variant="ghost"
                            size="icon"
                            class="md:hidden"
                            aria-label="Abrir menu"
                        >
                            <!-- Ícone hambúrguer animado (mantido do original) -->
                            <span
                                class="relative flex h-5 w-5 flex-col justify-center"
                                aria-hidden="true"
                            >
                                <span
                                    class="absolute block h-px w-full bg-current transition-all duration-300"
                                    :class="
                                        mobileOpen
                                            ? 'rotate-45'
                                            : '-translate-y-1.5'
                                    "
                                />
                                <span
                                    class="absolute block h-px w-full bg-current transition-all duration-200"
                                    :class="
                                        mobileOpen ? 'scale-x-0 opacity-0' : ''
                                    "
                                />
                                <span
                                    class="absolute block h-px w-full bg-current transition-all duration-300"
                                    :class="
                                        mobileOpen
                                            ? '-rotate-45'
                                            : 'translate-y-1.5'
                                    "
                                />
                            </span>
                        </Button>
                    </SheetTrigger>

                    <!-- Conteúdo do Sheet (menu mobile) -->
                    <SheetContent
                        side="right"
                        class="w-full max-w-xs p-0"
                        aria-describedby="menu-mobile"
                    >
                        <SheetHeader
                            class="border-b border-border px-6 py-4 text-left"
                        >
                            <SheetTitle
                                class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                            >
                                Menu
                            </SheetTitle>
                            <SheetClose
                                class="absolute top-4 right-4 rounded-lg p-1 text-muted-foreground hover:bg-muted hover:text-foreground focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                            />
                        </SheetHeader>

                        <div class="flex h-full flex-col">
                            <!-- Links de navegação -->
                            <nav class="flex-1 overflow-y-auto px-4 py-6">
                                <ul class="space-y-1">
                                    <li
                                        v-for="(link, i) in navLinks"
                                        :key="link.href.url"
                                        class="mobile-link"
                                        :style="{ '--delay': `${i * 40}ms` }"
                                    >
                                        <Link
                                            :href="link.href"
                                            class="flex items-center justify-between rounded-xl px-4 py-3.5 text-base font-medium transition-colors focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                                            :class="
                                                isActive(link.href)
                                                    ? 'bg-primary/10 text-primary'
                                                    : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                            "
                                            @click="closeMobile"
                                        >
                                            {{ link.label }}
                                            <svg
                                                v-if="isActive(link.href)"
                                                class="h-4 w-4 text-primary"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2.5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7"
                                                />
                                            </svg>
                                        </Link>
                                    </li>
                                </ul>
                            </nav>

                            <!-- CTA no rodapé do Sheet -->
                            <SheetFooter
                                class="border-t border-border p-6 sm:flex-col sm:space-x-0"
                            >
                                <Link
                                    :href="contact().url"
                                    class="flex w-full items-center justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground transition-all hover:opacity-90 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:outline-none active:scale-[0.98]"
                                    @click="closeMobile"
                                >
                                    Bora conversar
                                </Link>
                                <p
                                    class="mt-4 text-center text-xs text-muted-foreground"
                                >
                                    Respondo em até 24h 👋
                                </p>
                            </SheetFooter>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </nav>

        <!-- ─── Main Content ────────────────────────────────────────────── -->
        <main id="main-content">
            <slot />
        </main>

        <!-- ─── Footer (mantido idêntico) ───────────────────────────────── -->
        <footer
            class="border-t border-border bg-muted/30 px-6 py-14"
            role="contentinfo"
        >
            <!-- ... conteúdo do footer permanece igual ... -->
            <div class="mx-auto max-w-7xl">
                <!-- Top grid -->
                <div
                    class="grid grid-cols-2 gap-10 sm:grid-cols-2 md:grid-cols-4 lg:gap-16"
                >
                    <!-- Brand -->
                    <div class="col-span-2 md:col-span-2">
                        <Link
                            href="/"
                            class="mb-4 inline-flex items-center gap-2 rounded-lg focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                        >
                            <div
                                class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg"
                            >
                                <img
                                    :src="logo"
                                    alt=""
                                    aria-hidden="true"
                                    class="h-full w-full object-contain"
                                    @error="
                                        (e) =>
                                            ((
                                                e.target as HTMLImageElement
                                            ).style.display = 'none')
                                    "
                                />
                                <div
                                    class="logo-fallback flex h-full w-full items-center justify-center bg-primary text-lg font-bold text-primary-foreground"
                                >
                                    MC
                                </div>
                            </div>
                            <span class="text-lg font-bold tracking-tight"
                                >Marketing & Code</span
                            >
                        </Link>
                        <p
                            class="max-w-xs text-sm leading-relaxed text-muted-foreground"
                        >
                            Desenvolvimento de sistemas, plataformas e presença
                            digital com engenharia de alta qualidade e foco em
                            resultados.
                        </p>

                        <!-- Social / contact icons -->
                        <div class="mt-6 flex gap-3">
                            <!-- ... ícones sociais mantidos ... -->
                            <a
                                href="https://t.me/flaviomoreir4"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Telegram"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="https://wa.me/5511982776725"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="WhatsApp"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="mailto:flavio.moreira@mktcode.digital"
                                aria-label="E-mail"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.75"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.917V6.75"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Links -->
                    <div>
                        <h2
                            class="mb-4 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Navegação
                        </h2>
                        <ul class="space-y-2.5 text-sm" role="list">
                            <li v-for="link in navLinks" :key="link.href.url">
                                <Link
                                    :href="link.href"
                                    class="text-muted-foreground transition-colors hover:text-foreground focus-visible:text-foreground focus-visible:outline-none"
                                    :class="
                                        isActive(link.href)
                                            ? 'font-medium text-foreground'
                                            : ''
                                    "
                                >
                                    {{ link.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h2
                            class="mb-4 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Contato
                        </h2>
                        <ul class="space-y-2.5 text-sm" role="list">
                            <li>
                                <a
                                    href="https://t.me/flaviomoreir4"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    Telegram
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://wa.me/5511982776725"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    WhatsApp
                                </a>
                            </li>
                            <li>
                                <a
                                    href="mailto:flavio.moreira@mktcode.digital"
                                    class="text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    E-mail
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div
                    class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-border pt-8 text-xs text-muted-foreground sm:flex-row"
                >
                    <p>
                        © {{ new Date().getFullYear() }} MC Marketing & Code ·
                        CNPJ 43.296.394/0001-80
                    </p>
                    <p>
                        Desenvolvido com Laravel, Filament, Vue 3 e muito café
                        ☕
                    </p>
                </div>
            </div>
        </footer>
        <Transition
            enter-active-class="transition-all duration-200"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <button
                v-if="isMounted && isScrolled"
                @click="scrollToTop"
                class="group fixed right-4 bottom-4 flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
                title="Voltar ao topo"
            >
                <svg
                    class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 10l7-7m0 0l7 7m-7-7v18"
                    />
                </svg>
            </button>
        </Transition>
    </div>
</template>

<style scoped>
/* Logo fallback logic */
.logo-fallback {
    position: absolute;
    inset: 0;
}

img:not([style*='display: none']) + .logo-fallback {
    opacity: 0;
    pointer-events: none;
}

/* Mobile link stagger animation (mantida) */
.mobile-link {
    animation: slide-in 0.25s ease-out both;
    animation-delay: var(--delay, 0ms);
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateX(12px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Ajuste fino para o trigger do sheet (hambúrguer) */
[data-radix-vue-collection-item] {
    outline: none;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out both;
}
</style>
