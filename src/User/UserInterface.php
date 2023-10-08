<?php

declare(strict_types=1);

namespace Talendor\ElevenLabsClient\User;

interface UserInterface
{
    public function getUserInfo();
    public function getUserSubscription();
}
