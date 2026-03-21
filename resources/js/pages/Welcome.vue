<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import {
    LucideMonitor,
    LucideSettings2,
    LucideGlobe,
    LucideBrain,
    LucideArrowRight,
    LucideSend,
    LucideLayout,
    LucideLayers,
    LucideCode2,
    LucideServer,
    LucideDatabase,
    LucideCpu,
    LucideSmartphone,
    LucideShield,
    LucideBarChart2,
    LucideZap,
    LucidePackage,
    LucideCheckCircle2,
    LucideMessageSquare,
    LucidePhone,
    LucideX,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import logo from '@/../images/logo.png';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { projects as projectsStore } from '@/routes/public';
import { store as inquiryStore } from '@/routes/public/inquiry';
import type { PublicProject, SeoData, Service } from '@/types';

interface Props {
    projects: PublicProject[];
    services: Service[];
    seo: SeoData;
}
const props = defineProps<Props>();

// ─── Icon map ─────────────────────────────────────────────────────────────────
const iconMap: Record<string, unknown> = {
    monitor: LucideMonitor,
    layers: LucideLayers,
    layout: LucideLayout,
    globe: LucideGlobe,
    'search-code': LucideCode2,
    'settings-2': LucideSettings2,
    brain: LucideBrain,
    'code-2': LucideCode2,
    server: LucideServer,
    database: LucideDatabase,
    cpu: LucideCpu,
    smartphone: LucideSmartphone,
    shield: LucideShield,
    'bar-chart': LucideBarChart2,
    zap: LucideZap,
    package: LucidePackage,
};
const getIcon = (icon: string) => iconMap[icon] ?? LucideMonitor;

// ─── Stats ────────────────────────────────────────────────────────────────────
const stats = [
    { value: '15+', label: 'Anos de experiência' },
    { value: '40+', label: 'Projetos entregues' },
    { value: '100%', label: 'Laravel, Filament & Vue' },
    { value: '<24h', label: 'Tempo de resposta' },
];

// ─── Tech strip ───────────────────────────────────────────────────────────────
const techStack = [
    'Laravel',
    'Vue 3',
    'Inertia.js',
    'Filament',
    'Tailwind CSS',
    'PostgreSQL',
    'Redis',
    'Docker',
    'AWS',
    'TypeScript',
    'Livewire',
    'MySQL',
];

// ─── Differentials ────────────────────────────────────────────────────────────
const differentials = [
    {
        number: '01',
        title: 'Sênior de verdade',
        desc: 'Você fala diretamente com quem escreve o código — não com intermediário repassando tarefa.',
    },
    {
        number: '02',
        title: 'Código que não expira',
        desc: 'Quinze anos de cicatrizes ensinaram o que não envelhece. Boas práticas não são perfeccionismo — são respeito pelo futuro.',
    },
    {
        number: '03',
        title: 'Comunicação sem jargão',
        desc: 'Você entende cada etapa. Sem "sprint planning" opaco nem "velocity" como desculpa para atraso.',
    },
    {
        number: '04',
        title: 'Stack que dura',
        desc: 'Laravel 12, Filament, Vue 3, Inertia.js. Escolhas feitas pra resolver o seu problema — não pra impressionar em entrevista.',
    },
];

// ─── Scroll reveal ────────────────────────────────────────────────────────────
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -60px 0px' },
    );
    document.querySelectorAll('.reveal').forEach((el) => observer?.observe(el));
});

onUnmounted(() => observer?.disconnect());

// ─── Contact form ─────────────────────────────────────────────────────────────
const formSuccess = ref(false);

const form = useForm({
    name: '',
    email: '',
    message: '',
    whatsapp: '',
});

const submit = () => {
    form.post(inquiryStore().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            formSuccess.value = true;
        },
    });
};
</script>

