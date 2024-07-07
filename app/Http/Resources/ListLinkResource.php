<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this['id'],
            'user_id'       => $this['user_id'],
            'original_link' => $this['org_link'],
            'short_link'    => $_ENV['APP_URL'] . '/api/v1/links/' . $this['short_link'],
            'clicks'        => $this['click_count'],
        ];
    }
}
