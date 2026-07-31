import { ref, watch, onMounted } from 'vue';

export type ConsentValue = 'accepted' | 'rejected' | 'custom';

export interface CookieConsentState {
    value: ConsentValue;
    analytics: boolean;
    marketing: boolean;
    decidedAt: string | null;
}

const STORAGE_KEY = 'mktcode_cookie_consent';

const defaults: CookieConsentState = {
    value: 'accepted',
    analytics: true,
    marketing: true,
    decidedAt: null,
};

function readFromStorage(): CookieConsentState | null {
    if (typeof window === 'undefined') {
        return null;
    }

    const raw = window.localStorage.getItem(STORAGE_KEY);

    if (!raw) {
        return null;
    }

    try {
        return { ...defaults, ...(JSON.parse(raw) as Partial<CookieConsentState>) };
    } catch {
        return null;
    }
}

// Module-level singleton so all components share the same reactive state.
// Starts neutral: SSR renders nothing, the client hydrates on mount.
const state = ref<CookieConsentState | null>(null);
const isOpen = ref(false);
let initialized = false;

watch(
    state,
    (value) => {
        if (typeof window === 'undefined') {
            return;
        }

        if (value === null) {
            window.localStorage.removeItem(STORAGE_KEY);
        } else {
            window.localStorage.setItem(STORAGE_KEY, JSON.stringify(value));
        }

        isOpen.value = value === null;
    },
    { deep: true },
);

export function useCookieConsent() {
    // Read persisted choice only on the client, after mount, to avoid
    // SSR/client hydration mismatches (server has no localStorage).
    const init = () => {
        if (initialized || typeof window === 'undefined') {
            return;
        }

        initialized = true;
        const stored = readFromStorage();

        if (stored) {
            state.value = stored;
        }

        isOpen.value = state.value === null;
    };

    onMounted(init);

    const hasDecided = () => state.value !== null;

    const acceptAll = () => {
        state.value = {
            value: 'accepted',
            analytics: true,
            marketing: true,
            decidedAt: new Date().toISOString(),
        };
    };

    const rejectAll = () => {
        state.value = {
            value: 'rejected',
            analytics: false,
            marketing: false,
            decidedAt: new Date().toISOString(),
        };
    };

    const saveCustom = (analytics: boolean, marketing: boolean) => {
        state.value = {
            value: 'custom',
            analytics,
            marketing,
            decidedAt: new Date().toISOString(),
        };
    };

    const reset = () => {
        state.value = null;
        isOpen.value = true;
    };

    return {
        state,
        isOpen,
        hasDecided,
        acceptAll,
        rejectAll,
        saveCustom,
        reset,
        init,
    };
}
