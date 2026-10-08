<?php

namespace FilaBoost\FilamentBlueprint\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocsRepository
{
    protected string $docsPath;
    protected ?array $index = null;

    public function __construct(?string $docsPath = null)
    {
        $this->docsPath = $docsPath ?? (function_exists('base_path') ? base_path('docs') : __DIR__ . '/../../docs');
    }

    /**
     * Get or build documentation index.
     */
    public function getIndex(): array
    {
        if ($this->index !== null) {
            return $this->index;
        }

        $this->index = [];
        if (!File::isDirectory($this->docsPath)) {
            return $this->index;
        }

        $files = File::allFiles($this->docsPath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $relPath = str_replace('\\', '/', $file->getRelativePathname());
            $slug = str_replace('.md', '', $relPath);
            $parts = explode('/', $relPath);
            $category = count($parts) > 1 ? $parts[0] : 'general';

            $content = File::get($file->getRealPath());

            // Extract title
            $title = $slug;
            if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
                $title = trim($matches[1]);
            }

            // Extract snippet
            $cleanText = preg_replace('/^#.+$/m', '', $content);
            $cleanText = trim(strip_tags($cleanText));
            $snippet = Str::limit($cleanText, 250);

            $this->index[$slug] = [
                'slug' => $slug,
                'title' => $title,
                'category' => $category,
                'relative_path' => 'docs/' . $relPath,
                'real_path' => $file->getRealPath(),
                'snippet' => $snippet,
            ];
        }

        return $this->index;
    }

    /**
     * Search documentation by query.
     */
    public function search(string $query, ?string $category = null, int $limit = 5): array
    {
        $index = $this->getIndex();
        $queryLower = strtolower(trim($query));
        $terms = preg_split('/\s+/', $queryLower);

        $results = [];

        foreach ($index as $slug => $item) {
            if ($category && !str_starts_with($item['category'], $category) && !str_contains($item['category'], $category)) {
                continue;
            }

            $score = 0;
            $slugLower = strtolower($slug);
            $titleLower = strtolower($item['title']);

            // Exact match boosts
            if ($slugLower === $queryLower || $titleLower === $queryLower) {
                $score += 100;
            }

            if (str_contains($slugLower, $queryLower)) {
                $score += 50;
            }

            if (str_contains($titleLower, $queryLower)) {
                $score += 40;
            }

            // Term matching
            foreach ($terms as $term) {
                if (empty($term)) continue;

                if (str_contains($slugLower, $term)) {
                    $score += 20;
                }
                if (str_contains($titleLower, $term)) {
                    $score += 15;
                }
                if (str_contains(strtolower($item['snippet']), $term)) {
                    $score += 5;
                }
            }

            // Search file content if needed for deep term matching
            if ($score === 0) {
                $rawContent = strtolower(File::get($item['real_path']));
                foreach ($terms as $term) {
                    if (!empty($term) && str_contains($rawContent, $term)) {
                        $score += 3;
                    }
                }
            }

            if ($score > 0) {
                $item['score'] = $score;
                $results[] = $item;
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, $limit);
    }

    /**
     * Retrieve full document by topic slug or path.
     */
    public function get(string $topicId): ?array
    {
        $index = $this->getIndex();
        $normalized = str_replace('.md', '', str_replace('\\', '/', trim($topicId)));
        $normalized = preg_replace('#^docs/#', '', $normalized);

        // Direct key lookup
        if (isset($index[$normalized])) {
            $item = $index[$normalized];
            $item['content'] = File::get($item['real_path']);
            return $item;
        }

        // Fuzzy search by basename
        foreach ($index as $slug => $item) {
            if ($slug === $normalized || basename($slug) === $normalized || str_ends_with($slug, $normalized)) {
                $item['content'] = File::get($item['real_path']);
                return $item;
            }
        }

        return null;
    }

    /**
     * List all categories.
     */
    public function listCategories(): array
    {
        $index = $this->getIndex();
        $categories = [];
        foreach ($index as $item) {
            $categories[$item['category']] = ($categories[$item['category']] ?? 0) + 1;
        }
        return $categories;
    }
}
