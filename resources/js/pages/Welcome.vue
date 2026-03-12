<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import {
    LucideMonitor,
    LucideSettings2,
    LucideGlobe,
    LucideBrain,
    LucideCheckCircle2,
    LucideArrowRight,
    LucideSend,
} from 'lucide-vue-next';
import logo from '@/../images/logo.png';
import ProjectCard from '@/components/marketing/ProjectCard.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { projects as projectsStore } from '@/routes/public';
import { store as inquiryStore } from '@/routes/public/inquiry';

interface Project {
    title: string;
    category: string;
    description: string;
    tags: string[];
    highlights: string[];
}

const services = [
    {
        icon: LucideMonitor,
        title: 'Sistemas Web & ERPs',
        desc: 'Plataformas customizadas para o seu processo. Do MVP ao sistema enterprise.',
    },
    {
        icon: LucideSettings2,
        title: 'Plataformas Customizadas',
        desc: 'Soluções sob medida com arquitetura que escala junto com a sua operação.',
    },
    {
        icon: LucideGlobe,
        title: 'Sites & Landing Pages',
        desc: 'Presença digital que converte. Rápido, responsivo e estratégico.',
    },
    {
        icon: LucideBrain,
        title: 'Consultoria Técnica',
        desc: 'Avaliamos sua arquitetura e entregamos um plano concreto de evolução.',
    },
];

const projects: Project[] = [
    {
        title: 'Omniaflow — Gestão Educacional',
        category: 'ERP · Multi-tenant · Fintech',
        description:
            'Um ERP completo para redes de escolas, integrando secretaria, pedagógico e financeiro automátizado.',
        highlights: [
            'Multi-tenancy com isolamento total',
            'Integração Asaas (PIX/Boleto)',
            'Tempo real com WebSockets',
        ],
        tags: ['Laravel 12', 'Vue 3', 'Reverb'],
    },
    {
        title: 'CIM Base — Gestão Corporativa',
        category: 'SaaS · Corporativo · Gestão',
        description:
            'Plataforma robusta para gestão de leads e operações internas com controle sofisticado de permissões.',
        highlights: [
            'Arquitetura DDD com DTOs tipados',
            'Sistema RBAC granular',
            '50+ componentes UI acessíveis',
        ],
        tags: ['Laravel 12', 'Tailwind 4', 'TypeScript'],
    },
];

const differentials = [
    {
        title: 'Código que não expira',
        desc: 'Construído com as melhores práticas para durar anos sem precisar de refação.',
    },
    {
        title: 'Comunicação Clara',
        desc: 'Sem jargão técnico. Você entende cada passo do que estamos construindo.',
    },
    {
        title: 'Stack Moderno',
        desc: 'Laravel 12, Vue 3, Inertia.js e o que há de melhor no ecossistema atual.',
    },
    {
        title: 'Atendimento Direto',
        desc: 'Fala direto com quem coloca a mão no código. Sem intermediários.',
    },
];

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
            alert('Mensagem enviada com sucesso!');
        },
    });
};
</script>

