<?php

declare(strict_types=1);

namespace App\Entity\User\Tool;

use App\Openai\ChatCompletion\CompletionRequest\ToolChoice;
use App\Openai\ChatCompletion\Message\Assistant\KnownFunctionCall;
use App\Openai\ChatCompletion\Message\UserMessage;
use App\Openai\Openai;
use RuntimeException;

final readonly class ParseUserInfoHandler
{
    public function __construct(private Openai $openai) {}

    public function parseUserInfo(string $message): ParseUserInfoSchema
    {
        $response = $this->openai->completion(
            system: <<<'SYSTEM'
                You are user info normalizer.
                Do not modify the meaning of each field.
                But you can clean, fix typos and normalize the data.
                Check the birth date before providing it.
                SYSTEM,
            messages: [
                new UserMessage(content: $message),
            ],
            tools: [ParseUserInfoSchema::class],
            toolChoice: ToolChoice::useTool(ParseUserInfoSchema::class),
        );

        if (count($response->choices) === 0) {
            throw new RuntimeException('No response from OpenAI.');
        }

        $choice = $response->choices[0]->message?->toolCalls[0];

        if (!$choice instanceof KnownFunctionCall || !$choice->arguments instanceof ParseUserInfoSchema) {
            throw new RuntimeException('Unexpected response from OpenAI.');
        }

        return $choice->arguments;
    }
}