<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    Send,
    MessageSquare,
    Mail,
    Phone,
    CheckCircle2,
    Loader2,
} from 'lucide-vue-next';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { store as inquiryStore } from '@/routes/public/inquiry';

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
            // TODO: Adicionar notificação de sucesso se necessário (Toast)
        },
    });
};

const contacts = [
    {
        name: 'Telegram',
        value: '@flaviomoreir4',
        icon: MessageSquare,
        label: 'Resposta mais rápida',
        href: 'https://t.me/flaviomoreir4',
        color: 'bg-blue-500/10 text-blue-500',
    },
    {
        name: 'WhatsApp',
        value: '+55 11 98277-6725',
        icon: Phone,
        label: 'Se preferir',
        href: 'https://wa.me/5511982776725',
        color: 'bg-green-500/10 text-green-500',
    },
    {
        name: 'E-mail',
        value: 'flavio.moreira@mktcode.digital',
        icon: Mail,
        label: 'Para projetos formais',
        href: 'mailto:flavio.moreira@mktcode.digital',
        color: 'bg-primary/10 text-primary',
    },
];
</script>

<template>
    <Head title="Contato" />

    <PublicLayout>
        <div class="px-6 pt-32 pb-24">
            <div class="mx-auto max-w-7xl">
                <div
                    class="grid grid-cols-1 items-start gap-16 lg:grid-cols-12"
                >
                    <!-- Text Content & Direct Contacts -->
                    <div class="space-y-12 lg:col-span-5">
                        <div class="space-y-6">
                            <h1
                                class="text-4xl font-bold tracking-tight md:text-5xl"
                            >
                                Bora conversar.
                            </h1>
                            <p
                                class="text-lg leading-relaxed text-muted-foreground"
                            >
                                Tem um projeto, uma dúvida ou só quer entender
                                se faz sentido trabalhar juntos? Manda uma
                                mensagem. Sem formulário complicado, sem
                                enrolação.
                            </p>
                            <p class="font-bold text-foreground">
                                Respondemos no mesmo dia.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <a
                                v-for="contact in contacts"
                                :key="contact.name"
                                :href="contact.href"
                                class="group flex items-center gap-4 rounded-2xl border border-border bg-card p-4 transition-all hover:border-primary/50"
                            >
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl transition-colors"
                                    :class="contact.color"
                                >
                                    <component
                                        :is="contact.icon"
                                        class="h-6 w-6"
                                    />
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <h4 class="font-bold">
                                            {{ contact.name }}
                                        </h4>
                                        <span
                                            class="text-[10px] font-bold tracking-wider text-muted-foreground uppercase opacity-0 transition-opacity group-hover:opacity-100"
                                        >
                                            {{ contact.label }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-muted-foreground">
                                        {{ contact.value }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <p class="text-sm text-muted-foreground italic">
                            Cada grande sistema começou com uma conversa
                            simples.
                        </p>
                    </div>

                    <!-- Contact Form -->
                    <div class="lg:col-span-7">
                        <div
                            class="relative overflow-hidden rounded-3xl border border-border bg-card p-8 shadow-2xl shadow-primary/5 md:p-10"
                        >
                            <div
                                v-if="form.wasSuccessful"
                                class="absolute inset-0 z-20 flex flex-col items-center justify-center space-y-4 bg-background/95 p-8 text-center backdrop-blur-sm"
                            >
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-green-500/10 text-green-500"
                                >
                                    <CheckCircle2 class="h-8 w-8" />
                                </div>
                                <h3 class="text-2xl font-bold">
                                    Mensagem enviada!
                                </h3>
                                <p
                                    class="mx-auto max-w-xs text-muted-foreground"
                                >
                                    Obrigado pelo contato. Analisaremos sua
                                    mensagem e responderemos o mais rápido
                                    possível.
                                </p>
                                <button
                                    @click="form.wasSuccessful = false"
                                    class="text-sm font-bold text-primary hover:underline"
                                >
                                    Enviar outra mensagem
                                </button>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <div
                                    class="grid grid-cols-1 gap-6 md:grid-cols-2"
                                >
                                    <div class="space-y-2">
                                        <label
                                            for="name"
                                            class="text-sm font-bold"
                                            >Nome completo</label
                                        >
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="Como podemos te chamar?"
                                            class="w-full rounded-xl border border-border bg-muted/30 px-4 py-3 transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                            required
                                        />
                                        <p
                                            v-if="form.errors.name"
                                            class="text-xs text-destructive"
                                        >
                                            {{ form.errors.name }}
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            for="email"
                                            class="text-sm font-bold"
                                            >E-mail corporativo</label
                                        >
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            placeholder="seu@email.com"
                                            class="w-full rounded-xl border border-border bg-muted/30 px-4 py-3 transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                            required
                                        />
                                        <p
                                            v-if="form.errors.email"
                                            class="text-xs text-destructive"
                                        >
                                            {{ form.errors.email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        for="whatsapp"
                                        class="text-sm font-bold"
                                        >WhatsApp / Telefone (opcional)</label
                                    >
                                    <input
                                        id="whatsapp"
                                        v-model="form.whatsapp"
                                        type="text"
                                        placeholder="+55 (XX) XXXXX-XXXX"
                                        class="w-full rounded-xl border border-border bg-muted/30 px-4 py-3 transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                    />
                                    <p
                                        v-if="form.errors.whatsapp"
                                        class="text-xs text-destructive"
                                    >
                                        {{ form.errors.whatsapp }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        for="message"
                                        class="text-sm font-bold"
                                        >Como podemos ajudar?</label
                                    >
                                    <textarea
                                        id="message"
                                        v-model="form.message"
                                        rows="5"
                                        placeholder="Conte um pouco sobre o seu desafio..."
                                        class="w-full resize-none rounded-xl border border-border bg-muted/30 px-4 py-3 transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                        required
                                    ></textarea>
                                    <p
                                        v-if="form.errors.message"
                                        class="text-xs text-destructive"
                                    >
                                        {{ form.errors.message }}
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex w-full items-center justify-center gap-2 rounded-full bg-primary py-4 font-bold text-primary-foreground transition-all hover:scale-[1.01] hover:opacity-90 active:scale-[0.99] disabled:opacity-50"
                                >
                                    <span v-if="!form.processing"
                                        >Enviar Mensagem</span
                                    >
                                    <Loader2
                                        v-else
                                        class="h-5 w-5 animate-spin"
                                    />
                                    <Send
                                        v-if="!form.processing"
                                        class="h-4 w-4"
                                    />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
