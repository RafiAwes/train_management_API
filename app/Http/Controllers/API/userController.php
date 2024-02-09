<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\userModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function userFunction(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'balance' => 'required',
        ]);

        if($validator->fails()){

            return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
        }else{

            $user = userModel::create([
            'user_name' => $request->user_name,
            'balance' => $request->balance,
            ]);

            if($user){

                return response()->json([
                    'status' => 201,
                    'user'=>$user,
                    'message'=> "User added successfully"
                ], 201);
            }else{
                return response()->json([
                    'status' => 500,
                    'message'=> "Something went wrong"
                ], 500);
            }

        }
    }

    public function show($id)
    {
        $user = userModel::findOrFail($id);

        return response()->json([
            'wallet_id' => $user->id,
            'balance' => $user->balance,
            'wallet_user' => [
                'user_id' => $user->id,
                'user_name' => $user->user_name,
            ],
        ]);
    }

    public function update(Request $request, $walletId)
    {
        $request->validate([
            'recharge' => 'required|integer|between:100,10000',
        ]);

        $user = userModel::findOrFail($walletId);
        $user->balance += $request->recharge;
        $user->save();

        return response()->json([
            'wallet_id' => $user->id,
            'balance' => $user->wallet_balance,
            'wallet_user' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
            ],
        ]);
    }
}
