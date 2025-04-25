<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Bot\CancelCommandHandler;
use App\Bot\PageManager;
use App\Entity\User\Tool\ParseUserInfoHandler;
use App\Telegram\Declarations\TelegramBotUpdateHandler;
use DateTimeImmutable;
use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Framework\Handler\AbstractCommandHandler;
use Phenogram\Framework\Handler\UpdateHandlerInterface;
use Phenogram\Framework\TelegramBot;
use Throwable;

#[TelegramBotUpdateHandler]
class SaveUserInfoHandler implements UpdateHandlerInterface
{
    public function __construct(
        private readonly PageManager $pageManager,
        private readonly UserService $userInfoService,
        private readonly ParseUserInfoHandler $parseUserInfoHandler,
    ) {}

    public function supports(UpdateInterface $update): bool
    {
        return $update->message?->text !== null
            && count(AbstractCommandHandler::extractCommands($update->message)) === 0
            && !CancelCommandHandler::supports($update)
            && $this->pageManager->onPage($update->message->from->id, 'bio');
    }

    public function handle(UpdateInterface $update, TelegramBot $bot): void
    {
        $parsedInfo = $this->parseUserInfoHandler->parseUserInfo($update->message->text);

        $userInfo = $this->userInfoService->getByTelegramUserId($update->message->from->id);

        if ($parsedInfo->fullName !== null) {
            $userInfo->fullName = $parsedInfo->fullName;
        }

        $birthDate = null;

        if ($parsedInfo->birthDateMonth !== null && $parsedInfo->birthDateDay !== null) {
            if ($parsedInfo->birthDateYear !== null) {
                $birthDate = new DateTimeImmutable(
                    "{$parsedInfo->birthDateYear}-{$parsedInfo->birthDateMonth}-{$parsedInfo->birthDateDay}"
                );
            } elseif ($parsedInfo->age !== null) {
                $birthDate = (new DateTimeImmutable())->setDate(
                    year: (new DateTimeImmutable())->format('Y') - $parsedInfo->age,
                    month: $parsedInfo->birthDateMonth,
                    day: $parsedInfo->birthDateDay
                );
            }
        }

        if ($birthDate !== null) {
            if ($parsedInfo->birthTime !== null) {
                try {
                    [$hour, $minute] = explode(':', $parsedInfo->birthTime);
                    $birthDate       = $birthDate->setTime(
                        hour: intval($hour),
                        minute: intval($minute)
                    );
                } catch (Throwable) {
                }
            }

            $userInfo->birthDateTime = $birthDate;
        }

        if ($parsedInfo->birthPlace !== null) {
            $userInfo->birthPlace = $parsedInfo->birthPlace;
        }

        if ($parsedInfo->title !== null) {
            $userInfo->title = $parsedInfo->title;
        }

        if ($parsedInfo->gender !== null) {
            $userInfo->gender = $parsedInfo->gender;
        }

        $this->userInfoService->save($userInfo);

        $this->pageManager->changePage($update->message->from->id, PageManager::DEFAULT);

        $bot->api->sendMessage(
            chatId: $update->message->chat->id,
            text: l('bio.save info response', [
                'info' => $userInfo->sprint(),
            ]),
        );
    }
}