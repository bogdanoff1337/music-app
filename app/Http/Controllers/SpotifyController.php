<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    public function redirectToSpotify()
    {
        $clientId = config('services.spotify.client_id');
        $redirectUri = config('services.spotify.redirect_uri');
        $scopes = 'user-read-private user-read-email'; 

        $url = "https://accounts.spotify.com/authorize" .
               "?client_id=$clientId" .
               "&response_type=code" .
               "&redirect_uri=$redirectUri" .
               "&scope=$scopes";

        return redirect($url);
    }

    public function getUserData($accessToken)
    {
        // Зробити запит до Spotify API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.spotify.com/v1/me');

        // Отримати інформацію про користувача з відповіді API
        $user = $response->json();

        $userName = $user['display_name'];

        return $user;
    }

    public function storeAccessToken($accessToken, $user): void
    {
        // Зберегти токен доступу в сесійній пам'яті
        session()->put('accessToken', $accessToken);

        // Зберегти інформацію про користувача в сесійній пам'яті
        session()->put('user', $user);
    }



    public function handleCallback(Request $request)
    {
        // Отримайте код з Spotify
        $code = $request->input('code');

        // Обміняйте код на токен доступу
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.spotify.redirect_uri'),
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        // Отримайте токен доступу
        $accessToken = $response['access_token'];

        // Отримайте інформацію про користувача
        $user = $this->getUserData($accessToken);

        // Збережіть токен доступу
        $this->storeAccessToken($accessToken, $user);

        session()->put('user_name', $user['display_name']);
        // Редірект на головну сторінку
        return redirect('/');
    }

}