<template>
    <Head title="Tecnologia que resolve" />

    <PublicLayout>
        <!-- Hero Section -->
        <section
            class="relative overflow-hidden px-6 pt-32 pb-20 md:pt-48 md:pb-32"
        >
            <!-- Background Decorative Elements -->
            <div
                class="pointer-events-none absolute top-0 left-1/2 -z-10 h-full w-full max-w-7xl -translate-x-1/2"
            >
                <div
                    class="absolute top-0 right-0 h-[500px] w-[500px] rounded-full bg-primary/5 blur-[120px]"
                />
                <div
                    class="absolute bottom-0 left-0 h-[400px] w-[400px] rounded-full bg-primary/5 blur-[100px]"
                />
            </div>

            <div class="mx-auto max-w-7xl">
                <div
                    class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center"
                >
                    <div class="max-w-3xl">
                        <div
                            class="mb-8 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-bold tracking-widest text-primary uppercase"
                        >
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary opacity-75"
                                ></span>
                                <span
                                    class="relative inline-flex h-2 w-2 rounded-full bg-primary"
                                ></span>
                            </span>
                            Desenvolvimento · Sistemas · Estratégia Digital
                        </div>

                        <h1
                            class="mb-8 text-5xl leading-[1.05] font-bold tracking-tight md:text-7xl"
                        >
                            Seu negócio merece <br />
                            <span class="text-primary/60"
                                >tecnologia feita do jeito certo.</span
                            >
                        </h1>

                        <p
                            class="mb-12 max-w-xl text-xl leading-relaxed text-muted-foreground"
                        >
                            A
                            <span class="font-semibold text-foreground italic"
                                >MC Marketing & Code</span
                            >
                            desenvolve sistemas e plataformas que funcionam de
                            verdade — com arquitetura sólida e foco total no seu
                            crescimento.
                        </p>

                        <div
                            class="flex flex-col items-center gap-4 sm:flex-row"
                        >
                            <a
                                href="#contato"
                                class="group flex w-full items-center justify-center gap-2 rounded-full bg-primary px-8 py-4 font-bold text-primary-foreground transition-all hover:scale-[1.02] active:scale-[0.98] sm:w-auto"
                            >
                                Fala com a gente
                                <LucideArrowRight
                                    class="h-5 w-5 transition-transform group-hover:translate-x-1"
                                />
                            </a>
                            <Link
                                :href="projectsStore().url"
                                class="flex w-full items-center justify-center gap-2 rounded-full border border-border bg-background px-8 py-4 font-bold transition-all hover:bg-muted sm:w-auto"
                            >
                                Ver nossos projetos
                            </Link>
                        </div>
                    </div>

                    <!-- Creative Logo Side -->
                    <div
                        class="relative hidden items-center justify-center lg:flex"
                    >
                        <div
                            class="absolute inset-0 scale-75 animate-pulse rounded-full bg-primary/20 blur-[100px]"
                        />
                        <div class="group relative">
                            <div
                                class="absolute -inset-4 rounded-[40px] bg-gradient-to-r from-primary/50 to-primary/10 opacity-20 blur-2xl transition-opacity group-hover:opacity-40"
                            />
                            <div class="animate-float relative rounded-[40px]">
                                <img
                                    :src="logo"
                                    alt="MC Logo Hero"
                                    class="h-120 w-120 object-contain drop-shadow-[0_0_20px_rgba(var(--primary),0.3)] filter"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Do -->
        <section id="servicos" class="bg-muted/30 px-6 py-24">
            <div class="mx-auto max-w-7xl">
                <div
                    class="grid grid-cols-1 items-center gap-20 lg:grid-cols-2"
                >
                    <div>
                        <h2 class="mb-8 text-4xl font-bold tracking-tight">
                            Tecnologia que resolve.<br /><span
                                class="text-muted-foreground"
                                >Não que complica.</span
                            >
                        </h2>
                        <p
                            class="mb-12 text-lg leading-relaxed text-muted-foreground"
                        >
                            Muita empresa já foi queimada por software que
                            prometeu tudo e entregou dor de cabeça. Na MC, o
                            processo é diferente: entendemos o seu negócio antes
                            de escrever uma linha de código.
                        </p>

                        <div
                            class="grid grid-cols-1 gap-8 text-sm sm:grid-cols-2"
                        >
                            <div
                                v-for="service in services"
                                :key="service.title"
                                class="flex flex-col gap-4"
                            >
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-border bg-background"
                                >
                                    <component
                                        :is="service.icon"
                                        class="h-6 w-6 text-primary"
                                    />
                                </div>
                                <div>
                                    <h4 class="mb-2 font-bold">
                                        {{ service.title }}
                                    </h4>
                                    <p
                                        class="leading-relaxed text-muted-foreground"
                                    >
                                        {{ service.desc }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Element or Image Placeholder -->
                    <div class="relative aspect-square">
                        <div
                            class="absolute inset-0 flex items-center justify-center overflow-hidden rounded-[4rem] bg-primary/5"
                        >
                            <!-- This would be an abstract glassmorphism UI element -->
                            <div
                                class="h-3/4 w-3/4 rotate-3 rounded-3xl border border-white/20 bg-background/40 p-8 shadow-2xl backdrop-blur-xl transition-transform duration-700 hover:rotate-0"
                            >
                                <div class="mb-6 h-8 w-full rounded bg-muted" />
                                <div class="mb-8 grid grid-cols-3 gap-4">
                                    <div
                                        class="h-20 rounded-2xl bg-primary/10"
                                    />
                                    <div
                                        class="h-20 rounded-2xl bg-primary/10"
                                    />
                                    <div
                                        class="h-20 rounded-2xl bg-primary/10"
                                    />
                                </div>
                                <div class="space-y-3">
                                    <div
                                        class="h-4 w-1/2 w-full rounded bg-muted"
                                    />
                                    <div class="h-4 w-full rounded bg-muted" />
                                    <div
                                        class="h-4 w-3/4 w-full rounded bg-muted"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why MC? -->
        <section class="overflow-hidden border-y border-border px-6 py-24">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto mb-20 max-w-2xl text-center">
                    <h2 class="mb-6 text-4xl font-bold tracking-tight">
                        A diferença está em como a gente pensa.
                    </h2>
                </div>

                <div
                    class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="diff in differentials"
                        :key="diff.title"
                        class="space-y-4"
                    >
                        <LucideCheckCircle2 class="h-8 w-8 text-primary" />
                        <h4 class="text-lg font-bold">{{ diff.title }}</h4>
                        <p
                            class="text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ diff.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projetos" class="px-6 py-24">
            <div class="mx-auto max-w-7xl">
                <div
                    class="mb-16 flex flex-col items-end justify-between gap-8 md:flex-row"
                >
                    <div class="max-w-2xl">
                        <h2 class="mb-6 text-4xl font-bold tracking-tight">
                            Projetos que falam por si.
                        </h2>
                        <p
                            class="text-lg leading-relaxed text-muted-foreground"
                        >
                            Cada entrega é construída pensando em quem vai usar
                            e em quem vai manter. Soluções reais em produção.
                        </p>
                    </div>
                    <a
                        href="#"
                        class="group flex items-center gap-2 font-bold transition-colors hover:text-primary"
                    >
                        Ver todos os projetos
                        <LucideArrowRight
                            class="h-5 w-5 transition-transform group-hover:translate-x-1"
                        />
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <ProjectCard
                        v-for="project in projects"
                        :key="project.title"
                        :project="project"
                    />
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section
            id="sobre"
            class="mx-6 my-12 rounded-[3rem] bg-primary px-6 py-24 text-primary-foreground"
        >
            <div class="mx-auto max-w-4xl py-12">
                <h2 class="mb-12 text-4xl font-bold tracking-tight">
                    Tecnologia com propósito.
                </h2>
                <div class="grid grid-cols-1 items-start gap-12 md:grid-cols-2">
                    <p class="text-lg leading-relaxed opacity-80">
                        A MC nasceu da convicção de que a maioria dos problemas
                        de negócio tem solução técnica elegante. O que falta é
                        um parceiro que entenda os dois lados — o do código e o
                        do negócio.
                    </p>
                    <p class="text-lg leading-relaxed opacity-80">
                        Somos especialistas em Laravel. Trabalhamos com sistemas
                        de grau enterprise: ERPs multi-tenant, plataformas
                        financeiras e monolitos modernos seguindo Domain-Driven
                        Design.
                    </p>
                </div>
                <div class="mt-20 border-t border-white/10 pt-12">
                    <p
                        class="text-3xl leading-snug font-medium tracking-tight italic"
                    >
                        "Não entregamos software. Entregamos sistemas em que o
                        seu negócio pode confiar."
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Form -->
        <section id="contato" class="px-6 py-32">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-20 lg:grid-cols-2">
                    <div>
                        <h2 class="mb-8 text-5xl font-bold tracking-tight">
                            Bora conversar.
                        </h2>
                        <p
                            class="mb-12 text-xl leading-relaxed text-muted-foreground"
                        >
                            Tem um projeto, uma dúvida ou só quer entender se
                            faz sentido trabalhar juntos? Manda uma mensagem.
                            Respondemos rápido.
                        </p>

                        <div class="space-y-6">
                            <a
                                href="https:t.me/flaviomoreir4"
                                class="group block rounded-2xl border border-border p-4 transition-all hover:border-primary/20"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/5 text-primary"
                                    >
                                        <!-- Telegram Icon placeholder -->
                                        <LucideSend class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold">Telegram</h4>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Resposta mais rápida
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <a
                                href="https://wa.me/5511982776725"
                                class="group block rounded-2xl border border-border p-4 transition-all hover:border-primary/20"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/5 text-primary"
                                    >
                                        <LucideGlobe class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold">WhatsApp</h4>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Conversa direta
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-[2.5rem] border border-border bg-card p-10 shadow-sm"
                    >
                        <form
                            @submit.prevent="submit"
                            class="relative z-10 space-y-6"
                        >
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold"
                                        >Seu nome</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Nome completo"
                                        class="w-full rounded-2xl border-none bg-muted/50 p-4 transition-all outline-none focus:ring-2 focus:ring-primary"
                                        required
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold"
                                        >E-mail</label
                                    >
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        placeholder="seu@email.com"
                                        class="w-full rounded-2xl border-none bg-muted/50 p-4 transition-all outline-none focus:ring-2 focus:ring-primary"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold"
                                    >WhatsApp (Opcional)</label
                                >
                                <input
                                    v-model="form.whatsapp"
                                    type="text"
                                    placeholder="(00) 00000-0000"
                                    class="w-full rounded-2xl border-none bg-muted/50 p-4 transition-all outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold"
                                    >Como podemos ajudar?</label
                                >
                                <textarea
                                    v-model="form.message"
                                    rows="4"
                                    placeholder="Conte um pouco sobre o seu projeto..."
                                    class="w-full resize-none rounded-2xl border-none bg-muted/50 p-4 transition-all outline-none focus:ring-2 focus:ring-primary"
                                    required
                                ></textarea>
                            </div>

                            <button
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-primary py-5 font-bold text-primary-foreground transition-all hover:opacity-90 disabled:opacity-50"
                            >
                                <span v-if="form.processing">Enviando...</span>
                                <span v-else class="flex items-center gap-2"
                                    >Enviar mensagem
                                    <LucideSend class="h-5 w-5"
                                /></span>
                            </button>
                        </form>
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
        transform: translateY(-20px);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>
