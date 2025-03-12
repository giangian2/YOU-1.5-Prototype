<?php

namespace App\Jobs;

use App\Models\Detection;
use App\Models\User;
use App\Models\ShellyEvent;
use App\Models\Station;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractUserStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected TimeSlot $slot;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, TimeSlot $slot)
    {
        $this->user=$user;
        $this->slot=$slot;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now=Carbon::now();
        $startDate=Carbon::create($now->year,$now->month,$now->day,$this->slot->start_hour,0,0);
        $endDate=Carbon::create($now->year,$now->month,$now->day,$this->slot->end_hour,0,0);

        $stations=$this->user->stations;
        foreach($stations as $station){
            $sensors=$station->sensors;
            foreach($sensors as $sensor){
                $ordered_events=ShellyEvent::where('shelly_id',$sensor->key)->whereBetween('created_at',[$startDate,$endDate])->orderByDesc('created_at')->get();
                $first_event=$ordered_events[0];
                $last_event=$ordered_events[count($ordered_events)-1];

                Detection::create([
                    'apower1'=>$first_event->apower,
                    'apower2'=>$last_event->apower,
                    'aenergy1'=>$first_event->aenergy,
                    'aenergy2'=>$last_event->aenergy,
                    'sensor_id'=>$sensor->id,
                    'user_presence_id'=>$this->user->id,
                ]);
            }
        }
    }
}
