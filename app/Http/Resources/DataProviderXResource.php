<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DataProviderXResource extends JsonResource
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
                'parentAmount' => $this->parentAmount,
                'currency' => $this->currency,
                'parentEmail' => $this->parentEmail,
                'statusCode' => $this->statusCode,
                'registerationDate' => $this->registerationDate,
                'parentIdentification' => $this->parentIdentification,
            ];
    }
}
