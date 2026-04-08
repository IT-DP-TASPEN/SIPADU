<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use App\Models\SsoLaunchLog;
use App\SsoTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SsoLaunchController extends Controller
{
    public function __invoke(Request $request, PortalApplication $application, SsoTokenService $tokenService): RedirectResponse
    {
        abort_unless($application->is_active, 404);
        abort_unless($application->isAccessibleBy($request->user()), 403);

        $targetUrl = $application->url;
        $tokenId = null;
        $expiresAt = null;

        if ($application->usesSso()) {
            $issued = $tokenService->issueFor($request->user(), $application);
            $targetUrl = $this->appendQueryParameters(
                $application->sso_login_url ?: $application->url,
                [
                    'sso_token' => $issued['token'],
                    'issuer' => config('sso.issuer'),
                ],
            );
            $tokenId = $issued['token_id'];
            $expiresAt = $issued['expires_at'];
        }

        SsoLaunchLog::query()->create([
            'user_id' => $request->user()->id,
            'portal_application_id' => $application->id,
            'target_url' => $targetUrl,
            'token_id' => $tokenId,
            'token_expires_at' => $expiresAt,
            'launched_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return redirect()->away($targetUrl);
    }

    private function appendQueryParameters(string $url, array $parameters): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.http_build_query(array_filter($parameters));
    }
}
