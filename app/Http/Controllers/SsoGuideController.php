<?php

namespace App\Http\Controllers;

use App\Models\PortalApplication;
use Illuminate\View\View;

class SsoGuideController extends Controller
{
    public function concise(): View
    {
        return view('docs.sso-ringkas', [
            'applications' => PortalApplication::query()
                ->where('launch_mode', 'sso')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
