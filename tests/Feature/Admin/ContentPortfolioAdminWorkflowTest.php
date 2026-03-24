<?php

declare(strict_types=1);

use App\Application\Content\Commands\SchedulePost;
use App\Application\Content\Commands\UnpublishPost;
use App\Application\Content\DTOs\PostPublicationData;
use App\Application\Content\Services\PostPublicationWorkflow;
use App\Application\Portfolio\Commands\AssignProjectOwner;
use App\Application\Portfolio\Commands\FeatureProject;
use App\Application\Portfolio\Commands\PublishProject;
use App\Application\Portfolio\Commands\ReorderProjects;
use App\Application\Portfolio\Services\ProjectAdministrativeWorkflow;
use App\Domain\Content\Enums\PostStatus;
use App\Domain\Portfolio\Enums\ProjectStatus;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

function adminRichDocument(string $text): array
{
    return [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $text,
                    ],
                ],
            ],
        ],
    ];
}

function createAdminUserWithPermissions(string ...$permissions): User
{
    Role::findOrCreate('author');

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission);
    }

    $user = User::factory()->create(['username' => 'admin-user']);
    $user->assignRole('author');
    $user->givePermissionTo($permissions);

    return $user;
}

it('creates scheduled posts through the Filament create page workflow', function () {
    $user = createAdminUserWithPermissions('create_post');
    $category = Category::query()->create(['name' => 'Admin']);

    $page = new class extends CreatePost
    {
        /**
         * @param  array<string, mixed>  $data
         */
        public function createRecord(array $data): Post
        {
            /** @var Post $post */
            $post = $this->handleRecordCreation($data);

            return $post;
        }
    };

    $page->boot(app(PostPublicationWorkflow::class));

    $post = $page->createRecord([
        'title' => 'Scheduled from admin',
        'slug' => 'scheduled-from-admin',
        'body' => adminRichDocument('Scheduled body'),
        'status' => PostStatus::Published->value,
        'published_at' => now()->addDay()->toDateTimeString(),
        'author_id' => $user->id,
        'category_id' => $category->id,
    ]);

    expect($post->fresh()->status)->toBe(PostStatus::Published);
    expect($post->fresh()->published_at?->isFuture())->toBeTrue();
    expect(PostResource::resolveDisplayStatus($post->fresh()))->toBe(PostStatus::Scheduled);
});

