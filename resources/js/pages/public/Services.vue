<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    CheckCircle2,
    Layout,
    Layers,
    Monitor,
    SearchCode,
    Settings2,
    Globe,
    Brain,
    Code2,
    Server,
    Database,
    Cpu,
    Smartphone,
    Shield,
    BarChart2,
    Zap,
    Package,
    ArrowRight,
    MessageSquare,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';

import { contact } from '@/routes/public';
import type { SeoData, Service } from '@/types';

interface Props {
    services: Service[];
    seo: SeoData;
}

const props = defineProps<Props>();

// ─── Icon map ────────────────────────────────────────────────────────────────
const iconMap: Record<string, unknown> = {
    monitor: Monitor,
    layers: Layers,
    layout: Layout,
    glob: Globe,
    globe: Globe,
    'search-code': SearchCode,
    'settings-2': Settings2,
    brain: Brain,
    'code-2': Code2,
    server: Server,
    database: Database,
    cpu: Cpu,
    smartphone: Smartphone,
    shield: Shield,
    'bar-chart': BarChart2,
    zap: Zap,
    package: Package,
};
const getIcon = (icon: string) => iconMap[icon] ?? Monitor;

// ─── Service slug ─────────────────────────────────────────────────────────────
const slugify = (str: string) =>
    str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

const padIndex = (i: number) => String(i + 1).padStart(2, '0');

const isMounted = ref(false);

// ─── Active section tracking ──────────────────────────────────────────────────
const activeId = ref<string>('');
const showMobileNav = ref(false);

const handleScroll = () => {
    // Active section
    const sections = document.querySelectorAll<HTMLElement>(
        '[data-service-section]',
    );
    let current = '';
    sections.forEach((el) => {
        if (window.scrollY >= el.offsetTop - 160) {
            current = el.dataset.serviceSection ?? '';
        }
    });
    activeId.value = current;

    // Mobile pill nav — aparece após 200px
    showMobileNav.value = window.scrollY > 200;
};

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    isMounted.value = true;
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -50px 0px' },
    );
    document.querySelectorAll('.reveal').forEach((el) => observer?.observe(el));
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    observer?.disconnect();
});

// ─── ToC items ────────────────────────────────────────────────────────────────
const tocItems = computed(() =>
    props.services.map((s) => ({ id: slugify(s.title), label: s.title })),
);

const scrollTo = (id: string) => {
    const el = document.getElementById(id);

    if (el) {
        const top = el.getBoundingClientRect().top + window.scrollY - 100;
        window.scrollTo({ top, behavior: 'smooth' });
    }
};

// Label curto para a pill nav mobile
const shortLabel = (title: string) => {
    // Pega a primeira parte antes de "&" ou "—"
    return title.split(/[&—]/)[0].trim();
};
</script>

