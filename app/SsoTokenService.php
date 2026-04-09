<?php

namespace App;

use App\Models\PortalApplication;
use App\Models\User;
use Illuminate\Support\Str;

class SsoTokenService
{
    public function issueFor(User $user, PortalApplication $application): array
    {
        $issuedAt = now();
        $expiresAt = $issuedAt->copy()->addSeconds((int) config('sso.token_ttl', 120));
        $tokenId = (string) Str::uuid();

        $payload = [
            'iss' => config('sso.issuer'),
            'aud' => $application->sso_audience ?: $application->slug,
            'sub' => (string) $user->getKey(),
            'jti' => $tokenId,
            'iat' => $issuedAt->timestamp,
            'exp' => $expiresAt->timestamp,
            'user' => [
                'id' => $user->getKey(),
                'employee_id' => $user->employee_id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'title' => $user->title,
                'unit_name' => $user->unit_name,
            ],
            'app' => [
                'slug' => $application->slug,
                'name' => $application->name,
            ],
        ];

        return [
            'token' => $this->encode($payload, $application->sso_shared_secret ?: (string) config('sso.shared_secret')),
            'token_id' => $tokenId,
            'expires_at' => $expiresAt,
            'payload' => $payload,
        ];
    }

    public function decode(string $token, ?string $secret = null): array
    {
        [$payload, $signature] = explode('.', $token, 2);

        $expected = $this->sign($payload, $secret ?: (string) config('sso.shared_secret'));

        if (! hash_equals($expected, $signature)) {
            abort(401, 'SSO token signature is invalid.');
        }

        $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true, flags: JSON_THROW_ON_ERROR);

        if (($decoded['exp'] ?? 0) < now()->timestamp) {
            abort(401, 'SSO token has expired.');
        }

        return $decoded;
    }

    private function encode(array $payload, string $secret): string
    {
        $encodedPayload = rtrim(strtr(base64_encode(json_encode($payload, JSON_THROW_ON_ERROR)), '+/', '-_'), '=');

        return $encodedPayload.'.'.$this->sign($encodedPayload, $secret);
    }

    private function sign(string $payload, string $secret): string
    {
        return hash_hmac('sha256', $payload, $secret);
    }
}
