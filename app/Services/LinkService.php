<?php

namespace App\Services;

use App\Dto\CreateLinkRequestDto;
use App\Repository\Link\LinkRepository;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Redis;
use Vinkla\Hashids\Facades\Hashids;

class LinkService
{
    public function __construct(
        public readonly LinkRepository $linkRepository,
    ) {
    }

    public function create(CreateLinkRequestDto $dto): void
    {
        $this->checkLinkExist($dto->userId, $dto->link);
        $shortLink = $this->shortenLink($dto->userId);

        $link = $this->linkRepository->create($dto, $shortLink);
        $this->setClickInRedis($link->id, 0);
    }

    public function list(int $limit)
    {
        $result = [];
        $topLinksId = $this->getTopShortLink($limit);
        if (empty($topLinksId)) {
            return [];
        }

        $topLinks = $this->linkRepository->getByIds(array_keys($topLinksId));
        foreach ($topLinks as $topLink) {
            $result[] = [
                'id'          => $topLink->id,
                'user_id'     => $topLink->user_id,
                'short_link'  => $topLink->short_link,
                'org_link'    => $topLink->org_link,
                'click_count' => $topLinksId[$topLink->id],
            ];
        }

        return $result;
    }

    public function getUserLinks(int $userId)
    {
        return $this->linkRepository->getUserLinks($userId);
    }

    public function search(string $search)
    {
        return $this->linkRepository->searchLinks($search);
    }

    public function click(string $shortLink)
    {
        $link = $this->linkRepository->getByShortLink($shortLink);
        if (is_null($link)) {
            throw new \Exception('Link not found');
        }
        $this->setClickInRedis($link->id, 1);

        return $link->org_link;
    }

    public function checkLinkExist($userId, $link): void
    {
        $criteria = [
            'user_id' => $userId,
            'org_link' => $link,
        ];

        $link = $this->linkRepository->getByCriteria($criteria);
        if (!is_null($link)) {
            throw new \Exception('this link is already exist');
        }
    }

    public function shortenLink($userId): string
    {
        $maxLinkId = Redis::get('max_link_id');
        Redis::incr('max_link_id', 1);

        return Hashids::encode($maxLinkId + 1, $userId);
    }

    public function setClickInRedis(int $linkId, $increase)
    {
        Redis::zincrby('short_link:clicks', '+' . $increase, $linkId);
    }

    public function getTopShortLink(int $limit)
    {
        return Redis::zrevrange('short_link:clicks', 0, $limit - 1, ['WITHSCORES']);
    }
}
