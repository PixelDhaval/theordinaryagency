<?php

namespace Database\Factories;

use App\Models\Project;
use App\Support\ServiceCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(3, true));
        $discipline = fake()->randomElement(ServiceCatalog::all()->pluck('name')->all());

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'client' => fake()->company(),
            'discipline' => $discipline,
            'services' => [$discipline],
            'year' => (int) fake()->year(),
            'summary' => fake()->sentence(12),
            'content' => [
                ['type' => 'markdown', 'data' => ['body' => collect(fake()->paragraphs(3))->implode("\n\n")]],
            ],
            'published_at' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }

    public function inDiscipline(string $discipline): static
    {
        return $this->state(fn (): array => ['discipline' => $discipline, 'services' => [$discipline]]);
    }

    public function noindex(): static
    {
        return $this->state(fn (): array => ['noindex' => true]);
    }
}
