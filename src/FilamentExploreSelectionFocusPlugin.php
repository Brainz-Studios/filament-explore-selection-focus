<?php

namespace BrainzStudios\FilamentExploreSelectionFocus;

use BrainzStudios\FilamentExploreSelectionFocus\Support\SelectedFilesFolderResolver;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Arr;
use RalphJSmit\Filament\Explore\Enums\FileType;
use RalphJSmit\Filament\Explore\Filament\Actions\SelectFileAction;
use RalphJSmit\Filament\Explore\Filament\Forms\Components\FilePicker;

class FilamentExploreSelectionFocusPlugin implements Plugin
{
    protected bool $enabled = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament()->getPlugin(static::make()->getId());
    }

    public function getId(): string
    {
        return 'brainz-studios/filament-explore-selection-focus';
    }

    public function enabled(bool $condition = true): static
    {
        $this->enabled = $condition;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->assets([
            Js::make(
                'scroll-to-selected-files',
                __DIR__.'/../resources/js/scroll-to-selected-files.js',
            ),
        ], 'filament-explore-selection-focus');
    }

    public function boot(Panel $panel): void
    {
        if (! $this->enabled || ! config('filament-explore-selection-focus.enabled', true)) {
            return;
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_BEFORE,
            fn (): string => '<script>window.filamentExploreSelectionFocus = '.json_encode([
                'scrollBehavior' => config('filament-explore-selection-focus.scroll_behavior', 'smooth'),
                'scrollBlock' => config('filament-explore-selection-focus.scroll_block', 'center'),
            ]).';</script>',
            scopes: $panel->getId(),
        );

        FilePicker::configureUsing(function (FilePicker $picker): void {
            $picker->modifySelectFileActionUsing(function (SelectFileAction $action, FilePicker $component): SelectFileAction {
                $selectedKeys = Arr::wrap($component->getRawState());

                if ($selectedKeys === []) {
                    return $action;
                }

                $folder = app(SelectedFilesFolderResolver::class)->resolve(
                    $component->getDriver(),
                    $selectedKeys,
                );

                if ($folder?->getType() === FileType::Folder) {
                    $action->defaultFolder($folder);
                }

                return $action;
            });
        });
    }
}