<template>
    <SeoHead v-bind="props.seo" />


    <!-- ── Pill nav mobile — sticky, aparece após 200px ──────────── -->
    <Teleport to="body" :disabled="!isMounted">
        <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition-all duration-150"
            leave-from-class="opacity-100" leave-to-class="opacity-0 -translate-y-2">
            <div v-if="showMobileNav"
                class="fixed top-[57px] right-0 left-0 z-40 overflow-x-auto border-b border-border bg-background/95 px-4 py-2.5 backdrop-blur-sm xl:hidden"
                role="navigation" aria-label="Navegação rápida entre serviços">
                <div class="flex gap-2 whitespace-nowrap">
                    <button v-for="(item, i) in tocItems" :key="item.id"
                        class="rounded-full px-3.5 py-1.5 text-xs font-medium transition-all" :class="activeId === item.id
                            ? 'bg-primary text-primary-foreground'
                            : 'bg-muted text-muted-foreground hover:text-foreground'
                            " @click="scrollTo(item.id)">
                        <span class="mr-1 opacity-60">{{
                            padIndex(i)
                        }}</span>{{ shortLabel(item.label) }}
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>

    <div class="px-6 pt-32 pb-32">
        <div class="mx-auto max-w-7xl">
            <!-- ── Page Header ──────────────────────────────────────── -->
            <div class="reveal mb-24 max-w-3xl">
                <p class="mb-3 text-xs font-bold tracking-widest text-primary uppercase">
                    Serviços
                </p>
                <h1 class="mb-6 text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl">
                    O que fazemos —<br />
                    <span class="text-muted-foreground">e como fazemos.</span>
                </h1>
                <p class="text-lg leading-relaxed text-muted-foreground">
                    Cada serviço tem uma forma de trabalho pensada para
                    entregar valor real, no prazo combinado, com comunicação
                    transparente do início ao fim.
                </p>

                <div class="mt-10 flex flex-wrap gap-6">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="flex h-2 w-2 rounded-full bg-primary" />
                        <span class="font-semibold">{{ services.length }} serviços</span>
                        <span class="text-muted-foreground">disponíveis</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="flex h-2 w-2 rounded-full bg-primary" />
                        <span class="font-semibold">Orçamento gratuito</span>
                        <span class="text-muted-foreground">sem compromisso</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="flex h-2 w-2 rounded-full bg-primary" />
                        <span class="font-semibold">Resposta</span>
                        <span class="text-muted-foreground">em até 24h</span>
                    </div>
                </div>
            </div>

            <!-- ── Body: ToC sidebar + Content ────────────────────── -->
            <div class="relative flex gap-16 xl:gap-24">
                <!-- Sticky ToC — desktop only -->
                <aside class="hidden xl:block">
                    <nav class="sticky top-28 w-52 shrink-0" aria-label="Índice de serviços">
                        <p class="mb-4 text-xs font-bold tracking-widest text-muted-foreground uppercase">
                            Nesta página
                        </p>
                        <ul class="space-y-1">
                            <li v-for="(item, i) in tocItems" :key="item.id">
                                <button
                                    class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm transition-colors"
                                    :class="activeId === item.id
                                        ? 'bg-primary/10 font-semibold text-primary'
                                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                        " :aria-current="activeId === item.id
                                            ? 'location'
                                            : undefined
                                            " @click="scrollTo(item.id)">
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full transition-colors" :class="activeId === item.id
                                        ? 'bg-primary'
                                        : 'bg-border group-hover:bg-muted-foreground'
                                        " />
                                    <span class="mr-0.5 text-[10px] font-medium opacity-50">{{ padIndex(i) }}</span>
                                    {{ item.label }}
                                </button>
                            </li>
                        </ul>

                        <!-- Sticky CTA -->
                        <div class="mt-10 rounded-2xl border border-border bg-muted/50 p-5">
                            <p class="mb-3 text-sm leading-snug font-semibold">
                                Dúvidas sobre qual serviço escolher?
                            </p>
                            <Link :href="contact().url"
                                class="flex items-center gap-1.5 text-sm font-bold text-primary hover:underline">
                                Fala com a gente
                                <ArrowRight class="h-3.5 w-3.5" />
                            </Link>
                        </div>
                    </nav>
                </aside>

                <!-- Services list -->
                <div class="min-w-0 flex-1">
                    <div class="space-y-0 divide-y divide-border">
                        <article v-for="(service, i) in services" :id="slugify(service.title)" :key="service.id"
                            :data-service-section="slugify(service.title)" class="py-16 first:pt-0">
                            <!-- Número + ícone + título -->
                            <div class="reveal mb-10 flex items-start gap-5">
                                <div class="min-w-0 flex-1">
                                    <div class="mb-3 flex items-center gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary ring-1 ring-primary/15">
                                            <component :is="getIcon(service.icon)" class="h-6 w-6" />
                                        </div>
                                        <span class="text-4xl font-bold text-border/60 tabular-nums">{{ padIndex(i)
                                            }}</span>
                                    </div>
                                    <h2 class="text-3xl font-bold tracking-tight md:text-4xl">
                                        {{ service.title }}
                                    </h2>
                                </div>
                            </div>

                            <!-- Body grid -->
                            <div class="reveal grid grid-cols-1 gap-8 lg:grid-cols-2" style="--reveal-delay: 60ms">
                                <!-- Left: descrição + ideal_for + CTA mobile -->
                                <div class="space-y-6">
                                    <p class="text-lg leading-relaxed text-muted-foreground">
                                        {{ service.description }}
                                    </p>

                                    <div v-if="service.ideal_for"
                                        class="rounded-2xl border-l-2 border-primary/40 bg-muted/40 px-5 py-4">
                                        <p class="mb-1 text-xs font-bold tracking-wider text-primary/70 uppercase">
                                            Ideal para
                                        </p>
                                        <p class="text-sm leading-relaxed text-foreground">
                                            {{ service.ideal_for }}
                                        </p>
                                    </div>

                                    <!-- CTA mobile -->
                                    <div class="lg:hidden">
                                        <Link :href="contact().url"
                                            class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-5 py-2.5 text-sm font-semibold text-primary transition-all hover:bg-primary/10">
                                            Solicitar orçamento
                                            <ArrowRight class="h-4 w-4" />
                                        </Link>
                                    </div>
                                </div>

                                <!-- Right: features card -->
                                <div
                                    class="group relative overflow-hidden rounded-3xl border border-border bg-card p-7 transition-all duration-300 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5">
                                    <!-- Glow on hover -->
                                    <div
                                        class="pointer-events-none absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/5 opacity-0 blur-3xl transition-opacity duration-500 group-hover:opacity-100" />

                                    <h3 class="relative mb-5 text-base font-bold">
                                        O que está incluso
                                    </h3>

                                    <ul class="relative space-y-3">
                                        <li v-for="feature in service.features" :key="feature.item"
                                            class="group/item flex items-start gap-3">
                                            <div
                                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary transition-all duration-200 group-hover/item:bg-primary group-hover/item:text-primary-foreground">
                                                <CheckCircle2 class="h-3 w-3" />
                                            </div>
                                            <span
                                                class="text-sm leading-relaxed text-muted-foreground transition-colors duration-200 group-hover/item:text-foreground">
                                                {{ feature.item }}
                                            </span>
                                        </li>
                                    </ul>

                                    <div class="relative mt-7 border-t border-border pt-5">
                                        <Link :href="contact().url"
                                            class="group/link inline-flex items-center gap-2 text-sm font-bold text-primary">
                                            Fazer orçamento para este
                                            serviço
                                            <ArrowRight
                                                class="h-4 w-4 transition-transform group-hover/link:translate-x-1" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- ── Bottom CTA ─────────────────────────────── -->
                    <div class="reveal mt-20">
                        <div
                            class="overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14">
                            <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                                <div class="max-w-lg">
                                    <p class="mb-2 text-xs font-bold tracking-widest uppercase opacity-60">
                                        Próximo passo
                                    </p>
                                    <h2 class="text-3xl font-bold tracking-tight md:text-4xl">
                                        Não sabe por onde começar?
                                    </h2>
                                    <p class="mt-3 text-lg leading-relaxed opacity-80">
                                        Conta o seu desafio. A gente analisa
                                        e indica o melhor caminho — sem
                                        enrolação e sem custo.
                                    </p>
                                </div>
                                <div class="flex flex-col gap-3 sm:flex-row md:flex-col md:items-end">
                                    <Link :href="contact().url"
                                        class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]">
                                        <MessageSquare class="h-4 w-4" />
                                        Fala com a gente
                                    </Link>
                                    <a href="https://wa.me/5511982776725" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20">
                                        WhatsApp direto
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
.reveal {
    opacity: 0;
    transform: translateY(20px);
    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
    transition-delay: var(--reveal-delay, 0ms);
}

.reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
