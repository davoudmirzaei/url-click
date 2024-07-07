<?php

namespace App\Http\Api\V1;

use App\Dto\CreateLinkRequestDto;
use App\Http\Requests\CreateLinkRequest;
use App\Http\Requests\ListLinkRequest;
use App\Http\Resources\GetSearchLinkResource;
use App\Http\Resources\GetUserLinkResource;
use App\Http\Resources\ListLinkResource;
use App\Services\LinkService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;

class LinkController extends Controller
{
    public function __construct(
        public readonly LinkService $linkService
    ) {
    }

    public function create(CreateLinkRequest $request)
    {
        $dto = CreateLinkRequestDto::getFromRequest($request);
        $this->linkService->create($dto);
    }

    public function index(Request $request)
    {
        $userId = rand(1, 5); //toDo $request->user()->id,
        $result = $this->linkService->getUserLinks($userId);

        return GetUserLinkResource::collection($result);
    }

    public function list(ListLinkRequest $request)
    {
        $limit  = $request->query->get('limit');
        $result = $this->linkService->list($limit);

        return ListLinkResource::collection($result);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $result = $this->linkService->search($search);

        return GetSearchLinkResource::collection($result);
    }

    public function click(Request $request)
    {
        $shortLink = request()->segment(count(request()->segments()));
        $link = $this->linkService->click($shortLink);

        return redirect()->away($link);
    }
}
