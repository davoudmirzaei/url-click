<?php

namespace App\Dto;

use App\Dto\Interfaces\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateLinkRequestDto implements RequestDtoInterface
{
    public function __construct(
        public readonly string $userId,
        public readonly string $link,
    ) {

    }

    public static function getFromRequest(Request $request): static
    {

        $data = $request->request;

        return new static(
            rand(1,5), //toDo $request->user()->id,
            $data->get('link'),
        );
    }
}
