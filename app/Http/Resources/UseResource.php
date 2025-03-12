<?php

namespace App\Http\Resources;

use App\Models\DailySensorStats;
use App\Models\User;
use App\Models\UserPresence;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user=User::findOrFail($this->id);
        $sensors=[];
        $sensors_ids=[];

        foreach ($user->stations as $station) {
            foreach ($station->sensors as $sensor) {
                array_push($sensors,$sensor);
                array_push($sensors_ids, $sensor->id);
            }
        }

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'stations'=>$user->stations,
            'sensors'=>$sensors,
            'last_10_presences'=>UserPresence::where('user_id',$this->id)->limit(10)->get(),
            'statistics'=>DailySensorStats::whereIn("sensor_id",$sensors_ids)->orderBy("created_at","desc")->get()
        ];
    }
}
