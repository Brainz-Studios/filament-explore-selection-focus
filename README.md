# brainz-studios/filament-explore-selection-focus

When a Filament **Explore** / **Media Library** picker is reopened for editing, pre-selected files are marked but often off-screen (wrong folder or scroll position). This package:

1. Opens the picker modal in the **folder of the selected file(s)**.
2. **Scrolls** the file grid so the first selected item is visible.

Built for [Ralph J. Smit Filament Explore](https://github.com/ralphjsmit/laravel-filament-explore) and [Filament Media Library](https://github.com/ralphjsmit/laravel-filament-media-library).

## Requirements

- PHP 8.2+
- Laravel 11 / 12 / 13
- Filament 5
- `ralphjsmit/laravel-filament-explore` ^1.0

## Install

```bash
composer require brainz-studios/filament-explore-selection-focus
php artisan vendor:publish --tag=filament-explore-selection-focus-config
```

Register the plugin on your Filament panel (e.g. next to `FilamentMediaLibrary`):

```php
use BrainzStudios\FilamentExploreSelectionFocus\FilamentExploreSelectionFocusPlugin;

$panel->plugins([
    FilamentMediaLibrary::make(),
    FilamentExploreSelectionFocusPlugin::make(),
]);
```

No other setup is required — `FilePicker` / `MediaPicker` instances are configured automatically.

## Configuration

```php
// config/filament-explore-selection-focus.php
return [
    'enabled' => true,
    'scroll_behavior' => 'smooth', // or 'auto'
    'scroll_block' => 'center',    // 'start' | 'center' | 'end' | 'nearest'
];
```

Disable per panel:

```php
FilamentExploreSelectionFocusPlugin::make()->enabled(false),
```

## How it works

- **PHP:** `FilePicker::configureUsing()` adds a `modifySelectFileActionUsing()` hook. When the picker already has state, `SelectedFilesFolderResolver` resolves the parent folder via the Explore driver and sets `SelectFileAction::defaultFolder()`.
- **JS:** A small Filament asset watches Explore modals after actions open and calls `scrollIntoView()` on the first visibly selected file tile/row.

## Limitations

- If the selected file sits on another **pagination page** within the same folder, the folder opens correctly but scroll may not find the DOM node until you change page. Resolving the correct page would require deeper Explore integration (future improvement / upstream PR).

## Publish to Satis

1. Push this repository to GitHub.
2. Tag a version (`1.0.0`).
3. Mirror into your Satis instance.

## License

Proprietary — Brainz Studios.
