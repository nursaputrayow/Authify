<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $this->fromNumber = config('services.twilio.whatsapp_from');
        
        try {
            $this->client = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
        } catch (Exception $e) {
            Log::error('Twilio client initialization failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function sendWhatsAppMessage($to, $message)
    {
        try {
            $to = $this->formatPhoneNumber($to);
            
            $message = $this->client->messages->create(
                "whatsapp:$to",
                [
                    'from' => "whatsapp:" . $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('WhatsApp message sent successfully', [
                'to' => $to,
                'message_sid' => $message->sid
            ]);

            return $message->sid;
        } catch (Exception $e) {
            Log::error('Failed to send WhatsApp message', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . ltrim($phone, '0');
        }
        
        return $phone;
    }
}