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
import PublicLayout from '@/layouts/PublicLayout.vue';
import { contact } from '@/routes/public';
import type { Service } from '@/types';

const props = defineProps<{
    services: Service[];
}>();

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

// ─── Service slug (for anchor IDs) ───────────────────────────────────────────
const slugify = (str: string) =>
    str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

// ─── Active section tracking ──────────────────────────────────────────────────
const activeId = ref<string>('');

const handleScroll = () => {
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
};

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });

    // set initial active
    handleScroll();

    // Scroll reveal
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

// ─── Padded index ─────────────────────────────────────────────────────────────
// const padIndex = (i: number) => String(i + 1).padStart(2, '0');

// ─── ToC items ───────────────────────────────────────────────────────────────
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
</script>

<template>
    <SeoHead title="Serviços" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-32">
            <div class="mx-auto max-w-7xl">
                <!-- ── Page Header ──────────────────────────────────────── -->
                <div class="reveal mb-24 max-w-3xl">
                    <p
                        class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Serviços
                    </p>
                    <h1
                        class="mb-6 text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                    >
                        O que fazemos —<br />
                        <span class="text-muted-foreground"
                            >e como fazemos.</span
                        >
                    </h1>
                    <p class="text-lg leading-relaxed text-muted-foreground">
                        Cada serviço tem uma forma de trabalho pensada para
                        entregar valor real, no prazo combinado, com comunicação
                        transparente do início ao fim.
                    </p>

                    <!-- Quick stats -->
                    <div class="mt-10 flex flex-wrap gap-6">
                        <div class="flex items-center gap-2 text-sm">
                            <span
                                class="flex h-2 w-2 rounded-full bg-primary"
                            />
                            <span class="font-semibold"
                                >{{ services.length }} serviços</span
                            >
                            <span class="text-muted-foreground"
                                >disponíveis</span
                            >
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span
                                class="flex h-2 w-2 rounded-full bg-primary"
                            />
                            <span class="font-semibold"
                                >Orçamento gratuito</span
                            >
                            <span class="text-muted-foreground"
                                >sem compromisso</span
                            >
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <span
                                class="flex h-2 w-2 rounded-full bg-primary"
                            />
                            <span class="font-semibold">Resposta</span>
                            <span class="text-muted-foreground"
                                >em até 24h</span
                            >
                        </div>
                    </div>
                </div>

                <!-- ── Body: ToC sidebar + Content ────────────────────── -->
                <div class="relative flex gap-16 xl:gap-24">
                    <!-- Sticky ToC — desktop only -->
                    <aside class="hidden xl:block">
                        <nav
                            class="sticky top-28 w-52 shrink-0"
                            aria-label="Índice de serviços"
                        >
                            <p
                                class="mb-4 text-xs font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Nesta página
                            </p>
                            <ul class="space-y-1">
                                <li v-for="item in tocItems" :key="item.id">
                                    <button
                                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm transition-colors"
                                        :class="
                                            activeId === item.id
                                                ? 'bg-primary/8 font-semibold text-primary'
                                                : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                        "
                                        @click="scrollTo(item.id)"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 shrink-0 rounded-full transition-colors"
                                            :class="
                                                activeId === item.id
                                                    ? 'bg-primary'
                                                    : 'bg-border group-hover:bg-muted-foreground'
                                            "
                                        />
                                        {{ item.label }}
                                    </button>
                                </li>
                            </ul>

                            <!-- Sticky CTA -->
                            <div
                                class="mt-10 rounded-2xl border border-border bg-muted/50 p-5"
                            >
                                <p
                                    class="mb-3 text-sm leading-snug font-semibold"
                                >
                                    Dúvidas sobre qual serviço escolher?
                                </p>
                                <Link
                                    :href="contact().url"
                                    class="flex items-center gap-1.5 text-sm font-bold text-primary hover:underline"
                                >
                                    Fala com a gente
                                    <ArrowRight class="h-3.5 w-3.5" />
                                </Link>
                            </div>
                        </nav>
                    </aside>

                    <!-- Services list -->
                    <div class="min-w-0 flex-1">
                        <div class="space-y-0 divide-y divide-border">
                            <article
                                v-for="service in services"
                                :id="slugify(service.title)"
                                :key="service.id"
                                :data-service-section="slugify(service.title)"
                                class="py-16 first:pt-0"
                            >
                                <!-- Index + title row -->
                                <div
                                    class="reveal mb-10 flex items-start gap-5"
                                >
                                    <div class="min-w-0">
                                        <div
                                            class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/8 text-primary ring-1 ring-primary/15"
                                        >
                                            <component
                                                :is="getIcon(service.icon)"
                                                class="h-6 w-6"
                                            />
                                        </div>
                                        <h2
                                            class="text-3xl font-bold tracking-tight md:text-4xl"
                                        >
                                            {{ service.title }}
                                        </h2>
                                    </div>
                                </div>

                                <!-- Body grid -->
                                <div
                                    class="reveal grid grid-cols-1 gap-8 lg:grid-cols-2"
                                    style="--reveal-delay: 60ms"
                                >
                                    <!-- Left: description + ideal for -->
                                    <div class="space-y-6">
                                        <p
                                            class="text-lg leading-relaxed text-muted-foreground"
                                        >
                                            {{ service.description }}
                                        </p>

                                        <div
                                            v-if="service.ideal_for"
                                            class="rounded-2xl border-l-2 border-primary/40 bg-muted/40 px-5 py-4"
                                        >
                                            <p
                                                class="mb-1 text-xs font-bold tracking-wider text-primary/70 uppercase"
                                            >
                                                Ideal para
                                            </p>
                                            <p
                                                class="text-sm leading-relaxed text-foreground"
                                            >
                                                {{ service.ideal_for }}
                                            </p>
                                        </div>

                                        <!-- Mobile CTA (inside card) -->
                                        <div class="lg:hidden">
                                            <Link
                                                :href="contact().url"
                                                class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-5 py-2.5 text-sm font-semibold text-primary transition-all hover:bg-primary/10"
                                            >
                                                Solicitar orçamento
                                                <ArrowRight class="h-4 w-4" />
                                            </Link>
                                        </div>
                                    </div>

                                    <!-- Right: features card -->
                                    <div
                                        class="group relative overflow-hidden rounded-3xl border border-border bg-card p-7 transition-all duration-300 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                                    >
                                        <!-- Subtle glow on hover -->
                                        <div
                                            class="pointer-events-none absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/5 opacity-0 blur-3xl transition-opacity duration-500 group-hover:opacity-100"
                                        />

                                        <h3
                                            class="relative mb-5 text-base font-bold"
                                        >
                                            O que está incluso
                                        </h3>

                                        <ul class="relative space-y-3">
                                            <li
                                                v-for="feature in service.features"
                                                :key="feature.item"
                                                class="group/item flex items-start gap-3"
                                            >
                                                <div
                                                    class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/8 text-primary transition-all duration-200 group-hover/item:bg-primary group-hover/item:text-primary-foreground"
                                                >
                                                    <CheckCircle2
                                                        class="h-3 w-3"
                                                    />
                                                </div>
                                                <span
                                                    class="text-sm leading-relaxed text-muted-foreground transition-colors duration-200 group-hover/item:text-foreground"
                                                >
                                                    {{ feature.item }}
                                                </span>
                                            </li>
                                        </ul>

                                        <div
                                            class="relative mt-7 border-t border-border pt-5"
                                        >
                                            <Link
                                                :href="contact().url"
                                                class="group/link inline-flex items-center gap-2 text-sm font-bold text-primary"
                                            >
                                                Fazer orçamento para este
                                                serviço
                                                <ArrowRight
                                                    class="h-4 w-4 transition-transform group-hover/link:translate-x-1"
                                                />
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <!-- ── Bottom CTA ─────────────────────────────── -->
                        <div class="reveal mt-20">
                            <div
                                class="overflow-hidden rounded-[2rem] bg-primary px-8 py-14 text-primary-foreground md:px-14"
                            >
                                <div
                                    class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between"
                                >
                                    <div class="max-w-lg">
                                        <p
                                            class="mb-2 text-xs font-bold tracking-widest uppercase opacity-60"
                                        >
                                            Próximo passo
                                        </p>
                                        <h2
                                            class="text-3xl font-bold tracking-tight md:text-4xl"
                                        >
                                            Não sabe por onde começar?
                                        </h2>
                                        <p
                                            class="mt-3 text-lg leading-relaxed opacity-80"
                                        >
                                            Conta o seu desafio. A gente analisa
                                            e indica o melhor caminho — sem
                                            enrolação e sem custo.
                                        </p>
                                    </div>
                                    <div
                                        class="flex flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                                    >
                                        <Link
                                            :href="contact().url"
                                            class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                                        >
                                            <MessageSquare class="h-4 w-4" />
                                            Fala com a gente
                                        </Link>
                                        <a
                                            href="https://wa.me/5511982776725"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20"
                                        >
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
    </PublicLayout>
</template>

<style scoped>
/* ── Scroll reveal ──────────────────────────────────────── */
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
