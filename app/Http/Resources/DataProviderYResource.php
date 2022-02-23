<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DataProviderYResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'balance' => $this->balance,
            'currency' => $this->currency,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'id' => $this->id,
        ];
    }
}
