<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Bot\PageManager;
use App\Telegram\Declarations\TelegramBotUpdateHandler;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Framework\Handler\AbstractCommandHandler;
use Phenogram\Framework\TelegramBot;

#[TelegramBotUpdateHandler]
class BioCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private readonly PageManager $pageManager,
        private readonly UserService $userService,
    ) {}

    public static function supports(UpdateInterface $update): bool
    {
        return self::hasCommand($update, '/bio');
    }

    public function handle(UpdateInterface $update, TelegramBot $bot): void
    {
        $user = $this->userService->findByTelegramUserId($update->message->from->id);

        $message = l('bio.tell about yourself') . "\n";
        $message .= l('bio.cancel instruction') . "\n";

        if (trim($user?->sprint() ?? '') !== '') {
            $message .= l('bio.current info') . "\n" . $user->sprint();
        } else {
            $message .= l('bio.example');
        }

        $bot->api->sendMessage(
            chatId: $update->message->chat->id,
            text: $message,
        );

        $this->pageManager->changePage($update->message->from->id, 'bio');
    }
}