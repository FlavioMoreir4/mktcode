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
    <div class="group relative bg-card border border-border rounded-3xl p-8 hover:border-primary/20 transition-all duration-500 overflow-hidden">
        <!-- Flare Effect -->
        <div class="absolute -right-16 -top-16 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-500" />
        
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 block">{{ project.category }}</span>
                    <h3 class="text-2xl font-bold tracking-tight">{{ project.title }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-muted flex items-center justify-center text-muted-foreground group-hover:bg-primary group-hover:text-primary-foreground transition-all duration-300">
                    <LucideExternalLink class="w-4 h-4" />
                </div>
            </div>

            <p class="text-muted-foreground text-sm leading-relaxed mb-8">
                {{ project.description }}
            </p>

            <div class="space-y-4">
                <div v-for="highlight in project.highlights" :key="highlight" class="flex gap-3 text-sm">
                    <span class="text-primary font-bold">→</span>
                    <span>{{ highlight }}</span>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-border flex flex-wrap gap-2">
                <span 
                    v-for="tag in project.tags" 
                    :key="tag"
                    class="px-3 py-1 bg-muted rounded-full text-[10px] font-bold uppercase tracking-wider text-muted-foreground"
                >
                    {{ tag }}
                </span>
            </div>
        </div>
    </div>
</template>
