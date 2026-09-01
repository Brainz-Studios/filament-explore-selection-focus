<?php

namespace BrainzStudios\FilamentExploreSelectionFocus\Support;

use Illuminate\Support\Arr;
use RalphJSmit\Filament\Explore\Data\FileData;
use RalphJSmit\Filament\Explore\Drivers\Contracts\Driver;
use RalphJSmit\Filament\Explore\Enums\FileType;

class SelectedFilesFolderResolver
{
    /**
     * Resolve the folder that should be opened when editing an existing file selection.
     *
     * When every selected file lives in the same folder, that folder is returned.
     * Otherwise the folder of the first selected key (preserving picker state order) is used.
     */
    public function resolve(Driver $driver, string | array | null $selectedFileKeys): ?FileData
    {
        $keys = array_values(array_filter(Arr::wrap($selectedFileKeys), fn (mixed $key): bool => is_string($key) && $key !== ''));

        if ($keys === []) {
            return null;
        }

        $files = $driver->findFiles(FileType::File, $keys);

        if ($files->isEmpty()) {
            return null;
        }

        $folders = $files
            ->map(fn (FileData $file): ?FileData => $file->getFolder())
            ->filter()
            ->unique(fn (FileData $folder): string => $folder->getKey())
            ->values();

        if ($folders->count() === 1) {
            return $folders->first();
        }

        foreach ($keys as $key) {
            $file = $files->first(fn (FileData $candidate): bool => $candidate->getKey() === $key);

            if ($file?->getFolder() instanceof FileData) {
                return $file->getFolder();
            }
        }

        return $folders->first();
    }
}
