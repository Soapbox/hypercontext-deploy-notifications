<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Slack\Factory;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Validation\ValidationException;

class Notify
{
    public function notify(GuzzleClient $client)
    {
        $message = request()->all();

        try {
            $message = Factory::makeMessage($message)->toArray();
        } catch (ValidationException $e) {
            Log::debug($message);
            Log::debug($e->validator->errors());
        } catch (Exception $e) {
            Log::debug($message);
            Log::debug($e);
        } catch (Throwable $e) {
            Log::debug($message);
            Log::debug($e);
        }
        
        $client->post(env('SLACK_WEBHOOK'), [
            'json' => $message,
        ]);
    }
}
