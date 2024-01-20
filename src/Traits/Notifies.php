<?php

namespace Pieldefoca\Lux\Traits;

trait Notifies
{
    public function notifySuccess(string $message, ?string $redirectUrl = null)
    {
        $this->dispatch('notify-success', message: $message, redirectUrl: $redirectUrl);
    }

    public function notifyError(string $message)
    {
        $this->dispatch('notify-error', message: $message);
    }
}