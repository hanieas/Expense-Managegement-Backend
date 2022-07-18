<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'username' => $this->username,
            'currency_id' => $this->currency_id,
            'created_at' => $this->created_at,
            'token' => $this->token,
        ];
    }
}
