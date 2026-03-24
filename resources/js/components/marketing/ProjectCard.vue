<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ExternalLink, ArrowRight } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import projects from '@/routes/public/projects';
import type { PublicProjectViewData } from '@/types/public';

interface Props {
    project: PublicProjectViewData;
    variant?: 'default' | 'featured';
    withCover?: boolean;
    isPriority?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
    withCover: true,
    isPriority: false,
});
</script>

<template>
    <!-- ── FEATURED variant ──────────────────────────────────────────────── -->
    <Link
        v-if="props.variant === 'featured'"
        :href="projects.show(project.slug).url"
        class="group block"
    >
        <Card
            class="overflow-hidden rounded-[2rem] py-0 transition-all duration-500 hover:border-primary/30 hover:shadow-2xl hover:shadow-primary/8 md:flex md:flex-row"
        >
            <!-- Image -->
            <div
                v-if="withCover"
                class="aspect-video overflow-hidden bg-muted md:aspect-auto md:w-1/2"
            >
                <img
                    v-if="project.media?.cover"
                    :src="project.media.cover.url"
                    :alt="project.title"
                    :fetchpriority="isPriority ? 'high' : undefined"
                    :loading="isPriority ? 'eager' : 'lazy'"
                    :decoding="isPriority ? 'sync' : 'async'"
                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                />
                <div
                    v-else
                    class="flex h-full min-h-[240px] items-center justify-center bg-gradient-to-br from-primary/5 to-primary/10"
                >
                    <span class="text-7xl font-bold text-primary/15">
                        {{ project.title.charAt(0) }}
                    </span>
                </div>
            </div>

            <!-- Info -->
            <div class="flex flex-col justify-center md:w-1/2">
                <CardHeader class="gap-5 p-8 md:p-12">
                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge
                            variant="outline"
                            class="text-[10px] tracking-widest text-primary/70 uppercase"
                        >
                            Projeto
                        </Badge>
                        <template v-if="project.client || project.year">
                            <span class="text-xs text-border">·</span>
                            <span
                                v-if="project.client"
                                class="text-xs font-medium text-foreground/80"
                            >
                                {{ project.client }}
                            </span>
                            <span
                                v-if="project.client && project.year"
                                class="text-xs text-border"
                                >·</span
                            >
                            <span
                                v-if="project.year"
                                class="text-xs text-muted-foreground"
                            >
                                {{ project.year }}
                            </span>
                        </template>
                    </div>

                    <div>
                        <CardTitle class="text-3xl tracking-tight md:text-4xl">
                            {{ project.title }}
                        </CardTitle>
                        <CardDescription
                            v-if="project.description"
                            class="mt-3 leading-relaxed"
                        >
                            {{ project.description }}
                        </CardDescription>
                    </div>
                </CardHeader>

                <CardContent class="px-8 pb-0 md:px-12">
                    <!-- Stack -->
                    <div
                        v-if="project.stack?.length"
                        class="mt-5 flex flex-wrap gap-2"
                    >
                        <Badge
                            v-for="tech in project.stack"
                            :key="tech"
                            variant="secondary"
                            class="text-[10px] tracking-wider uppercase"
                        >
                            {{ tech }}
                        </Badge>
                    </div>
                </CardContent>

                <CardFooter class="gap-4 p-8 pt-6 md:p-12 md:pt-6">
                    <Button
                        variant="ghost"
                        class="gap-1.5 px-0 font-bold text-primary group-hover:gap-2.5 hover:bg-transparent hover:text-primary/80"
                    >
                        Ver projeto
                        <ArrowRight
                            class="h-4 w-4 transition-transform group-hover:translate-x-1"
                        />
                    </Button>
                    <Button
                        v-if="project.url"
                        as="a"
                        :href="project.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        variant="ghost"
                        size="sm"
                        class="gap-1.5 text-muted-foreground"
                        @click.stop
                    >
                        <ExternalLink class="h-3.5 w-3.5" />
                        Ao vivo
                    </Button>
                </CardFooter>
            </div>
        </Card>
    </Link>

    <!-- ── DEFAULT variant ───────────────────────────────────────────────── -->
    <Link
        v-else
        :href="projects.show(project.slug).url"
        class="group block h-full"
    >
        <Card
            class="relative h-full overflow-hidden rounded-3xl py-0 transition-all duration-300 hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
        >
            <!-- Flare -->
            <div
                class="pointer-events-none absolute -top-16 -right-16 h-40 w-40 rounded-full bg-primary/5 blur-3xl transition-all duration-500 group-hover:bg-primary/10"
                aria-hidden="true"
            />

            <!-- Thumbnail -->
            <div
                v-if="project.media?.cover && withCover"
                class="aspect-[16/10] overflow-hidden bg-muted"
            >
                <img
                    :src="project.media.cover.url"
                    :alt="project.title"
                    :fetchpriority="isPriority ? 'high' : undefined"
                    :loading="isPriority ? 'eager' : 'lazy'"
                    :decoding="isPriority ? 'sync' : 'async'"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]"
                />
            </div>

            <CardHeader class="relative z-10 gap-4 p-7 pb-0">
                <!-- Category + action icon -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <Badge
                            variant="outline"
                            class="text-[10px] tracking-widest text-primary/70 uppercase"
                        >
                            Projeto
                        </Badge>
                        <template v-if="project.client || project.year">
                            <span class="text-xs text-border">·</span>
                            <span
                                v-if="project.client"
                                class="text-xs text-muted-foreground"
                            >
                                {{ project.client }}
                            </span>
                            <span
                                v-if="project.client && project.year"
                                class="text-xs text-border"
                                >·</span
                            >
                            <span
                                v-if="project.year"
                                class="text-xs text-muted-foreground"
                            >
                                {{ project.year }}
                            </span>
                        </template>
                    </div>

                    <Button
                        v-if="project.url"
                        as="a"
                        :href="project.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        size="icon"
                        variant="secondary"
                        class="h-8 w-8 shrink-0 rounded-full hover:bg-primary hover:text-primary-foreground"
                        title="Abrir site ao vivo"
                        @click.stop
                    >
                        <ExternalLink class="h-3.5 w-3.5" />
                    </Button>
                    <div
                        v-else
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-secondary text-muted-foreground transition-all duration-300 group-hover:bg-primary group-hover:text-primary-foreground"
                    >
                        <ArrowRight
                            class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                        />
                    </div>
                </div>

                <div>
                    <CardTitle class="text-xl leading-snug tracking-tight">
                        {{ project.title }}
                    </CardTitle>
                    <CardDescription
                        v-if="project.description"
                        class="mt-2 line-clamp-2 leading-relaxed"
                    >
                        {{ project.description }}
                    </CardDescription>
                </div>
            </CardHeader>

            <!-- Stack tags -->
            <CardFooter
                v-if="project.stack?.length"
                class="relative z-10 flex-wrap gap-1.5 border-t border-border/60 p-7 pt-4"
            >
                <Badge
                    v-for="tech in project.stack.slice(0, 5)"
                    :key="tech"
                    variant="secondary"
                    class="text-[10px] tracking-wider uppercase"
                >
                    {{ tech }}
                </Badge>
                <Badge
                    v-if="project.stack.length > 5"
                    variant="secondary"
                    class="text-[10px] tracking-wider uppercase"
                >
                    +{{ project.stack.length - 5 }}
                </Badge>
            </CardFooter>
        </Card>
    </Link>
</template>
