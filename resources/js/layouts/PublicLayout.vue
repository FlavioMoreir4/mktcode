<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

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
    <div class="min-h-screen bg-background font-sans selection:bg-primary/10 select-none">
        <!-- Navigation -->
        <nav
            class="fixed top-0 z-50 w-full transition-all duration-300 px-6 py-4"
            :class="[isScrolled ? 'bg-background/80 backdrop-blur-md border-b border-border py-3' : 'bg-transparent']"
        >
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-primary-foreground font-bold text-lg group-hover:scale-105 transition-transform">
                        M
                    </div>
                    <span class="font-bold text-xl tracking-tight">MC</span>
                </Link>

                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-muted-foreground">
                    <a href="#servicos" class="hover:text-foreground transition-colors">Serviços</a>
                    <a href="#projetos" class="hover:text-foreground transition-colors">Projetos</a>
                    <a href="#sobre" class="hover:text-foreground transition-colors">Sobre</a>
                    <a href="#contato" class="hover:text-foreground transition-colors">Contato</a>
                </div>

                <div class="flex items-center gap-4">
                    <Link
                        href="#contato"
                        class="px-5 py-2.5 bg-primary text-primary-foreground rounded-full text-sm font-semibold hover:opacity-90 transition-all hover:scale-[1.02] active:scale-[0.98]"
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
        <footer class="bg-muted/30 border-t border-border py-12 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <Link href="/" class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 bg-primary rounded flex items-center justify-center text-primary-foreground font-bold text-xs">
                            M
                        </div>
                        <span class="font-bold text-lg tracking-tight">MC</span>
                    </Link>
                    <p class="text-muted-foreground text-sm max-w-sm leading-relaxed">
                        Desenvolvimento de sistemas, plataformas e presença digital com engenharia de alta qualidade e foco em resultados.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4">Links</h4>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li><a href="#servicos" class="hover:text-foreground transition-colors">Serviços</a></li>
                        <li><a href="#projetos" class="hover:text-foreground transition-colors">Projetos</a></li>
                        <li><a href="#sobre" class="hover:text-foreground transition-colors">Sobre</a></li>
                        <li><a href="#contato" class="hover:text-foreground transition-colors">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4">Contato</h4>
                    <ul class="space-y-2 text-sm text-muted-foreground">
                        <li><a href="#" class="hover:text-foreground transition-colors">Telegram</a></li>
                        <li><a href="#" class="hover:text-foreground transition-colors">WhatsApp</a></li>
                        <li><a href="#" class="hover:text-foreground transition-colors">E-mail</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-muted-foreground">
                <p>© 2025 MC Marketing & Code · CNPJ XX.XXX.XXX/XXXX-XX</p>
                <p>Desenvolvido com Laravel, Vue 3 e muito café.</p>
            </div>
        </footer>
    </div>
</template>
