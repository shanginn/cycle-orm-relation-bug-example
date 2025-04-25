<?php

declare(strict_types=1);

namespace App\Endpoint\Web\Middleware;

use App\Entity\Settings\Language;
use App\Entity\Settings\SettingsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Translator\Translator;
use Spiral\Translator\TranslatorInterface;

/**
 * The middleware that sets the application locale based on the "Accept-Language" header.
 * List of available locales is taken from the translator.
 */
final class LocaleSelector implements MiddlewareInterface
{
    /** @var string[] */
    private array $availableLocales;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SettingsRepository $settingsRepository,
    ) {
        $this->availableLocales = $this->translator->getCatalogueManager()->getLocales();
    }

    private function detectLocale(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('Accept-Language');
        if (empty($header)) {
            return null;
        }

        $acceptLanguages = $this->parseAcceptLanguage($header);

        return array_find(
            $acceptLanguages,
            fn (string $lang) => in_array($lang, $this->availableLocales, true)
        );
    }

    private function parseAcceptLanguage(string $header): array
    {
        $acceptLanguages = [];
        foreach (explode(',', $header) as $value) {
            $pos = strpos($value, ';');
            if ($pos !== false) {
                $acceptLanguages[] = substr($value, 0, $pos);
            }

            $acceptLanguages[] = $value;
        }

        return $acceptLanguages;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $defaultLocale = Language::default()->value;

        // Try to get user's preferred language from settings
        $telegramId = $request->getAttribute('telegramId');
        if ($telegramId !== null) {
            $settings = $this->settingsRepository->findByTelegramUserId($telegramId);
            if ($settings !== null) {
                $locate = $settings->language->value;
                $this->translator->setLocale($locate);

                return $handler->handle($request);
            }
        }

        // Fallback to Accept-Language header if no user settings
        $locale = $this->detectLocale($request) ?? $defaultLocale;
        $this->translator->setLocale($locale);

        return $handler->handle($request);
    }
}
