<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllDataCollectedResource extends JsonResource
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
            'currency' =>   $this->currency,
            'type' =>       $this->balance ? 'Y' : 'X',
            'balance' =>    $this->balance ? $this->balance : $this->parentAmount,
            'email' =>      $this->email ? $this->email : $this->parentEmail,
            'status' =>     $this->status ? $this->status : $this->statusCode,
            'created_at' => $this->registerationDate ? $this->registerationDate : $this->created_at,
            'id' =>         $this->id ? $this->id : $this->parentIdentification,

        ];
    }
}
