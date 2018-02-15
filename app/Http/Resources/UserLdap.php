<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserLdap extends Resource
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
            'username' => strtoupper($this->getAccountName()),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName()
        ];
    }
}
