<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\ShellyEvent;

class mqtt_intercept extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mqtt_intercept';

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
        $server='35.171.153.160';
        $username='testuser';
        $password='Testuser,1234';
        $port=1883;
        $clientId='test-subscriber';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setConnectTimeout(3)
            ->setMaxReconnectAttempts(3)
            ->setKeepAliveInterval(10)
            ->setUsername($username)
            ->setPassword($password);

        $mqtt->connect($connectionSettings, true);

        $mqtt->subscribe('Renovit/MI-Mali/Piano-04/Uff-01/#', function ($topic, $message, $retained, $matchedWildcards) {

            $json = json_decode($message, true);
            if(isset($json["params"])){
                if(isset($json["params"]["switch:0"])){
                    ShellyEvent::create([
                        'shelly_id'=> $json["src"],
                        'apower'=> $json["params"]["switch:0"]["apower"],
                        'aenergy'=> $json["params"]["switch:0"]["aenergy"]["total"],
                        'topic'=> $topic
                    ]);
                    //Log::info( sprintf("Received message on topic [%s]: %s\n", $topic, $message));
                }
            }

        }, 0);

        $mqtt->loop(true);
        $mqtt->disconnect();

    }
}