it('centralizes post publication commands for scheduling and unpublishing', function () {
    $author = User::factory()->create(['username' => 'writer']);
    $category = Category::query()->create(['name' => 'News']);

    $post = Post::query()->create([
        'title' => 'Workflow post',
        'slug' => 'workflow-post',
        'body' => adminRichDocument('Workflow body'),
        'status' => PostStatus::Draft,
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    app(SchedulePost::class)->handle($post, PostPublicationData::fromArray([
        'status' => PostStatus::Published,
        'published_at' => now()->addHours(4),
    ]));

    expect($post->fresh()->published_at?->isFuture())->toBeTrue();
    expect(PostResource::resolveDisplayStatus($post->fresh()))->toBe(PostStatus::Scheduled);

    app(UnpublishPost::class)->handle($post->fresh());

    expect($post->fresh()->status)->toBe(PostStatus::Draft);
    expect($post->fresh()->published_at)->toBeNull();
});

it('creates published projects through the Filament create page workflow', function () {
    $user = createAdminUserWithPermissions('create_project');
    $owner = User::factory()->create(['username' => 'owner-user']);

    $page = new class extends CreateProject
    {
        /**
         * @param  array<string, mixed>  $data
         */
        public function createRecord(array $data): Project
        {
            /** @var Project $project */
            $project = $this->handleRecordCreation($data);

            return $project;
        }
    };

    $page->boot(app(ProjectAdministrativeWorkflow::class));

    $project = $page->createRecord([
        'title' => 'Admin project',
        'slug' => 'admin-project',
        'description' => 'Project created from admin',
        'content' => adminRichDocument('Project body'),
        'status' => ProjectStatus::Published->value,
        'user_id' => $owner->id,
        'featured' => true,
        'sort_order' => 3,
    ]);

    expect($project->fresh()->status)->toBe(ProjectStatus::Published);
    expect($project->fresh()->user_id)->toBe($owner->id);
    expect($project->fresh()->featured)->toBeTrue();
    expect($project->fresh()->sort_order)->toBe(3);
});

it('centralizes project ownership, featuring and ordering commands', function () {
    $originalOwner = User::factory()->create(['username' => 'original-owner']);
    $newOwner = User::factory()->create(['username' => 'new-owner']);

    $project = Project::query()->create([
        'title' => 'Project workflow',
        'slug' => 'project-workflow',
        'description' => 'Workflow description',
        'content' => adminRichDocument('Workflow project body'),
        'status' => ProjectStatus::Draft,
        'user_id' => $originalOwner->id,
        'featured' => false,
        'sort_order' => 0,
    ]);

    app(AssignProjectOwner::class)->handle($project, $newOwner->id);
    app(FeatureProject::class)->handle($project->fresh(), true);
    app(ReorderProjects::class)->handle($project->fresh(), 5);
    app(PublishProject::class)->handle($project->fresh());

    $project = $project->fresh();

    expect($project->user_id)->toBe($newOwner->id);
    expect($project->featured)->toBeTrue();
    expect($project->sort_order)->toBe(5);
    expect($project->status)->toBe(ProjectStatus::Published);
});

it('uses the administrative workflow to sync published project state', function () {
    $owner = User::factory()->create(['username' => 'workflow-owner']);
    $project = Project::query()->create([
        'title' => 'Workflow aggregate',
        'slug' => 'workflow-aggregate',
        'description' => 'Workflow aggregate description',
        'content' => adminRichDocument('Workflow aggregate body'),
        'status' => ProjectStatus::Draft,
    ]);

    app(ProjectAdministrativeWorkflow::class)->sync(
        $project,
        App\Application\Portfolio\DTOs\ProjectListingData::fromArray([
            'user_id' => $owner->id,
            'featured' => true,
            'sort_order' => 7,
        ]),
        ProjectStatus::Published,
    );

    $project = $project->fresh();

    expect($project->user_id)->toBe($owner->id);
    expect($project->featured)->toBeTrue();
    expect($project->sort_order)->toBe(7);
    expect($project->status)->toBe(ProjectStatus::Published);
});

it('uses the publication workflow to sync post publication state', function () {
    $author = User::factory()->create(['username' => 'workflow-author']);
    $category = Category::query()->create(['name' => 'Workflow']);
    $post = Post::query()->create([
        'title' => 'Workflow sync',
        'slug' => 'workflow-sync',
        'body' => adminRichDocument('Workflow sync body'),
        'status' => PostStatus::Draft,
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    app(PostPublicationWorkflow::class)->sync($post, PostPublicationData::fromArray([
        'status' => PostStatus::Published,
        'published_at' => now()->addHours(2),
    ]));

    expect($post->fresh()->status)->toBe(PostStatus::Published);
    expect($post->fresh()->published_at?->isFuture())->toBeTrue();
});

it('keeps public project ordering consistent with featured and sort order', function () {
    Project::query()->create([
        'title' => 'Late project',
        'slug' => 'late-project',
        'description' => 'Late project',
        'content' => adminRichDocument('Late body'),
        'status' => ProjectStatus::Published,
        'featured' => false,
        'sort_order' => 10,
    ]);

    Project::query()->create([
        'title' => 'Featured project',
        'slug' => 'featured-project',
        'description' => 'Featured project',
        'content' => adminRichDocument('Featured body'),
        'status' => ProjectStatus::Published,
        'featured' => true,
        'sort_order' => 99,
    ]);

    Project::query()->create([
        'title' => 'Ordered project',
        'slug' => 'ordered-project',
        'description' => 'Ordered project',
        'content' => adminRichDocument('Ordered body'),
        'status' => ProjectStatus::Published,
        'featured' => false,
        'sort_order' => 1,
    ]);

    $this->get(route('public.projects'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('public/project/Index')
            ->where('projects.data.0.slug', 'featured-project')
            ->where('projects.data.1.slug', 'ordered-project')
            ->where('projects.data.2.slug', 'late-project'));
});
