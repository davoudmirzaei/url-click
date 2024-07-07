<?php

namespace App\Dto\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface RequestDtoInterface
{
    public static function getFromRequest(Request $request): static;
}
