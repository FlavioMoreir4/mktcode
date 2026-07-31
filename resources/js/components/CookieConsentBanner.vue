<script setup lang="ts">
import { ref } from 'vue';
import { useCookieConsent } from '@/composables/useCookieConsent';
import { Button } from '@/components/ui/button';

const { state, isOpen, acceptAll, rejectAll, saveCustom } = useCookieConsent();

const showPreferences = ref(false);
const analytics = ref(true);
const marketing = ref(true);

const openPreferences = () => {
    analytics.value = state.value?.analytics ?? true;
    marketing.value = state.value?.marketing ?? true;
    showPreferences.value = true;
};

const savePreferences = () => {
    saveCustom(analytics.value, marketing.value);
    showPreferences.value = false;
};
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-if="isOpen"
            class="fixed inset-x-0 bottom-0 z-[100] px-4 pb-4 sm:flex sm:justify-center"
            role="dialog"
            aria-live="polite"
            aria-label="Consentimento de cookies"
        >
            <div
                class="w-full max-w-3xl rounded-2xl border border-border bg-background/95 p-5 shadow-2xl backdrop-blur-md sm:p-6"
            >
                <template v-if="!showPreferences">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                        <div class="flex-1">
                            <h2 class="text-sm font-semibold text-foreground">
                                🍪 Usamos cookies
                            </h2>
                            <p class="mt-1 text-xs leading-relaxed text-muted-foreground">
                                Utilizamos cookies essenciais e, com seu
                                consentimento, cookies de analytics e marketing
                                (Google, Meta e tecnologias de tracking) para
                                melhorar sua experiência. Você pode gerenciar
                                suas preferências a qualquer momento. Veja nossa
                                <a
                                    href="/page/politica-de-privacidade"
                                    class="font-medium text-primary underline-offset-2 hover:underline"
                                    >Política de Privacidade</a
                                >.
                            </p>
                        </div>
                        <div
                            class="flex flex-col gap-2 sm:flex-row sm:items-center"
                        >
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="openPreferences"
                            >
                                Preferências
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="rejectAll"
                            >
                                Rejeitar
                            </Button>
                            <Button size="sm" @click="acceptAll">
                                Aceitar todos
                            </Button>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="space-y-4">
                        <h2 class="text-sm font-semibold text-foreground">
                            Preferências de cookies
                        </h2>

                        <label
                            class="flex items-start gap-3 rounded-xl border border-border p-3"
                        >
                            <input
                                type="checkbox"
                                checked
                                disabled
                                class="mt-0.5 h-4 w-4 accent-primary"
                            />
                            <span>
                                <span class="block text-sm font-medium"
                                    >Essenciais</span
                                >
                                <span
                                    class="block text-xs text-muted-foreground"
                                    >Necessários para o funcionamento do site.
                                    Sempre ativos.</span
                                >
                            </span>
                        </label>

                        <label
                            class="flex items-start gap-3 rounded-xl border border-border p-3"
                        >
                            <input
                                v-model="analytics"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 accent-primary"
                            />
                            <span>
                                <span class="block text-sm font-medium"
                                    >Analytics</span
                                >
                                <span
                                    class="block text-xs text-muted-foreground"
                                    >Google Analytics e métricas de uso
                                    (tracking).</span
                                >
                            </span>
                        </label>

                        <label
                            class="flex items-start gap-3 rounded-xl border border-border p-3"
                        >
                            <input
                                v-model="marketing"
                                type="checkbox"
                                class="mt-0.5 h-4 w-4 accent-primary"
                            />
                            <span>
                                <span class="block text-sm font-medium"
                                    >Marketing</span
                                >
                                <span
                                    class="block text-xs text-muted-foreground"
                                    >Google Ads, Meta Pixel e conversão.</span
                                >
                            </span>
                        </label>

                        <div
                            class="flex flex-col gap-2 sm:flex-row sm:justify-end"
                        >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="rejectAll"
                            >
                                Rejeitar todos
                            </Button>
                            <Button size="sm" @click="savePreferences">
                                Salvar preferências
                            </Button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </Transition>
</template>
