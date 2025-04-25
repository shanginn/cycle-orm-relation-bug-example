<?php

declare(strict_types=1);

namespace App\Entity\Subscription\Components;

use App\Entity\Settings\SettingsPage;
use App\Telegram\Declarations\TelegramBotUpdateHandler;
use Phenogram\Bindings\Types\InlineKeyboardButton;
use Phenogram\Bindings\Types\Interfaces\InlineKeyboardButtonInterface;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Framework\Handler\UpdateHandlerInterface;
use Phenogram\Framework\TelegramBot;
use Spiral\Prototype\Traits\PrototypeTrait;
use Throwable;

#[TelegramBotUpdateHandler]
class ShowSettingsButtonComponent implements UpdateHandlerInterface
{
    use PrototypeTrait;

    private static string $prefix = 'show_settings';

    public static function getButton(?string $goBackTo = null): InlineKeyboardButtonInterface
    {
        return new InlineKeyboardButton(
            text: l('settings button text'),
            callbackData: self::$prefix . ':' . ($goBackTo ?? ''),
        );
    }

    public static function supports(UpdateInterface $update): bool
    {
        return $update->callbackQuery !== null
            && str_starts_with($update->callbackQuery->data, self::$prefix);
    }

    public function handle(UpdateInterface $update, TelegramBot $bot): void
    {
        $telegramUserId = $update->callbackQuery->from->id;

        [, $goBackTo] = explode(':', $update->callbackQuery->data);

        $bot->api->answerCallbackQuery(
            callbackQueryId: $update->callbackQuery->id,
            text: l('loading'),
        );

        $settingsPage = new SettingsPage(
            userSettings: $this->settingsService->getByTelegramUserId($telegramUserId),
            goBackTo: $goBackTo !== '' ? $goBackTo : null
        );

        try {
            $bot->api->editMessageText(
                messageId: $update->callbackQuery->message->messageId,
                chatId: $update->callbackQuery->message->chat->id,
                text: $settingsPage->text,
                replyMarkup: $settingsPage->replyMarkup,
            );
        } catch (Throwable) {
            $bot->api->sendMessage(
                chatId: $update->callbackQuery->message->chat->id,
                text: $settingsPage->text,
                replyMarkup: $settingsPage->replyMarkup,
            );
        }
    }
}