<?php

declare(strict_types=1);

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
            'auth' => $this->resource->auth,
            'username' => $this->resource->username,
            'password' => $this->resource->password,
            'query' => $this->resource->query,
            'refreshed_at' => $this->resource->refreshed_at,
            'updated_at' => $this->resource->updated_at,
            'created_at' => $this->resource->created_at,
            'url' => $this->resource->url(),
            'fields' => $this->resource->fields,
        ];
    }
}
