<?php

namespace App\Http\Requests\Api;

class UserAddressRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'province'      => 'required',
            'city'          => 'required',
            // 'district'      => 'required',
            'address'       => 'required',
            'contact_name'  => 'required',
            'contact_phone' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'province'      => '省',
            'city'          => '城市',
            // 'district'      => '地区',
            'address'       => '详细地址',
            'contact_name'  => '姓名',
            'contact_phone' => '电话',
        ];
    }
}
