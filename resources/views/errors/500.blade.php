@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

@section('content')
    <section class="flex min-h-[80vh] flex-col items-center justify-center px-6 py-24 text-center">
        <p class="mb-8 select-none text-[9rem] font-black leading-none tracking-tighter text-primary/[0.06] md:text-[12rem]" aria-hidden="true">500</p>
        <div class="max-w-md">
            <h1 class="mb-3 text-3xl font-bold tracking-tight md:text-4xl">Erro interno do servidor</h1>
            <p class="mb-10 text-base leading-relaxed text-muted-foreground">
                Ops! Algo deu errado no servidor. Nossa equipe já foi notificada e está trabalhando para resolver o problema.
            </p>
            <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <a href="/" class="flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-bold text-primary-foreground shadow-md transition-all hover:opacity-90 active:scale-[0.98]">
                    Voltar ao início
                </a>
                <a href="/contato" class="flex items-center gap-2 rounded-full border border-border px-7 py-3 text-sm font-semibold text-foreground transition-all hover:bg-muted active:scale-[0.98]">
                    Falar com a gente
                </a>
            </div>
        </div>
    </section>
@endsection
