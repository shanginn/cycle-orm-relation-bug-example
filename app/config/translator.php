<?php

/**
 * Translator component configuration.
 *
 * @see https://spiral.dev/docs/advanced-i18n#configuration
 */
return [
    'locale'         => env('LOCALE', 'ru'),
    'fallbackLocale' => 'ru',
    'directory'      => directory('locale'),
    'autoRegister'   => env('DEBUG', true),
];
