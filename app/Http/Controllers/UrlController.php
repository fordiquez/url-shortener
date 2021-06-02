<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Http\Responses\UrlResponse;
use App\Models\Url;
use App\Rules\Hasher;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $urls = Url::orderBy('created_at', 'desc')->paginate(config('shorturl.items_per_page'));
        } else {
            $urls = Url::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(config('shorturl.items_per_page'));
        }

        return view('urls.index', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('urls.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UrlRequest $request
     *
     * @return UrlResponse
     */
    public function store(UrlRequest $request): UrlResponse
    {
        $data = [
            'url' => $request->get('url'),
            'code' => $request->get('code') ? \Str::slug($request->get('code')) : (new Hasher)->generate(),
            'user_id' => optional(auth()->user())->id,
        ];

        if ($request->filled('expires_at')) {
            $data['expires_at'] = Carbon::parse($request->get('expires_at'))->toDateTimeString();
        } else {
            $expires_at_timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') + 3, date('y'));
            $expires_at_date = date('Y-m-d H:i:s', $expires_at_timestamp);
            $data['expires_at'] = Carbon::parse($expires_at_date)->toDateTimeString();
        }

        $url = Url::create($data);

        return new UrlResponse($url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(int $id)
    {
        $url = Url::findOrFail($id);

        if (Auth::user()->id != $url->user_id) {
            return back()->withMessage('Access denied');
        }
        return view('urls.edit', compact('url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UrlRequest $request
     * @param int $id
     * @return UrlResponse
     */
    public function update(UrlRequest $request, int $id): UrlResponse
    {
        $url = Url::findOrFail($id);

        if (Auth::user()->id != $url->user_id) {
            return back()->withMessage('Access denied');
        }

        \Cache::forget("url.{$url['code']}");

        $data = [
            'url' => $request->get('url'),
            'code' => $request->get('code'),
            'user_id' => optional(auth()->user())->id,
        ];

        if ($request->filled('expires_at')) {
            $data['expires_at'] = Carbon::parse($request->get('expires_at'))->toDateTimeString();
        }

        $url->update($data);

        return new UrlResponse($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function destroy(int $id)
    {
        $url = Url::findOrFail($id);

        if (Auth::user()->id != $url->user_id) {
            return back()->withMessage('Access denied');
        }

        \Cache::forget("url.{$url['code']}");

        $url->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return back()->with('short_url', true);
    }
}
