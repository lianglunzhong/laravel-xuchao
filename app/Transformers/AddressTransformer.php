<?php

namespace App\Transformers;

use App\Models\UserAddress;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{

    public function transform(UserAddress $address)
    {
        return [
            'id' => $address->id,
            'user_id' => $address->user_id,
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'address' => $address->address,
            'full_address' => $address->full_address,
            'contact_name' => $address->contact_name,
            'contact_phone' => $address->contact_phone,
            'last_used_at' => $address->last_used_at,
        ];
    }
}