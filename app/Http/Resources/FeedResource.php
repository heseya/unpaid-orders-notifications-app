<?php

namespace App\Http\Resources;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Feed $resource
 */
class FeedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'query' => $this->resource->query,
            'refreshed_at' => $this->resource->refreshed_at,
            'updated_at' => $this->resource->updated_at,
            'created_at' => $this->resource->created_at,
            'fields' => $this->resource->fields,
        ];
    }
}
