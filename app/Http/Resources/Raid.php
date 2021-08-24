<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Raid extends JsonResource
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
            'name' => $this->raid,
            'date' => $this->date,
            'guild' => $this->guildID,
            'channelID' => $this->channelID,
            'softReserve' => $this->softreserve ? 1 : 0,
            'locked' => $this->locked ? 1 : 0,
            'title' => $this->title,
            'description' => $this->description,
            'time' => $this->time,
            'createdAt' => $this->createdAt,
        ];
    }
}
