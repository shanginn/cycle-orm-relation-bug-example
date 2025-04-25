<?php

declare(strict_types=1);

namespace App\Entity\Subscription\Components;

use App\Telegram\Declarations\TelegramBotUpdateHandler;
use Phenogram\Bindings\Types\InlineKeyboardButton;
use Phenogram\Bindings\Types\InlineKeyboardMarkup;
use Phenogram\Bindings\Types\Interfaces\InlineKeyboardButtonInterface;
use Phenogram\Bindings\Types\Interfaces\InlineKeyboardMarkupInterface;
use Phenogram\Bindings\Types\Interfaces\MessageInterface;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Framework\Handler\UpdateHandlerInterface;
use Phenogram\Framework\TelegramBot;
use Spiral\Prototype\Traits\PrototypeTrait;
use Throwable;

#[TelegramBotUpdateHandler]
class RenovateSubscriptionButton implements UpdateHandlerInterface
{
    use PrototypeTrait;

    private static string $prefix = 'renovate_subscription';

    private function replaceRenewButtonWithCancelButton(InlineKeyboardMarkupInterface $keyboard): InlineKeyboardMarkupInterface
    {
        $buttons = $keyboard->inlineKeyboard;
        if (count($buttons) === 0) {
            return $keyboard;
        }

        foreach ($buttons as $rowIndex => $row) {
            foreach ($row as $buttonIndex => $button) {
                if ($button->callbackData === self::$prefix) {
                    $buttons[$rowIndex][$buttonIndex] = CancelSubscriptionButton::getButton();
                }
            }
        }

        $keyboard->inlineKeyboard = $buttons;

        return $keyboard;
    }

    public static function supports(UpdateInterface $update): bool
    {
        return $update->callbackQuery !== null
            && $update->callbackQuery->data === self::$prefix;
    }

    public static function getButton(): InlineKeyboardButtonInterface
    {
        return new InlineKeyboardButton(
            text: l('subscription.renovation.button'),
            callbackData: self::$prefix
        );
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

        if ($user->getActiveSubscription() === null) {
            $bot->api->answerCallbackQuery(
                callbackQueryId: $update->callbackQuery->id,
                text: l('subscription.no_active_subscription'),
                showAlert: true
            );

            return;
        }

        try {
            $workflow = $this->subscriptionWorkflowHandler->getRunningWorkflow($user->getActiveSubscription());
            $workflow->renovate();

            $bot->api->answerCallbackQuery(
                callbackQueryId: $update->callbackQuery->id,
                text: l('subscription.renovation.message')
            );
        } catch (Throwable $e) {
            dump($e);
            $bot->api->sendMessage(
                chatId: $update->callbackQuery->from->id,
                text: l('subscription.renovation.failed'),
                parseMode: 'HTML'
            );

            return;
        }

        $message = $update->callbackQuery->message;

        if (!$message instanceof MessageInterface) {
            return;
        }

        $bot->api->sendMessage(
            chatId: $update->callbackQuery->from->id,
            text: l('subscription.renovation.initiated'),
            parseMode: 'HTML'
        );

        try {
            $bot->api->editMessageText(
                chatId: $message->chat->id,
                messageId: $message->messageId,
                text: $message->text,
                entities: $message->entities,
                replyMarkup: $this->replaceRenewButtonWithCancelButton($message->replyMarkup),
                linkPreviewOptions: $message->linkPreviewOptions
            );
        } catch (Throwable) {
            $bot->api->sendMessage(
                chatId: $update->callbackQuery->from->id,
                text: l('subscription.cancellation.initiated', [
                    'date' => $user->getActiveSubscription()->endDate->format('d.m.Y H:i:s'),
                ]),
                replyMarkup: new InlineKeyboardMarkup(
                    inlineKeyboard: [CancelSubscriptionButton::getButton()]
                ),
                parseMode: 'HTML'
            );
        }
    }
}