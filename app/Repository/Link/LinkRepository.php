<?php

namespace App\Repository\Link;

use App\Dto\CreateLinkRequestDto;
use App\Models\Link;

class LinkRepository
{
    public function create(CreateLinkRequestDto $dto, $shortLink)
    {
        return Link::create([
            'org_link' => $dto->link,
            'user_id' => $dto->userId,
            'short_link' => $shortLink
        ]);
    }

    public function getByCriteria(array $criteria)
    {
        return Link::where($criteria)->first();
    }

    public function getByShortLink(string $shortLink)
    {
        return Link::where('short_link', $shortLink)->first();
    }

    public function getByIds(array $topLinksId)
    {
        return Link::whereIn('id', $topLinksId)
            ->orderByRaw("FIELD(id, " . implode(",", $topLinksId) . ")")->get();
    }

    public function getUserLinks($userId)
    {
        return Link::where('user_id', $userId)->get();
    }

    public function searchLinks(string $search)
    {
        return Link::where('org_link', 'like', '%' . $search . '%')->get();
    }
}
