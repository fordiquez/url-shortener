<?php

namespace App\Http\Controllers;

use App\Models\HistoryUser;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RedirectController extends Controller
{
    /**
     * Redirect to url by its code.
     *
     * @param string $code
     *
     * @return RedirectResponse
     */
    public function redirect(string $code, Request $request): RedirectResponse
    {
        $url = \Cache::rememberForever("url.$code", function () use ($code) {
            return Url::whereCode($code)->first();
        });

        if ($url == null) {
            abort(404);
        } elseif ($url->hasExpired()) {
            abort(410);
        }
        $url->increment('counter');

        $history_user = new HistoryUser();
        $history_user->user_id = $url->user_id;
        $history_user->url_id = $url->id;
        $history_user->ip_address = $request->ip();
        $history_user->user_agent = $request->userAgent();
        $history_user->saveOrFail();

        return redirect()->away($url->url, $url->couldExpire() ? 302 : 301);
    }
}
