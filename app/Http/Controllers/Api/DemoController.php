<?php

namespace App\Http\Controllers\Api;

use App\GeneralSettings;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Log;
use stdClass;

class DemoController extends Controller
{

    public function get(): \Illuminate\Http\JsonResponse
    {
        // Get Current Setting
        $current_data = app(GeneralSettings::class)->current_data;

        // Demo Tube Lines
        $lines_array = [
            'vic' => 'victoria',
            'cen' => 'central',
            'jub' => 'jubilee',
        ];

        // Example Station Stop
        $stop_array = [
            'vic' => '940GZZLUKSX',
            'cen' => '940GZZLUNHG',
            'jub' => '940GZZLULNB',
        ];

        // Set or Get Current Line from Current Setting
        $current_line = 'vic';
        if (!empty($current_data)) {
            if(isset($current_data["current_line"])){
                $current_line = $current_data["current_line"];
            }
        }

        // Full tfl Endpoint
        $url = 'https://api.tfl.gov.uk/Line/'. $lines_array[$current_line] . '/Arrivals';

        // Create Http Client
        $client = new \GuzzleHttp\Client(['headers' => ['Cache-Control' => 'no-cache']]);

        // Fetch data handle Exception
        try {
            $response = $client->request(
                'GET',
                $url,
                ['query' => [
                    'api_key' => 'afa13c985b544f5eabc5a45e5e6b9197'
                ]]
            );

            if ($response->getStatusCode() == 200) {
                //Set New Venues (array)
                $all_arrivals = json_decode($response->getBody());

                if (is_array($all_arrivals) && count($all_arrivals) > 0) {
                    // Get example station Live data
                    $example_stop = self::getOneStop($all_arrivals, $stop_array[$current_line]);

                    if(property_exists($example_stop, 'naptanId')){
                        // Get example station ID
                        $stop_id = $example_stop->naptanId;

                        // Return JsonResponse
                        return response()->json([
                            'Success' => true,
                            'Demo' => $all_arrivals,
                            'Stop' => $example_stop,
                            'inbound' => self::getStopArrivals($stop_id, $all_arrivals, 'inbound'),
                            'outbound' => self::getStopArrivals($stop_id, $all_arrivals, 'outbound'),
                            'img_url' => url('img/' . $current_line . '_big.gif'),
                            'Result_count' => count($all_arrivals)
                        ]);
                    }
                }

                return response()->json(['Success' => false,'Demo' => $all_arrivals]);

            }

            return response()->json(['Success' => false, 'Demo' => $response->getStatusCode()]);


        } catch (ClientException  $e) {
            Log::warning('API DEMO ClientException ' . $e->getMessage());
            return response()->json(['Success' => false, 'Demo' => 'API DEMO ClientException']);
        } catch (GuzzleException $e) {
            Log::warning('API DEMO GuzzleException ' . $e->getMessage());
            return response()->json(['Success' => false, 'Demo' => 'API DEMO GuzzleException']);
        }
    }


    private static function getOneStop($all_arrivals, $stop_id):stdClass
    {
        $stop = new stdClass();
        foreach ($all_arrivals as $arrival){
            if($arrival->naptanId == $stop_id){
                $stop = $arrival;
                break;
            }
        }
        return $stop;
    }

    private static function getStopArrivals($stop_id, $all_arrivals, $direction): array
    {
        $r = [];
        foreach ($all_arrivals as $arrival){
            if($arrival->naptanId == $stop_id && $arrival->direction == $direction){
                $r[] = $arrival;
            }
        }
        return $r;
    }

}
