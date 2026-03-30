
<!DOCTYPE html>
<html lang="pt-BR" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-background font-sans selection:bg-primary/10">

    {{-- ─── Skip link (acessibilidade) ─────────────────────────────────── --}}
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[200] focus:rounded-lg focus:bg-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-primary-foreground focus:shadow-lg focus:outline-none"
    >
        Ir para o conteúdo
    </a>

    {{-- ─── Nav ─────────────────────────────────────────────────────────── --}}
    <nav
        id="navbar"
        role="navigation"
        aria-label="Navegação principal"
        class="fixed top-0 z-50 w-full bg-transparent px-6 py-4 transition-all duration-300"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between">

            {{-- Logo --}}
            <a
                href="/"
                class="group flex items-center gap-2 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
            >
                <div class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg transition-transform group-hover:scale-105">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt=""
                        aria-hidden="true"
                        class="h-full w-full object-contain"
                        onerror="this.style.display='none';this.nextElementSibling.style.opacity='1'"
                    />
                    <div class="absolute inset-0 flex items-center justify-center bg-primary text-lg font-bold text-primary-foreground opacity-0 transition-opacity">
                        MC
                    </div>
                </div>
                <span class="text-xl font-bold tracking-tight">Marketing &amp; Code</span>
            </a>

            {{-- Desktop links --}}
            @php
                $navLinks = [
                    ['label' => 'Serviços',  'href' => '/servicos'],
                    ['label' => 'Projetos',  'href' => '/projetos'],
                    ['label' => 'Sobre',     'href' => '/sobre'],
                    ['label' => 'Blog',      'href' => '/blog'],
                ];
            @endphp

            <div class="hidden items-center gap-2 md:flex">
                @foreach ($navLinks as $link)
                    <a
                        href="{{ $link['href'] }}"
                        class="px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <a
                    href="/contato"
                    class="rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition-all hover:opacity-90 active:scale-[0.98]"
                >
                    Bora conversar
                </a>
            </div>

            {{-- Mobile: hambúrguer --}}
            <button
                id="mobile-toggle"
                class="flex h-9 w-9 flex-col items-center justify-center gap-1.5 rounded-lg hover:bg-muted md:hidden"
                aria-label="Abrir menu"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <span id="bar1" class="block h-px w-5 bg-current transition-all duration-300"></span>
                <span id="bar2" class="block h-px w-5 bg-current transition-all duration-200"></span>
                <span id="bar3" class="block h-px w-5 bg-current transition-all duration-300"></span>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div
            id="mobile-menu"
            class="hidden border-t border-border bg-background/95 px-4 py-4 backdrop-blur-md md:hidden"
        >
            <ul class="space-y-1">
                @foreach ($navLinks as $link)
                    <li>
                        <a
                            href="{{ $link['href'] }}"
                            class="flex items-center rounded-xl px-4 py-3.5 text-base font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        >
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
                <li class="pt-2">
                    <a
                        href="/contato"
                        class="flex w-full items-center justify-center rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground transition-all hover:opacity-90 active:scale-[0.98]"
                    >
                        Bora conversar
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- ─── Main ────────────────────────────────────────────────────────── --}}
    <main id="main-content" class="pt-20">
        @yield('content')
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────────────── --}}
    <footer class="border-t border-border bg-muted/30 px-6 py-14" role="contentinfo">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-2 gap-10 sm:grid-cols-2 md:grid-cols-4 lg:gap-16">

                {{-- Brand --}}
                <div class="col-span-2 md:col-span-2">
                    <a href="/" class="mb-4 inline-flex items-center gap-2 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <div class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg">
                            <img
                                src="{{ asset('images/logo.png') }}"
                                alt=""
                                aria-hidden="true"
                                class="h-full w-full object-contain"
                                onerror="this.style.display='none';this.nextElementSibling.style.opacity='1'"
                            />
                            <div class="absolute inset-0 flex items-center justify-center bg-primary text-lg font-bold text-primary-foreground opacity-0">
                                MC
                            </div>
                        </div>
                        <span class="text-lg font-bold tracking-tight">Marketing &amp; Code</span>
                    </a>
                    <p class="max-w-xs text-sm leading-relaxed text-muted-foreground">
                        Desenvolvimento de sistemas, plataformas e presença
                        digital com engenharia de alta qualidade e foco em
                        resultados.
                    </p>
                    <div class="mt-6 flex gap-3">
                        <a href="https://t.me/flaviomoreir4" target="_blank" rel="noopener noreferrer" aria-label="Telegram"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.833.941z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/5511982776725" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                        <a href="mailto:flavio.moreira@mktcode.digital" aria-label="E-mail"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.917V6.75"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Navegação --}}
                <div>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                        Navegação
                    </h2>
                    <ul class="space-y-2.5 text-sm" role="list">
                        @foreach ($navLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}"
                                    class="text-muted-foreground transition-colors hover:text-foreground focus-visible:text-foreground focus-visible:outline-none">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contato --}}
                <div>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                        Contato
                    </h2>
                    <ul class="space-y-2.5 text-sm" role="list">
                        <li><a href="https://t.me/flaviomoreir4" target="_blank" rel="noopener noreferrer" class="text-muted-foreground transition-colors hover:text-foreground">Telegram</a></li>
                        <li><a href="https://wa.me/5511982776725" target="_blank" rel="noopener noreferrer" class="text-muted-foreground transition-colors hover:text-foreground">WhatsApp</a></li>
                        <li><a href="mailto:flavio.moreira@mktcode.digital" class="text-muted-foreground transition-colors hover:text-foreground">E-mail</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-border pt-8 text-xs text-muted-foreground sm:flex-row">
                <p>© {{ date('Y') }} MC Marketing &amp; Code · CNPJ 43.296.394/0001-80</p>
                <p>Desenvolvido com Laravel, Filament, Vue 3 e muito café ☕</p>
            </div>
        </div>
    </footer>

    {{-- ─── Back to top ──────────────────────────────────────────────────── --}}
    <button
        id="back-to-top"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        aria-label="Voltar ao topo"
        class="group fixed bottom-4 right-4 hidden h-10 w-10 items-center justify-center rounded-full border border-border bg-background shadow-sm transition-all duration-200 hover:bg-muted"
    >
        <svg class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-foreground"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    {{-- ─── JS mínimo: scroll nav + mobile menu + back to top ─────────── --}}
    <script>
        (function () {
            var navbar = document.getElementById('navbar');
            var btt    = document.getElementById('back-to-top');

            // Nav scroll effect
            function onScroll() {
                if (window.scrollY > 20) {
                    navbar.classList.add('border-b', 'border-border', 'bg-background/80', 'py-3', 'shadow-sm', 'backdrop-blur-md');
                    navbar.classList.remove('bg-transparent', 'py-4');
                    btt.classList.remove('hidden');
                    btt.classList.add('flex');
                } else {
                    navbar.classList.remove('border-b', 'border-border', 'bg-background/80', 'py-3', 'shadow-sm', 'backdrop-blur-md');
                    navbar.classList.add('bg-transparent', 'py-4');
                    btt.classList.add('hidden');
                    btt.classList.remove('flex');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });

            // Mobile menu toggle
            var toggle = document.getElementById('mobile-toggle');
            var menu   = document.getElementById('mobile-menu');
            var bar1   = document.getElementById('bar1');
            var bar2   = document.getElementById('bar2');
            var bar3   = document.getElementById('bar3');
            var isOpen = false;

            toggle.addEventListener('click', function () {
                isOpen = !isOpen;
                toggle.setAttribute('aria-expanded', String(isOpen));
                menu.classList.toggle('hidden', !isOpen);
                bar1.style.transform = isOpen ? 'translateY(6px) rotate(45deg)'  : '';
                bar2.style.opacity   = isOpen ? '0' : '';
                bar3.style.transform = isOpen ? 'translateY(-6px) rotate(-45deg)' : '';
            });
        }());
    </script>

</body>
</html>
