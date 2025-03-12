<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DailySensorStats;
use Carbon\Carbon;
use App\Models\Detection;
use App\Models\Sensor;
use App\Models\TimeSlot;
use App\Models\UserPresence;
use App\Models\Station;

class CreateDailySensorStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-daily-sensor-stats {--sensorId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sensorId=$this->option('sensorId');

        if(!Sensor::where('id',$sensorId)->exists()){
            $this->error('Il sensore non esiste!');
            die();
        }

        /**
         * 1- Cercare le detection fatte nella giornata di oggi da quel determinato sensore
         * 2- Prendere ciascuna detection con la relativa user_presence
         * 3- Associare la user_presence al time slot
         * 4- COnsiderare in maniera separata ciascuna time slot
         *
         */
        $consumption=0;
        $spreco=0;
        $risparmio=0;
        $eccesso=0;
        $apowerExit=0;
        $consumo_progressivo_giornaliero=0;
        $eccesso_giornaliero=0;
        $firstAenergy=0;
        $lastAenergy=0;

        $detections=Detection::whereDate('created_at','=',Carbon::today()->toDateString())->where('sensor_id','=',$sensorId)->orderBy('created_at','asc')->get();
        foreach($detections as $detection){
            $user_presence=$detection->userPresence;
            $time_slot=$user_presence->timeSlot;
            switch($time_slot->id){

                /** 9 - 13 */
                case 2:
                    $diffApower=$detection->apower2-$detection->apower1;
                    $diffAenergy=$detection->aenergy2-$detection->aenergy1;

                    $consumption+=$diffAenergy;

                    break;

                /** 13 - 14 */
                case 5:
                    if($detection->apower1>0){
                        $diffAenergy=$detection->aenergy2-$detection->aenergy1;
                        $consumption+=$diffAenergy;
                        $spreco+=$diffAenergy;
                    }else{
                        $nOreMattina=4;
                        $nOrePausaPranzo=1;

                        $risparmio+=($consumption/$nOreMattina)*$nOrePausaPranzo;
                    }

                    break;

                /** 14 - 18 */
                case 3:
                    $diffApower=$detection->apower2-$detection->apower1;
                    $diffAenergy=$detection->aenergy2-$detection->aenergy1;
                    $apowerExit=$detection->apower2;
                    $consumption+=$diffAenergy;

                    break;

                /** 18 - 24 */
                case 4:
                    $coefficente=0.5/15;
                    $lastAenergy=$detection->aenergy2;
                    /**se apower è maggiore di 0 faccio coeff*apower sll'uscita di sera*ore */
                    if ($detection->apower1 > 0) {
                        $eccesso += $coefficente * $apowerExit * 15;
                        $this->info("eccesso: ".$eccesso." coefficente: ".$coefficente." power exit: ".$apowerExit);
                        $diffAenergy = $detection->aenergy2 - $detection->aenergy1;
                        $consumo_progressivo_giornaliero += $detection->aenergy2;
                        $eccesso_giornaliero += $diffAenergy;
                    }

                    break;

                /** 0 - 9 */
                case 6:
                    $coefficente=0.5/15;
                    $firstAenergy=$detection->aenergy1;
                    /**se apower è maggiore di 0 faccio coeff*apower sll'uscita di sera*ore */
                    if($detection->apower1>0){
                        $diffAenergy=$detection->aenergy2-$detection->aenergy1;
                        $consumo_progressivo_giornaliero+=$detection->aenergy1;
                        $eccesso_giornaliero+=$diffAenergy;
                    }

                    break;
            }
        }

        DailySensorStats::create([
            'sensor_id' => $sensorId,
            'consumo_progressivo_giornaliero' => $consumo_progressivo_giornaliero,
            'spreco_giornaliero' => $spreco,
            'eccesso_giornaliero' => $eccesso_giornaliero,
            'risparmio_giornaliero_da_spreco' => $risparmio,
            'risparmio_giornaliero_da_eccesso' => $eccesso,
            'consumo_giornaliero'=> $lastAenergy-$firstAenergy,
            'day_' => date('Y-m-d')
        ]);
    }
}
