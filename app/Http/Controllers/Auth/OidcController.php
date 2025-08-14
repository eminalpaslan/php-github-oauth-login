<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OidcController extends Controller
{
    private function makeState($len = 32) {
        return bin2hex(openssl_random_pseudo_bytes($len/2));
    }

    // 1) Login başlatma
    public function redirect(Request $request)
    {
        $state = $this->makeState();
        $request->session()->put('oidc_state', $state);

        $params = [
            'response_type' => 'code',
            'client_id'     => config('services.oidc.client_id'),
            'redirect_uri'  => config('services.oidc.redirect_uri'),
            'scope'         => config('services.oidc.scope'),
            'state'         => $state,
        ];

        $authUrl = config('services.oidc.auth_url') . '?' . http_build_query($params);
        return redirect()->away($authUrl);
    }

    // 2) Callback
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return response('OAuth hata: ' . $request->get('error'), 400);
        }

        // state kontrol
        $savedState = $request->session()->pull('oidc_state');
        if ($savedState !== $request->get('state')) {
            return response('State uyuşmuyor', 400);
        }

        // code ile token al (client_secret_post yöntemi)
        $payload = [
            'grant_type'    => 'authorization_code',
            'code'          => $request->get('code'),
            'redirect_uri'  => config('services.oidc.redirect_uri'),
            'client_id'     => config('services.oidc.client_id'),
            'client_secret' => config('services.oidc.client_secret'),
        ];

        $token = $this->postForm(
            config('services.oidc.token_url'),
            $payload
        );

        if ($token['status'] !== 200) {
            return response('Token alınamadı: ' . $token['body'], 400);
        }

        $tokenData = json_decode($token['body'], true);

        // userinfo iste
        $user = null;
        if (config('services.oidc.userinfo_url')) {
            $userResp = $this->getJson(
                config('services.oidc.userinfo_url'),
                ['Authorization: Bearer ' . $tokenData['access_token']]
            );
            if ($userResp['status'] === 200) {
                $user = json_decode($userResp['body'], true);
            }
        }

        // session başlat
        $request->session()->put('auth', $tokenData);
        $request->session()->put('user', $user);

        return redirect('/hosgeldin');
    }

    // 3) Logout
    public function logout(Request $request)
    {
        $idToken = $request->session()->get('auth.id_token');
        $request->session()->flush();

        if ($idToken && config('services.oidc.end_session_url')) {
            return redirect()->away(config('services.oidc.end_session_url') . '?' . http_build_query([
                'id_token_hint' => $idToken,
                'post_logout_redirect_uri' => url('/')
            ]));
        }

        return redirect('/');
    }

    // ==== yardımcı cURL fonksiyonları ====

    private function postForm($url, array $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($body === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['status' => 0, 'body' => $err];
        }
        curl_close($ch);
        return ['status' => (int)$status, 'body' => $body];
    }

    private function getJson($url, array $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array_merge(['Accept: application/json'], $headers),
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($body === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['status' => 0, 'body' => $err];
        }
        curl_close($ch);
        return ['status' => (int)$status, 'body' => $body];
    }
}
