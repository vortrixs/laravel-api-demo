<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = [];

        foreach ($this->collection as $user)
        {
            $users[] = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }

        return $users;
    }
}
