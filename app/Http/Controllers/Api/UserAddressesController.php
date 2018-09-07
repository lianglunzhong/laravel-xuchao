<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\Api\UserAddressRequest;
use App\Transformers\AddressTransformer;
use Carbon\Carbon;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
    	$addresses = $this->user()
    					->addresses()
    					->orderBy('last_used_at', 'desc')
    					->get();

    	return $this->response->collection($addresses, new AddressTransformer());
    }

    public function store(UserAddressRequest $request)
    {
        $data= $request->only([
            'province',
            'city',
            'district',
            'address',
            'contact_name',
            'contact_phone',
        ]);

        $data['last_used_at'] = Carbon::now();

        $user = $this->user();

        // 添加地址
        $user->addresses()->create($data);

        $addresses = $user->addresses()
    					->orderBy('last_used_at', 'desc')
    					->get();

    	return $this->response->collection($addresses, new AddressTransformer());
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('own', $address);
        
        $address->delete();

        return $this->response->noContent();
    }
}
