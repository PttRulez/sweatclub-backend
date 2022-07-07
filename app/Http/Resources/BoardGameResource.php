<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BoardGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(
            [
                'id' => $this->id,
                'name' => $this->name,
                'has_points' => $this->has_points,
                'imageUrl' => config('app.url') . $this->image_path,
                'thumbnailUrl' =>  config('app.url') . $this->thumbnail,
            ]);
    }
}
