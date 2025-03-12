<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UseResource;
use App\Models\TimeSlot;
use App\Models\UserPresence;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(){
        return response(User::all(),200);
    }

    public function show(User $user){
        return response()->json(UseResource::make($user));
    }

    public function createUserPresence(User $user, Request $request){
        $request->validate([
            'day'=>'nullable'
        ]);

        $day=Carbon::now()->format("Y-m-d");
        if($request->has("day")){
            $day=$request->day;
        }

        if(!UserPresence::where('day',$day)->where('user_id',$user->id)->exists()){
            TimeSlot::all()->each(function($ts) use ($day,$user){
                UserPresence::create([
                    "time_slot_id"=>$ts->id,
                    "user_id"=>$user->id,
                    "present"=>1,
                    "day"=>$day
                ]);
            });

            return $this->show($user);
        }else{
            return response()->json([
                'message'=>'la presenza dell utente è già stata inserita per il giorno indicato: '.$day
            ],401);
        }


    }
}
