<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, RefreshCw, Home } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    status: number;
}>();

interface ErrorContent {
    title: string;
    description: string;
    /** Ação primária */
    primary: { label: string; href: string; reload?: boolean };
    /** Ação secundária — opcional */
    secondary?: { label: string; href: string; reload?: boolean };
}

const content = computed((): ErrorContent => {
    const map: Record<number, ErrorContent> = {
        403: {
            title: 'Acesso negado',
            description:
                'Você não tem permissão para visualizar este conteúdo. Se acredita que isso é um engano, entre em contato.',
            primary: { label: 'Voltar ao início', href: '/' },
            secondary: { label: 'Falar com a gente', href: '/contato' },
        },
        404: {
            title: 'Página não encontrada',
            description:
                'A URL que você tentou acessar não existe ou foi removida. Verifique o endereço ou volte ao início.',
            primary: { label: 'Voltar ao início', href: '/' },
            secondary: { label: 'Ver projetos', href: '/projetos' },
        },
        419: {
            title: 'Sessão expirada',
            description:
                'Sua sessão expirou por inatividade. Recarregue a página para continuar.',
            primary: { label: 'Recarregar página', href: '#', reload: true },
        },
        500: {
            title: 'Erro no servidor',
            description:
                'Algo deu errado do nosso lado. A equipe já foi notificada e estamos trabalhando na correção.',
            primary: { label: 'Tentar novamente', href: '#', reload: true },
            secondary: { label: 'Voltar ao início', href: '/' },
        },
        503: {
            title: 'Em manutenção',
            description:
                'O sistema está temporariamente fora do ar para manutenção. Tudo voltará em breve.',
            primary: { label: 'Verificar novamente', href: '#', reload: true },
        },
    };

    return (
        map[props.status] ?? {
            title: 'Algo deu errado',
            description:
                'Ocorreu um erro inesperado. Tente novamente ou volte ao início.',
            primary: { label: 'Voltar ao início', href: '/' },
        }
    );
});

function handleAction(action: { href: string; reload?: boolean }) {
    if (action.reload) {
        window.location.reload();

        return;
    }
}
</script>

<template>

    <Head>
        <title>{{ status }} — {{ content.title }}</title>
    </Head>

    <section class="flex min-h-[80vh] flex-col items-center justify-center px-6 py-24 text-center">
        <!-- Código decorativo ao fundo -->
        <div class="relative mb-8 select-none" aria-hidden="true">
            <span class="text-[9rem] leading-none font-black tracking-tighter text-primary/[0.06] md:text-[12rem]">
                {{ status }}
            </span>
            <!-- Linha decorativa sobre o número -->
            <div class="absolute top-1/2 left-1/2 h-px w-24 -translate-x-1/2 -translate-y-1/2 bg-primary/20" />
        </div>

        <!-- Conteúdo -->
        <div class="max-w-md">
            <h1 class="mb-3 text-3xl font-bold tracking-tight md:text-4xl">
                {{ content.title }}
            </h1>
            <p class="mb-10 text-base leading-relaxed text-muted-foreground">
                {{ content.description }}
            </p>

            <!-- CTAs -->
            <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <!-- Primário -->
                <template v-if="content.primary.reload">
                    <button type="button"
                        class="flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-bold text-primary-foreground shadow-md transition-all hover:opacity-90 active:scale-[0.98]"
                        @click="handleAction(content.primary)">
                        <RefreshCw class="h-4 w-4" />
                        {{ content.primary.label }}
                    </button>
                </template>
                <template v-else>
                    <Link :href="content.primary.href"
                        class="flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-bold text-primary-foreground shadow-md transition-all hover:opacity-90 active:scale-[0.98]">
                        <Home class="h-4 w-4" />
                        {{ content.primary.label }}
                    </Link>
                </template>

                <!-- Secundário -->
                <template v-if="content.secondary">
                    <template v-if="content.secondary.reload">
                        <button type="button"
                            class="flex items-center gap-2 rounded-full border border-border px-7 py-3 text-sm font-semibold text-foreground transition-all hover:bg-muted active:scale-[0.98]"
                            @click="handleAction(content.secondary)">
                            {{ content.secondary.label }}
                        </button>
                    </template>
                    <template v-else>
                        <Link :href="content.secondary.href"
                            class="flex items-center gap-2 rounded-full border border-border px-7 py-3 text-sm font-semibold text-foreground transition-all hover:bg-muted active:scale-[0.98]">
                            <ArrowLeft class="h-4 w-4" />
                            {{ content.secondary.label }}
                        </Link>
                    </template>
                </template>
            </div>
        </div>
    </section>
</template>
