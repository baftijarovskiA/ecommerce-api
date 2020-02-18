<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //

    public function index(){
        $posts = Address::all();
        return response()->json($posts,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'postal_code' => 'integer|nullable',
            'first_address' => 'required|string|max:255',
            'second_address' => 'nullable',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $address = new Address();
        $address->name = $request->input('name');
        $address->email = $request->input('email');
        $address->phone = $request->input('phone');
        $address->postal_code = $request->input('postal_code');
        $address->first_address = $request->input('first_address');
        $address->second_address = $request->input('second_address');
        $address->city = $request->input('city');
        $address->country = $request->input('country');
        if (auth()->user()){
            $address->user_id = auth()->user()->id;
        } else {
            $address->user_id = null;
        }
        $address->save();

        return response()->json(['success'=>$address],200);

    }
}
