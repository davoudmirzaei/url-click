<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSearchLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
        'id'            => $this->id,
        'user_id'       => $this->user_id,
        'original_link' => $this->org_link,
        'short_link'    => $_ENV['APP_URL'] . '/api/v1/' . $this->short_link,
        'clicks'        => $this->clicks,
        'created_at'    => $this->created_at,
        'updated_at'    => $this->updated_at,
    ];
    }
}
