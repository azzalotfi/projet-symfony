<?php

namespace App\Message;

class ReturnReminderMessage
{
    private int $empruntId;

    public function __construct(int $empruntId)
    {
        $this->empruntId = $empruntId;
    }

    public function getEmpruntId(): int
    {
        return $this->empruntId;
    }
}
