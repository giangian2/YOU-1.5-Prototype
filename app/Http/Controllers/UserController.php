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

    public function setUserPresence(User $user, Request $request){
        $request->validate([
            'day'=>'date|nullable'
        ]);

        $day=Carbon::now()->format("Y-m-d");
        if($request->has("day")){
            $day=$request->day;
        }

        TimeSlot::all()->each(function($ts) use ($day,$user){
            UserPresence::create([
                "time_slot_id"=>$ts->id,
                "user_id"=>$user->id,
                "present"=>1,
                "day"=>$day
            ]);
        });

        return $this->show($user);
    }
}
