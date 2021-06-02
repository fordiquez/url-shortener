<?php

namespace App\Http\Responses;

use App\Models\Url;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UrlResponse implements Responsable
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * Create a new instance.
     *
     * @param Url $url
     *
     * @return void
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function toResponse($request)
    {
        $shortUrl = route('short-urls.redirect', ['code' => $this->url->code]);

        if ($request->wantsJson()) {
            return response([
                'id'          => $this->url->id,
                'code'        => $this->url->code,
                'title'       => $this->url->title,
                'description' => $this->url->description,
                'url'         => $this->url->url,
                'short_url'   => $shortUrl,
                'counter'     => $this->url->counter,
                'expires_at'  => $this->url->expires_at,
                'user_id'     => optional($this->url->user)->id,
            ], 201);
        }
        return back()->with('short_url', $shortUrl);
    }
}
