<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import logo from '@/../images/logo.png';
import { services, projects, about, contact } from '@/routes/public';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="min-h-screen bg-background font-sans selection:bg-primary/10">
        <!-- Navigation -->
        <nav
            class="fixed top-0 z-50 w-full px-6 py-4 transition-all duration-300"
            :class="[
                isScrolled
                    ? 'border-b border-border bg-background/80 py-3 backdrop-blur-md'
                    : 'bg-transparent',
            ]"
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <Link href="/" class="group flex items-center gap-2">
                    <div
                        class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg transition-transform group-hover:scale-105"
                    >
                        <img
                            :src="logo"
                            alt="MC Logo"
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

                <div
                    class="hidden items-center gap-8 text-sm font-medium text-muted-foreground md:flex"
                >
                    <Link
                        :href="services().url"
                        class="transition-colors hover:text-foreground"
                        >Serviços</Link
                    >
                    <Link
                        :href="projects().url"
                        class="transition-colors hover:text-foreground"
                        >Projetos</Link
                    >
                    <Link
                        :href="about().url"
                        class="transition-colors hover:text-foreground"
                        >Sobre</Link
                    >
                    <Link
                        :href="contact().url"
                        class="transition-colors hover:text-foreground"
                        >Contato</Link
                    >
                </div>

                <div class="flex items-center gap-4">
                    <Link
                        :href="contact().url"
                        class="rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition-all hover:scale-[1.02] hover:opacity-90 active:scale-[0.98]"
                    >
                        Bora conversar
                    </Link>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-border bg-muted/30 px-6 py-12">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 gap-12 md:grid-cols-4"
            >
                <div class="md:col-span-2">
                    <Link href="/" class="mb-4 flex items-center gap-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg transition-transform group-hover:scale-105"
                        >
                            <img
                                :src="logo"
                                alt="MC Logo"
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
                        class="max-w-sm text-sm leading-relaxed text-muted-foreground"
                    >
                        Desenvolvimento de sistemas, plataformas e presença
                        digital com engenharia de alta qualidade e foco em
                        resultados.
                    </p>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-bold">Links</h4>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li>
                            <Link
                                :href="services().url"
                                class="transition-colors hover:text-foreground"
                                >Serviços</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="projects().url"
                                class="transition-colors hover:text-foreground"
                                >Projetos</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="about().url"
                                class="transition-colors hover:text-foreground"
                                >Sobre</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="contact().url"
                                class="transition-colors hover:text-foreground"
                                >Contato</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-bold">Contato</h4>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li>
                            <a
                                href="https://t.me/flaviomoreir4"
                                target="_blank"
                                class="transition-colors hover:text-foreground"
                                >Telegram</a
                            >
                        </li>
                        <li>
                            <a
                                href="https://wa.me/5511982776725"
                                target="_blank"
                                class="transition-colors hover:text-foreground"
                                >WhatsApp</a
                            >
                        </li>
                        <li>
                            <a
                                href="mailto:flavio.moreira@mktcode.digital"
                                class="transition-colors hover:text-foreground"
                                >E-mail</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="mx-auto mt-12 flex max-w-7xl flex-col items-center justify-between gap-4 border-t border-border pt-8 text-xs text-muted-foreground md:flex-row"
            >
                <p>© 2025 MC Marketing & Code · CNPJ 43.296.394/0001-80</p>
                <p>Desenvolvido com Laravel, Vue 3 e muito café.</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.logo-fallback {
    position: absolute;
    inset: 0;
}

img:not([style*='display: none']) + .logo-fallback {
    opacity: 0;
    pointer-events: none;
}
</style>
