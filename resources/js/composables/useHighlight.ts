import hljs from 'highlight.js/lib/core';

// Importa apenas linguagens necessárias
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import php from 'highlight.js/lib/languages/php';
import tsx from 'highlight.js/lib/languages/typescript';
import typescript from 'highlight.js/lib/languages/typescript';
import xml from 'highlight.js/lib/languages/xml';
import { onMounted, onUnmounted } from 'vue';

// Registra linguagens
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('typescript', typescript);
hljs.registerLanguage('php', php);
hljs.registerLanguage('html', xml);
hljs.registerLanguage('css', css);
hljs.registerLanguage('bash', bash);
hljs.registerLanguage('jsx', tsx);

export function useHighlight() {
    onMounted(() => {
        hljs.highlightAll();
    });

    onUnmounted(() => {
        // normalmente não precisa limpar nada
    });

    return { hljs };
}
