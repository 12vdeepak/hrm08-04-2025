<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GmailController extends Controller
{
    protected function buildGoogleClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);
        $client->setScopes([
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_METADATA,
        ]);

        $tokenPath = $this->getUserTokenPath();
        if (Storage::exists($tokenPath)) {
            $accessToken = json_decode(Storage::get($tokenPath), true);
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    Storage::put($tokenPath, json_encode($client->getAccessToken()));
                }
            }
        }

        return $client;
    }

    protected function getUserTokenPath(): string
    {
        $userId = Auth::id() ?: 'guest';
        return "gmail_tokens/token_{$userId}.json";
    }

    public function connect(Request $request)
    {
        $client = $this->buildGoogleClient();
        // If we already have a valid token, go to inbox
        if ($client->getAccessToken() && !$client->isAccessTokenExpired()) {
            return redirect()->route('gmail.inbox');
        }

        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $client = $this->buildGoogleClient();
        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('gmail.connect')->with('error', 'Authorization code missing');
        }

        $token = $client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) {
            return redirect()->route('gmail.connect')->with('error', $token['error_description'] ?? 'Failed to get token');
        }

        Storage::put($this->getUserTokenPath(), json_encode($client->getAccessToken()));

        return redirect()->route('gmail.inbox');
    }

    public function inbox(Request $request)
    {
        $client = $this->buildGoogleClient();
        if (!$client->getAccessToken()) {
            return redirect()->route('gmail.connect');
        }

        $service = new Google_Service_Gmail($client);

        $messages = [];
        $list = $service->users_messages->listUsersMessages('me', [
            'maxResults' => 10,
            'q' => $request->get('q', ''),
        ]);

        foreach ($list->getMessages() ?? [] as $messageRef) {
            $full = $service->users_messages->get('me', $messageRef->getId(), ['format' => 'metadata', 'metadataHeaders' => ['Subject', 'From', 'Date']]);
            $headers = collect($full->getPayload()->getHeaders() ?? [])->keyBy(fn($h) => $h->getName());
            $messages[] = [
                'id' => $full->getId(),
                'snippet' => $full->getSnippet(),
                'subject' => optional($headers->get('Subject'))->getValue(),
                'from' => optional($headers->get('From'))->getValue(),
                'date' => optional($headers->get('Date'))->getValue(),
            ];
        }

        return view('gmail.inbox', [
            'messages' => $messages,
        ]);
    }
}


