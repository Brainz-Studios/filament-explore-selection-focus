<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch for folder navigation and scroll-into-view behavior.
    |
    */
    'enabled' => env('FILAMENT_EXPLORE_SELECTION_FOCUS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Scroll behavior
    |--------------------------------------------------------------------------
    |
    | Passed to Element.scrollIntoView() as the `behavior` option.
    | Use "auto" for an instant jump or "smooth" for animated scrolling.
    |
    */
    'scroll_behavior' => env('FILAMENT_EXPLORE_SELECTION_FOCUS_SCROLL_BEHAVIOR', 'smooth'),

    /*
    |--------------------------------------------------------------------------
    | Scroll block alignment
    |--------------------------------------------------------------------------
    |
    | Passed to Element.scrollIntoView() as the `block` option.
    |
    */
    'scroll_block' => env('FILAMENT_EXPLORE_SELECTION_FOCUS_SCROLL_BLOCK', 'center'),

];
