<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class UserLdap extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request = null)
    {
        return [
            'username'   => $this->getAccountName(),
            'email'      => $this->getEmail(),
            'name'       => $this->getCommonName(),
            'first_name' => $this->getFirstName(),
            'last_name'  => $this->getLastName()
        ];
    }
}
