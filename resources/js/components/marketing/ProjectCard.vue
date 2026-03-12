<script setup lang="ts">
import { LucideExternalLink } from 'lucide-vue-next';

interface Project {
    title: string;
    category: string;
    description: string;
    tags: string[];
    highlights: string[];
}

defineProps<{
    project: Project;
}>();
</script>

<template>
    <div
        class="group relative overflow-hidden rounded-3xl border border-border bg-card p-8 transition-all duration-500 hover:border-primary/20"
    >
        <!-- Flare Effect -->
        <div
            class="absolute -top-16 -right-16 h-32 w-32 rounded-full bg-primary/5 blur-3xl transition-all duration-500 group-hover:bg-primary/10"
        />

        <div class="relative z-10">
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <span
                        class="mb-2 block text-xs font-bold tracking-widest text-primary/60 uppercase"
                        >{{ project.category }}</span
                    >
                    <h3 class="text-2xl font-bold tracking-tight">
                        {{ project.title }}
                    </h3>
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-muted text-muted-foreground transition-all duration-300 group-hover:bg-primary group-hover:text-primary-foreground"
                >
                    <LucideExternalLink class="h-4 w-4" />
                </div>
            </div>

            <p class="mb-8 text-sm leading-relaxed text-muted-foreground">
                {{ project.description }}
            </p>

            <div class="space-y-4">
                <div
                    v-for="highlight in project.highlights"
                    :key="highlight"
                    class="flex gap-3 text-sm"
                >
                    <span class="font-bold text-primary">→</span>
                    <span>{{ highlight }}</span>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-2 border-t border-border pt-8">
                <span
                    v-for="tag in project.tags"
                    :key="tag"
                    class="rounded-full bg-muted px-3 py-1 text-[10px] font-bold tracking-wider text-muted-foreground uppercase"
                >
                    {{ tag }}
                </span>
            </div>
        </div>
    </div>
</template>
