<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Notification;

class ValidationNotification
{
    protected array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $context, string $message)
    {
        $this->errors[] = ['context' => $context, 'message' => $message];
    }

    public function hasError(): bool
    {
        return (bool) count($this->errors);
    }

    public function messages(string $context = null): string
    {
        $message = '';

        foreach ($this->errors as $error) {
            if ($context == null || $error['context'] === $context) {
                $message .= "{$error['context']}: {$error['message']}, ";
            }
        }

        return substr($message, 0, -2);
    }
}
