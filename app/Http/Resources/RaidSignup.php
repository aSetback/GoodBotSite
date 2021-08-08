<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RaidSignup extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->player,
            'signup' => $this->signup,
            'reserve' => $this->reserve ? $this->reserve->item->name : null,
            'confirmed' => $this->confirmed,
            'createdAt' => $this->createdAt
        ];
    }
}