<template>
    <SeoHead v-bind="props.seo" />

    <PublicLayout>
        <!-- ══════════════════════════════════════════════
            HERO
        ══════════════════════════════════════════════ -->
        <section
            class="relative overflow-hidden px-6 pt-32 pb-20 md:pt-48 md:pb-28"
        >
            <!-- Glow blobs -->
            <div
                class="pointer-events-none absolute inset-0 -z-10 overflow-hidden"
            >
                <div
                    class="absolute top-0 right-0 h-[600px] w-[600px] translate-x-1/4 -translate-y-1/4 rounded-full bg-primary/6 blur-[140px]"
                />
                <div
                    class="absolute bottom-0 left-0 h-[400px] w-[400px] -translate-x-1/4 translate-y-1/4 rounded-full bg-primary/4 blur-[120px]"
                />
            </div>

            <div class="mx-auto max-w-7xl">
                <div
                    class="grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center"
                >
                    <!-- Copy -->
                    <div>
                        <div
                            class="mb-8 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold tracking-widest text-primary uppercase"
                        >
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary opacity-75"
                                />
                                <span
                                    class="relative inline-flex h-2 w-2 rounded-full bg-primary"
                                />
                            </span>
                            Desenvolvimento · Sistemas · Estratégia Digital
                        </div>

                        <h1
                            class="mb-6 text-5xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                        >
                            Seu negócio merece<br />
                            <span class="text-primary/60"
                                >tecnologia feita<br />do jeito certo.</span
                            >
                        </h1>

                        <p
                            class="mb-10 max-w-xl text-xl leading-relaxed text-muted-foreground"
                        >
                            A
                            <span class="font-semibold text-foreground italic"
                                >MC Marketing & Code</span
                            >
                            é conduzida por um desenvolvedor sênior com 15+ anos
                            de campo — que viu o PHP nascer, sobreviveu ao
                            jQuery e hoje entrega sistemas enterprise em Laravel
                            sem intermediários.
                        </p>

                        <div
                            class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center"
                        >
                            <a
                                href="#contato"
                                class="group flex items-center justify-center gap-2 rounded-full bg-primary px-8 py-4 font-bold text-primary-foreground transition-all hover:scale-[1.02] active:scale-[0.98]"
                            >
                                Fala com a gente
                                <LucideArrowRight
                                    class="h-5 w-5 transition-transform group-hover:translate-x-1"
                                />
                            </a>
                            <Link
                                :href="projectsStore().url"
                                class="flex items-center justify-center gap-2 rounded-full border border-border bg-background px-8 py-4 font-bold transition-all hover:bg-muted"
                            >
                                Ver projetos
                            </Link>
                        </div>

                        <!-- Stats row -->
                        <div
                            class="mt-12 grid grid-cols-2 gap-6 border-t border-border pt-10 sm:grid-cols-4"
                        >
                            <div
                                v-for="stat in stats"
                                :key="stat.label"
                                class="space-y-1"
                            >
                                <p class="text-2xl font-bold tracking-tight">
                                    {{ stat.value }}
                                </p>
                                <p
                                    class="text-xs leading-snug text-muted-foreground"
                                >
                                    {{ stat.label }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hero visual -->
                    <div class="relative flex items-center justify-center">
                        <!-- Desktop: floating logo -->
                        <div class="relative hidden lg:block">
                            <div
                                class="absolute inset-0 scale-75 animate-pulse rounded-full bg-primary/15 blur-[100px]"
                            />
                            <div class="group animate-float relative">
                                <div
                                    class="absolute -inset-4 rounded-[40px] bg-gradient-to-r from-primary/40 to-primary/10 opacity-20 blur-2xl transition-opacity group-hover:opacity-40"
                                />
                                <img
                                    :src="logo"
                                    alt=""
                                    aria-hidden="true"
                                    class="relative h-96 w-96 object-contain drop-shadow-[0_0_40px_hsl(var(--primary)/0.25)]"
                                />
                            </div>
                        </div>

                        <!-- Mobile: tech cloud -->
                        <div class="w-full lg:hidden">
                            <div
                                class="relative mx-auto flex max-w-sm flex-wrap justify-center gap-2 py-4"
                            >
                                <span
                                    v-for="(tech, i) in techStack"
                                    :key="tech"
                                    class="rounded-full border border-border bg-muted/60 px-3 py-1.5 text-xs font-medium text-muted-foreground backdrop-blur-sm"
                                    :style="{ animationDelay: `${i * 0.15}s` }"
                                >
                                    {{ tech }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             TECH MARQUEE STRIP
        ══════════════════════════════════════════════ -->
        <div
            class="overflow-hidden border-y border-border bg-muted/20 py-4"
            aria-hidden="true"
        >
            <div
                class="marquee-track flex gap-12 text-sm font-medium whitespace-nowrap text-muted-foreground"
            >
                <span
                    v-for="(tech, index) in [...techStack, ...techStack]"
                    :key="index"
                    class="flex shrink-0 items-center gap-2"
                >
                    <span class="h-1 w-1 rounded-full bg-primary/50" />
                    {{ tech }}
                </span>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════
             SERVICES
        ══════════════════════════════════════════════ -->
        <section id="servicos" class="px-6 py-24 md:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="reveal mb-16 max-w-2xl">
                    <p
                        class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Serviços
                    </p>
                    <h2 class="text-4xl font-bold tracking-tight md:text-5xl">
                        Tecnologia que resolve.<br />
                        <span class="text-muted-foreground"
                            >Não que complica.</span
                        >
                    </h2>
                    <p
                        class="mt-6 text-lg leading-relaxed text-muted-foreground"
                    >
                        Muita empresa já foi queimada por software que prometeu
                        tudo e entregou dor de cabeça. Quinze anos de projeto
                        ensinam a ouvir o problema antes de abrir o editor.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="(service, i) in services"
                        :key="service.title"
                        class="reveal group rounded-3xl border border-border bg-card p-7 transition-all duration-300 hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5"
                        :style="{ '--reveal-delay': `${i * 60}ms` }"
                    >
                        <div
                            class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl border border-border bg-muted transition-colors group-hover:border-primary/30 group-hover:bg-primary/5"
                        >
                            <component
                                :is="getIcon(service.icon)"
                                class="h-6 w-6 text-primary"
                            />
                        </div>
                        <h4 class="mb-2 text-lg font-bold">
                            {{ service.title }}
                        </h4>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ service.description }}
                        </p>
                        <ul
                            v-if="service.features?.length"
                            class="mt-4 space-y-1.5"
                        >
                            <li
                                v-for="feature in service.features.slice(0, 3)"
                                :key="feature.item"
                                class="flex items-center gap-2 text-xs text-muted-foreground"
                            >
                                <LucideCheckCircle2
                                    class="h-3.5 w-3.5 shrink-0 text-primary/60"
                                />
                                {{ feature.item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             DIFFERENTIALS
        ══════════════════════════════════════════════ -->
        <section class="border-y border-border bg-muted/20 px-6 py-24 md:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="reveal mb-20 max-w-2xl">
                    <p
                        class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Por que a MC
                    </p>
                    <h2 class="text-4xl font-bold tracking-tight md:text-5xl">
                        A diferença está em<br />como a gente pensa.
                    </h2>
                </div>

                <div
                    class="grid grid-cols-1 gap-0 divide-y divide-border md:grid-cols-2 md:divide-x md:divide-y-0"
                >
                    <div
                        v-for="(diff, i) in differentials"
                        :key="diff.title"
                        class="reveal group flex gap-6 p-8 transition-colors hover:bg-muted/40 md:p-10"
                        :style="{ '--reveal-delay': `${i * 80}ms` }"
                        :class="{ 'md:border-b md:border-border': i < 2 }"
                    >
                        <span
                            class="mt-0.5 shrink-0 text-5xl font-bold text-border transition-colors group-hover:text-primary/20 md:text-6xl"
                        >
                            {{ diff.number }}
                        </span>
                        <div>
                            <h4 class="mb-2 text-xl font-bold">
                                {{ diff.title }}
                            </h4>
                            <p class="leading-relaxed text-muted-foreground">
                                {{ diff.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             PROJECTS
        ══════════════════════════════════════════════ -->
        <section id="projetos" class="px-6 py-24 md:py-32">
            <div class="mx-auto max-w-7xl">
                <div
                    class="reveal mb-16 flex flex-col gap-6 md:flex-row md:items-end md:justify-between"
                >
                    <div class="max-w-2xl">
                        <p
                            class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                        >
                            Projetos
                        </p>
                        <h2
                            class="text-4xl font-bold tracking-tight md:text-5xl"
                        >
                            Projetos que falam por si.
                        </h2>
                        <p
                            class="mt-4 text-lg leading-relaxed text-muted-foreground"
                        >
                            Cada entrega é construída pensando em quem vai usar
                            e em quem vai manter. Nenhum mockup bonito que nunca
                            saiu do Figma.
                        </p>
                    </div>
                    <Link
                        :href="projectsStore().url"
                        class="group flex shrink-0 items-center gap-2 font-bold transition-colors hover:text-primary"
                    >
                        Ver todos os projetos
                        <LucideArrowRight
                            class="h-5 w-5 transition-transform group-hover:translate-x-1"
                        />
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div
                        v-for="(project, i) in props.projects"
                        :key="i"
                        class="reveal"
                        :style="{ '--reveal-delay': `${i * 80}ms` }"
                    >
                        <ProjectCard :project="project" :with-cover="false" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             ABOUT
        ══════════════════════════════════════════════ -->
        <section id="sobre" class="px-6 pb-24 md:pb-32">
            <div class="mx-auto max-w-7xl">
                <div
                    class="reveal overflow-hidden rounded-[2.5rem] bg-primary px-8 py-16 text-primary-foreground md:rounded-[3rem] md:px-16 md:py-24"
                >
                    <div class="max-w-4xl">
                        <p
                            class="mb-4 text-xs font-bold tracking-widest uppercase opacity-60"
                        >
                            Sobre a MC
                        </p>
                        <h2
                            class="mb-12 text-4xl font-bold tracking-tight md:text-5xl"
                        >
                            Tecnologia com propósito.
                        </h2>

                        <div
                            class="grid grid-cols-1 gap-8 md:grid-cols-2 md:gap-12"
                        >
                            <p class="text-lg leading-relaxed opacity-80">
                                A MC é conduzida por
                                <strong class="font-semibold opacity-100"
                                    >Flávio Moreira</strong
                                >, desenvolvedor tão antigo quanto o PHP — que
                                viu o JavaScript dar os primeiros passos,
                                sobreviveu à era pré-PSR, tem memória afetiva
                                com jQuery e Bootstrap, e hoje constrói sistemas
                                enterprise com a mesma atenção de sempre.
                            </p>
                            <p class="text-lg leading-relaxed opacity-80">
                                Essa bagagem não é nostalgia — é contexto. Quem
                                entende por que as ferramentas de hoje existem
                                toma decisões melhores. Especialistas em
                                Laravel, DDD, ERPs multi-tenant e plataformas
                                que precisam funcionar de verdade, por anos.
                            </p>
                        </div>

                        <!-- Credential strip -->
                        <div
                            class="mt-14 grid grid-cols-2 gap-6 border-t border-white/10 pt-12 sm:grid-cols-4"
                        >
                            <div
                                v-for="stat in stats"
                                :key="stat.label"
                                class="space-y-1"
                            >
                                <p class="text-2xl font-bold">
                                    {{ stat.value }}
                                </p>
                                <p class="text-xs leading-snug opacity-60">
                                    {{ stat.label }}
                                </p>
                            </div>
                        </div>

                        <blockquote
                            class="mt-14 border-t border-white/10 pt-12"
                        >
                            <p
                                class="text-2xl leading-snug font-medium tracking-tight italic md:text-3xl"
                            >
                                "Não entregamos software. Entregamos sistemas em
                                que o seu negócio pode confiar."
                            </p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════
             CONTACT
        ══════════════════════════════════════════════ -->
        <section
            id="contato"
            class="border-t border-border bg-muted/20 px-6 py-24 md:py-32"
        >
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-16 lg:grid-cols-2">
                    <!-- Left: copy + channels -->
                    <div class="reveal">
                        <p
                            class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                        >
                            Contato
                        </p>
                        <h2
                            class="mb-6 text-4xl font-bold tracking-tight md:text-5xl"
                        >
                            Bora conversar.
                        </h2>
                        <p
                            class="mb-12 text-xl leading-relaxed text-muted-foreground"
                        >
                            Tem um projeto, uma dúvida ou só quer entender se
                            faz sentido trabalhar juntos? Manda uma mensagem —
                            você fala direto com o Flávio. Sem filtro, sem
                            ticket, sem espera.
                        </p>

                        <div class="space-y-4">
                            <a
                                href="https://t.me/flaviomoreir4"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-primary/30 hover:shadow-md"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/5 text-primary transition-colors group-hover:bg-primary/10"
                                >
                                    <LucideSend class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold">Telegram</h4>
                                    <p class="text-sm text-muted-foreground">
                                        @flaviomoreir4 · Resposta mais rápida
                                    </p>
                                </div>
                                <LucideArrowRight
                                    class="ml-auto h-4 w-4 text-muted-foreground/40 transition-transform group-hover:translate-x-1 group-hover:text-primary"
                                />
                            </a>

                            <a
                                href="https://wa.me/5511982776725"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-primary/30 hover:shadow-md"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/5 text-primary transition-colors group-hover:bg-primary/10"
                                >
                                    <LucidePhone class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold">WhatsApp</h4>
                                    <p class="text-sm text-muted-foreground">
                                        +55 11 98277-6725 · Conversa direta
                                    </p>
                                </div>
                                <LucideArrowRight
                                    class="ml-auto h-4 w-4 text-muted-foreground/40 transition-transform group-hover:translate-x-1 group-hover:text-primary"
                                />
                            </a>

                            <a
                                href="mailto:flavio.moreira@mktcode.digital"
                                class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-primary/30 hover:shadow-md"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/5 text-primary transition-colors group-hover:bg-primary/10"
                                >
                                    <LucideMessageSquare class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold">E-mail</h4>
                                    <p
                                        class="truncate text-sm text-muted-foreground"
                                    >
                                        flavio.moreira@mktcode.digital
                                    </p>
                                </div>
                                <LucideArrowRight
                                    class="ml-auto h-4 w-4 text-muted-foreground/40 transition-transform group-hover:translate-x-1 group-hover:text-primary"
                                />
                            </a>
                        </div>
                    </div>

                    <!-- Right: form -->
                    <div class="reveal" style="--reveal-delay: 120ms">
                        <div
                            class="relative overflow-hidden rounded-[2rem] border border-border bg-card p-8 shadow-sm md:p-10"
                        >
                            <!-- Success state -->
                            <Transition
                                enter-active-class="transition-all duration-300"
                                enter-from-class="opacity-0 scale-95"
                                leave-active-class="transition-all duration-200"
                                leave-to-class="opacity-0 scale-95"
                            >
                                <div
                                    v-if="formSuccess"
                                    class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-4 rounded-[2rem] bg-card p-10 text-center"
                                >
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <LucideCheckCircle2
                                            class="h-8 w-8 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">
                                            Mensagem enviada!
                                        </h3>
                                        <p class="mt-2 text-muted-foreground">
                                            Obrigado pelo contato. Retornaremos
                                            em até 24h.
                                        </p>
                                    </div>
                                    <button
                                        class="mt-2 text-sm font-medium text-primary hover:underline"
                                        @click="formSuccess = false"
                                    >
                                        Enviar outra mensagem
                                    </button>
                                </div>
                            </Transition>

                            <form
                                @submit.prevent="submit"
                                class="space-y-5"
                                novalidate
                            >
                                <div
                                    class="grid grid-cols-1 gap-5 sm:grid-cols-2"
                                >
                                    <div class="space-y-1.5">
                                        <label
                                            for="contact-name"
                                            class="text-sm font-semibold"
                                        >
                                            Seu nome
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input
                                            id="contact-name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="Nome completo"
                                            autocomplete="name"
                                            required
                                            class="w-full rounded-xl border bg-muted/40 px-4 py-3 text-sm transition-all outline-none placeholder:text-muted-foreground/50 focus:border-primary/40 focus:ring-2 focus:ring-primary/20"
                                            :class="
                                                form.errors.name
                                                    ? 'border-destructive'
                                                    : 'border-transparent'
                                            "
                                        />
                                        <p
                                            v-if="form.errors.name"
                                            class="flex items-center gap-1 text-xs text-destructive"
                                        >
                                            <LucideX class="h-3 w-3" />{{
                                                form.errors.name
                                            }}
                                        </p>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            for="contact-email"
                                            class="text-sm font-semibold"
                                        >
                                            E-mail
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input
                                            id="contact-email"
                                            v-model="form.email"
                                            type="email"
                                            placeholder="seu@email.com"
                                            autocomplete="email"
                                            required
                                            class="w-full rounded-xl border bg-muted/40 px-4 py-3 text-sm transition-all outline-none placeholder:text-muted-foreground/50 focus:border-primary/40 focus:ring-2 focus:ring-primary/20"
                                            :class="
                                                form.errors.email
                                                    ? 'border-destructive'
                                                    : 'border-transparent'
                                            "
                                        />
                                        <p
                                            v-if="form.errors.email"
                                            class="flex items-center gap-1 text-xs text-destructive"
                                        >
                                            <LucideX class="h-3 w-3" />{{
                                                form.errors.email
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        for="contact-whatsapp"
                                        class="text-sm font-semibold"
                                    >
                                        WhatsApp
                                        <span
                                            class="text-xs font-normal text-muted-foreground"
                                            >(opcional)</span
                                        >
                                    </label>
                                    <input
                                        id="contact-whatsapp"
                                        v-model="form.whatsapp"
                                        type="tel"
                                        placeholder="(11) 99999-9999"
                                        autocomplete="tel"
                                        class="w-full rounded-xl border border-transparent bg-muted/40 px-4 py-3 text-sm transition-all outline-none placeholder:text-muted-foreground/50 focus:border-primary/40 focus:ring-2 focus:ring-primary/20"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        for="contact-message"
                                        class="text-sm font-semibold"
                                    >
                                        Como posso ajudar?
                                        <span class="text-primary">*</span>
                                    </label>
                                    <textarea
                                        id="contact-message"
                                        v-model="form.message"
                                        rows="4"
                                        placeholder="Conte um pouco sobre o seu projeto, desafio ou ideia. Quanto mais contexto, melhor a resposta."
                                        required
                                        class="w-full resize-none rounded-xl border bg-muted/40 px-4 py-3 text-sm transition-all outline-none placeholder:text-muted-foreground/50 focus:border-primary/40 focus:ring-2 focus:ring-primary/20"
                                        :class="
                                            form.errors.message
                                                ? 'border-destructive'
                                                : 'border-transparent'
                                        "
                                    />
                                    <p
                                        v-if="form.errors.message"
                                        class="flex items-center gap-1 text-xs text-destructive"
                                    >
                                        <LucideX class="h-3 w-3" />{{
                                            form.errors.message
                                        }}
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-4 font-bold text-primary-foreground transition-all hover:opacity-90 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="flex items-center gap-2"
                                    >
                                        <svg
                                            class="h-4 w-4 animate-spin"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            />
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8v8H4z"
                                            />
                                        </svg>
                                        Enviando...
                                    </span>
                                    <span
                                        v-else
                                        class="flex items-center gap-2"
                                    >
                                        Enviar mensagem
                                        <LucideSend class="h-4 w-4" />
                                    </span>
                                </button>

                                <p
                                    class="text-center text-xs text-muted-foreground"
                                >
                                    Respondemos em até 24h. Sem spam, prometido.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-18px);
    }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes marquee {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}
.marquee-track {
    animation: marquee 28s linear infinite;
    width: max-content;
}

.reveal {
    opacity: 0;
    transform: translateY(24px);
    transition:
        opacity 0.55s ease,
        transform 0.55s ease;
    transition-delay: var(--reveal-delay, 0ms);
}
.reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
