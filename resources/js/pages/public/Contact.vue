<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {
    Send,
    MessageSquare,
    Mail,
    Phone,
    CheckCircle2,
    Loader2,
    ArrowRight,
    X,
    Clock,
    Shield,
    Zap,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { store as inquiryStore } from '@/routes/public/inquiry';
import type { SeoData } from '@/types';

interface Props {
    seo: SeoData;
}

const props = defineProps<Props>();

// ─── Form ─────────────────────────────────────────────────────────────────────
const formSuccess = ref(false);

const form = useForm({
    name: '',
    email: '',
    whatsapp: '',
    message: '',
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

// ─── Contact channels ─────────────────────────────────────────────────────────
const contacts = [
    {
        name: 'Telegram',
        value: '@flaviomoreir4',
        label: 'Resposta mais rápida',
        icon: MessageSquare,
        href: 'https://t.me/flaviomoreir4',
        external: true,
    },
    {
        name: 'WhatsApp',
        value: '+55 11 98277-6725',
        label: 'Conversa direta',
        icon: Phone,
        href: 'https://wa.me/5511982776725',
        external: true,
    },
    {
        name: 'E-mail',
        value: 'flavio.moreira@mktcode.digital',
        label: 'Para projetos formais',
        icon: Mail,
        href: 'mailto:flavio.moreira@mktcode.digital',
        external: false,
    },
];

// ─── Trust indicators ─────────────────────────────────────────────────────────
const trust = [
    { icon: Zap, label: 'Resposta no mesmo dia' },
    { icon: Shield, label: 'Sem compromisso' },
    { icon: Clock, label: 'Orçamento gratuito' },
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
        { threshold: 0.07, rootMargin: '0px 0px -40px 0px' },
    );
    document.querySelectorAll('.reveal').forEach((el) => observer?.observe(el));
});

onUnmounted(() => observer?.disconnect());
</script>

<template>
    <SeoHead v-bind="props.seo" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-32">
            <div class="mx-auto max-w-7xl">
                <!-- ── Page header ─────────────────────────────────────── -->
                <div class="reveal mb-20 max-w-2xl">
                    <p
                        class="mb-3 text-xs font-bold tracking-widest text-primary uppercase"
                    >
                        Contato
                    </p>
                    <h1
                        class="text-4xl leading-[1.05] font-bold tracking-tight md:text-6xl"
                    >
                        Bora conversar.
                    </h1>
                    <p
                        class="mt-5 text-lg leading-relaxed text-muted-foreground"
                    >
                        Tem um projeto, uma dúvida ou só quer entender se faz
                        sentido trabalhar juntos? Manda uma mensagem. Sem
                        enrolação.
                    </p>

                    <!-- Trust pills -->
                    <div class="mt-8 flex flex-wrap gap-3">
                        <div
                            v-for="item in trust"
                            :key="item.label"
                            class="flex items-center gap-2 rounded-full border border-border bg-muted/40 px-4 py-2 text-sm font-medium"
                        >
                            <component
                                :is="item.icon"
                                class="h-3.5 w-3.5 text-primary"
                            />
                            {{ item.label }}
                        </div>
                    </div>
                </div>

                <!-- ── Main grid ───────────────────────────────────────── -->
                <div
                    class="grid grid-cols-1 items-start gap-12 lg:grid-cols-12"
                >
                    <!-- Left: channels + copy ───────────────────────── -->
                    <div class="space-y-10 lg:col-span-5">
                        <!-- Channel cards -->
                        <div class="reveal space-y-3">
                            <a
                                v-for="c in contacts"
                                :key="c.name"
                                :href="c.href"
                                :target="c.external ? '_blank' : undefined"
                                :rel="
                                    c.external
                                        ? 'noopener noreferrer'
                                        : undefined
                                "
                                class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all duration-200 hover:border-primary/30 hover:shadow-md"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/8 text-primary transition-colors group-hover:bg-primary/12"
                                >
                                    <component :is="c.icon" class="h-5 w-5" />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div
                                        class="flex items-center justify-between gap-2"
                                    >
                                        <h4 class="font-bold">{{ c.name }}</h4>
                                        <span
                                            class="shrink-0 text-[10px] font-bold tracking-wider text-primary/60 uppercase opacity-0 transition-opacity group-hover:opacity-100"
                                        >
                                            {{ c.label }}
                                        </span>
                                    </div>
                                    <p
                                        class="truncate text-sm text-muted-foreground"
                                    >
                                        {{ c.value }}
                                    </p>
                                </div>

                                <ArrowRight
                                    class="h-4 w-4 shrink-0 text-muted-foreground/40 transition-transform group-hover:translate-x-0.5 group-hover:text-primary"
                                />
                            </a>
                        </div>

                        <!-- Quote / microcopy -->
                        <div
                            class="reveal rounded-2xl border-l-2 border-primary/40 bg-muted/30 px-5 py-4"
                        >
                            <p
                                class="text-sm leading-relaxed text-muted-foreground italic"
                            >
                                "Cada grande sistema começou com uma conversa
                                simples."
                            </p>
                        </div>

                        <!-- FAQ lightweight -->
                        <div class="reveal space-y-5">
                            <h3
                                class="text-sm font-bold tracking-widest text-muted-foreground uppercase"
                            >
                                Perguntas frequentes
                            </h3>
                            <div class="space-y-4 text-sm">
                                <div>
                                    <p class="font-semibold">
                                        Como funciona o processo?
                                    </p>
                                    <p
                                        class="mt-1 leading-relaxed text-muted-foreground"
                                    >
                                        Você manda uma mensagem, a gente entende
                                        o contexto e monta uma proposta clara —
                                        sem surpresas no orçamento.
                                    </p>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        Trabalham com projetos pequenos?
                                    </p>
                                    <p
                                        class="mt-1 leading-relaxed text-muted-foreground"
                                    >
                                        Depende do escopo. O que não fazemos é
                                        trabalho de baixa qualidade —
                                        independente do tamanho.
                                    </p>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        Quanto tempo leva uma resposta?
                                    </p>
                                    <p
                                        class="mt-1 leading-relaxed text-muted-foreground"
                                    >
                                        Geralmente no mesmo dia. Telegram é o
                                        canal mais rápido.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: form ──────────────────────────────────── -->
                    <div
                        class="reveal lg:col-span-7"
                        style="--reveal-delay: 100ms"
                    >
                        <div
                            class="relative overflow-hidden rounded-[2rem] border border-border bg-card p-8 shadow-lg shadow-primary/5 md:p-10"
                        >
                            <!-- Success overlay -->
                            <Transition
                                enter-active-class="transition-all duration-300"
                                enter-from-class="opacity-0 scale-95"
                                leave-active-class="transition-all duration-200"
                                leave-to-class="opacity-0 scale-95"
                            >
                                <div
                                    v-if="formSuccess"
                                    class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-4 rounded-[2rem] bg-card p-10 text-center"
                                >
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <CheckCircle2
                                            class="h-8 w-8 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">
                                            Mensagem enviada!
                                        </h3>
                                        <p
                                            class="mt-2 max-w-xs text-muted-foreground"
                                        >
                                            Obrigado pelo contato. Respondemos
                                            em até 24h — geralmente bem antes
                                            disso.
                                        </p>
                                    </div>
                                    <button
                                        class="mt-1 text-sm font-bold text-primary hover:underline"
                                        @click="formSuccess = false"
                                    >
                                        Enviar outra mensagem
                                    </button>
                                </div>
                            </Transition>

                            <!-- Form header -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold tracking-tight">
                                    Manda uma mensagem
                                </h2>
                                <p class="mt-1.5 text-sm text-muted-foreground">
                                    Todos os campos marcados com
                                    <span class="font-bold text-primary"
                                        >*</span
                                    >
                                    são obrigatórios.
                                </p>
                            </div>

                            <form
                                @submit.prevent="submit"
                                class="space-y-5"
                                novalidate
                            >
                                <!-- Name + Email -->
                                <div
                                    class="grid grid-cols-1 gap-5 sm:grid-cols-2"
                                >
                                    <div class="space-y-1.5">
                                        <label
                                            for="contact-name"
                                            class="text-sm font-semibold"
                                        >
                                            Nome completo
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input
                                            id="contact-name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="Como podemos te chamar?"
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
                                            <X class="h-3 w-3 shrink-0" />{{
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
                                            <X class="h-3 w-3 shrink-0" />{{
                                                form.errors.email
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="space-y-1.5">
                                    <label
                                        for="contact-whatsapp"
                                        class="text-sm font-semibold"
                                    >
                                        WhatsApp
                                        <span
                                            class="text-xs font-normal text-muted-foreground"
                                            >(opcional — para contato mais
                                            ágil)</span
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
                                    <p
                                        v-if="form.errors.whatsapp"
                                        class="flex items-center gap-1 text-xs text-destructive"
                                    >
                                        <X class="h-3 w-3 shrink-0" />{{
                                            form.errors.whatsapp
                                        }}
                                    </p>
                                </div>

                                <!-- Message -->
                                <div class="space-y-1.5">
                                    <label
                                        for="contact-message"
                                        class="text-sm font-semibold"
                                    >
                                        Como podemos ajudar?
                                        <span class="text-primary">*</span>
                                    </label>
                                    <textarea
                                        id="contact-message"
                                        v-model="form.message"
                                        rows="5"
                                        placeholder="Conte um pouco sobre o seu desafio, projeto ou ideia. Quanto mais contexto, melhor a nossa resposta."
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
                                        <X class="h-3 w-3 shrink-0" />{{
                                            form.errors.message
                                        }}
                                    </p>
                                </div>

                                <!-- Submit -->
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-4 font-bold text-primary-foreground transition-all hover:opacity-90 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="flex items-center gap-2"
                                    >
                                        <Loader2 class="h-4 w-4 animate-spin" />
                                        Enviando...
                                    </span>
                                    <span
                                        v-else
                                        class="flex items-center gap-2"
                                    >
                                        Enviar mensagem
                                        <Send class="h-4 w-4" />
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

                <!-- ── Bottom CTA ──────────────────────────────────────── -->
                <div class="reveal mt-24">
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
                                    Prefere ir direto
                                </p>
                                <h2
                                    class="text-3xl font-bold tracking-tight md:text-4xl"
                                >
                                    Prefere falar agora?
                                </h2>
                                <p
                                    class="mt-3 text-lg leading-relaxed opacity-80"
                                >
                                    Telegram e WhatsApp são os canais mais
                                    rápidos. Normalmente respondemos em minutos.
                                </p>
                            </div>
                            <div
                                class="flex shrink-0 flex-col gap-3 sm:flex-row md:flex-col md:items-end"
                            >
                                <a
                                    href="https://t.me/flaviomoreir4"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-center gap-2 rounded-full bg-primary-foreground px-7 py-3.5 text-sm font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
                                >
                                    <MessageSquare class="h-4 w-4" />
                                    Telegram
                                </a>
                                <a
                                    href="https://wa.me/5511982776725"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-primary-foreground transition-all hover:bg-white/20"
                                >
                                    <Phone class="h-4 w-4" />
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
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
