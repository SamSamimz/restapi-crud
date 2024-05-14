<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'product' => [
                'id' => $this->id,
                'title' => $this->title,
                'price' => $this->price,
                'description' => $this->description,
            ],
            'author' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
        ];
    }
}
