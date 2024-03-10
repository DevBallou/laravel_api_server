<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => strtoupper($this->title),
            'body' => $this->body,
            'created_at' => $this->created_at->format('M d, Y'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
