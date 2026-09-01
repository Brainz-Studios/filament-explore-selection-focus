<?php

namespace BrainzStudios\FilamentExploreSelectionFocus;

use BrainzStudios\FilamentExploreSelectionFocus\Support\SelectedFilesFolderResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentExploreSelectionFocusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-explore-selection-focus')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SelectedFilesFolderResolver::class);
    }
}
