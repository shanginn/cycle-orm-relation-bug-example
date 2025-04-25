<?php

declare(strict_types=1);

namespace App\Entity\Subscription\Components;

use App\Entity\Tariff\Page\ListTariffsPage;
use App\Telegram\Declarations\TelegramBotUpdateHandler;
use Phenogram\Bindings\Types\InlineKeyboardButton;
use Phenogram\Bindings\Types\Interfaces\InlineKeyboardButtonInterface;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Framework\Handler\UpdateHandlerInterface;
use Phenogram\Framework\TelegramBot;
use Spiral\Prototype\Traits\PrototypeTrait;
use Throwable;

#[TelegramBotUpdateHandler]
class ShowTariffsButton implements UpdateHandlerInterface
{
    use PrototypeTrait;

    private static string $prefix = 'show_tariffs';

    public static function getButton(?string $goBackTo = null): InlineKeyboardButtonInterface
    {
        return new InlineKeyboardButton(
            text: l('view tariffs button text'),
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
        $user           = $this->userRepository->findByTelegramUserId($telegramUserId);

        if ($user === null) {
            $bot->api->answerCallbackQuery(
                callbackQueryId: $update->callbackQuery->id,
                text: l('not registered message'),
                showAlert: true
            );

            return;
        }

        $bot->api->answerCallbackQuery(
            callbackQueryId: $update->callbackQuery->id,
            text: l('loading'),
        );

        [, $goBackTo] = explode(':', $update->callbackQuery->data);

        $goBackTo = $goBackTo !== '' ? $goBackTo : null;

        $tariffs = $this->tariffRepository->findAllActive();

        $page = new ListTariffsPage(
            tariffs: $tariffs,
            goBackTo: $goBackTo,
        );

        if ($goBackTo !== null) {
            try {
                $bot->api->editMessageText(
                    messageId: $update->callbackQuery->message->messageId,
                    chatId: $update->callbackQuery->message->chat->id,
                    text: $page->text,
                    replyMarkup: $page->replyMarkup,
                    linkPreviewOptions: $page->linkPreviewOptions,
                    parseMode: $page->parseMode,
                );

                return;
            } catch (Throwable) {
            }
        }

        $bot->api->sendMessage(
            chatId: $update->callbackQuery->message->chat->id,
            text: $page->text,
            replyMarkup: $page->replyMarkup,
            linkPreviewOptions: $page->linkPreviewOptions,
            parseMode: $page->parseMode,
        );
    }
}