<?php

namespace App\Console\Commands;

use App\Models\Detection;
use App\Models\User;
use App\Models\ShellyEvent;
use App\Models\Station;
use App\Models\TimeSlot;
use App\Models\UserPresence;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateDetection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-detection {user} {slot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected User $user;
    protected TimeSlot $slot;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user=$this->argument('user');
        $slot=$this->argument('slot');
        $this->user=User::findOrFail($user);
        $this->slot=TimeSlot::findOrFail($slot);
        $now=Carbon::now();
        $startDate=Carbon::create($now->year,$now->month,$now->day,$this->slot->start_hour,0,0);
        $endDate=Carbon::create($now->year,$now->month,$now->day,$this->slot->end_hour,0,0);

        $stations=$this->user->stations;
        foreach($stations as $station){
            $sensors=$station->sensors;
            foreach($sensors as $sensor){
                $this->info("Sensor Key: ".$sensor->key.", Start Date: ".$startDate);
                $ordered_events=ShellyEvent::where('shelly_id',$sensor->key)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->orderByDesc('created_at')->get();
                $last_event=$ordered_events[0];
                $first_event = $ordered_events[count($ordered_events) - 1];

                if (UserPresence::where('user_id', '=', $this->user->id)->where('day', '=', date('Y-m-d'))->where('time_slot_id', '=', $this->slot->id)->exists()) {
                    Detection::create([
                        'apower1' => $first_event->apower,
                        'apower2' => $last_event->apower,
                        'aenergy1' => $first_event->aenergy,
                        'aenergy2' => $last_event->aenergy,
                        'sensor_id' => $sensor->id,
                        'user_presence_id' => UserPresence::where('user_id', '=', $this->user->id)
                                                        ->where('day', '=', date('Y-m-d'))
                                                        ->where('time_slot_id', '=', $this->slot->id)
                                                        ->first()
                                                        ->id,
                    ]);
                }
                ShellyEvent::where('shelly_id',$sensor->key)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->delete();
            }
        }
    }
}
