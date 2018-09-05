<?php

namespace App\Http\Requests\Api;

class WeappAuthorizationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'code' => 'required|string',
                ];
                break;

            case 'PUT':
                return [
                    'avatar' => 'required|string',
                    'name' => 'required|string',
                ];
                break;
        }
               
    }
}
