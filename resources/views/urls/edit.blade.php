@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-12">
            <h1 class="text-center mb-5">
                <img class="logo-title" src="{{ asset('/gallib/short-urls/images/short.png') }}" alt="Laravel Short Url">
                <span>Laravel Short Url</span>
            </h1>
            @if (session('short_url'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <span>Your shortened url is:</span>
                    <a class="fw-bold btn link-primary" href="{{ session('short_url') }}" target="_blank" title="your shortened url">
                        <span>{{ session('short_url') }}</span>
                    </a>
                    <span>(</span>
                    <a class="copy-clipboard text-decoration-none" href="javascript:void(0);" data-clipboard-text="{{ session('short_url') }}">
                        <span>Copy link to clipboard</span>
                    </a>
                    <span>)</span>
                </div>
            @endif
            <form method="POST" action="{{ route('short-urls.update', $url->id) }}">
                @method('PUT')
                @csrf
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control form-control-lg {{ $errors->has('url') ? 'is-invalid' : '' }}" id="url" name="url" placeholder="Paste an url" aria-label="Paste an url" value="{{ old('url', $url->url) }}">
                    <button class="btn btn-primary btn-lg" type="submit">Shorten</button>
                </div>
                @if ($errors->has('url'))
                    <small id="url-error" class="form-text text-danger">
                        {{ $errors->first('url') }}
                    </small>
                @endif
                <div class="row mt-3">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="code">Custom alias (optional)</label>
                            <input type="text" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" id="code" name="code" placeholder="Set your custom alias" value="{{ old('code', $url->code) }}">
                            @if ($errors->has('code'))
                                <small id="code-error" class="form-text text-danger">
                                    {{ $errors->first('code') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="expires_at">Expires at (optional)</label>
                            <input type="datetime-local" class="form-control {{ $errors->has('expires_at') ? 'is-invalid' : '' }}" id="expires_at" name="expires_at" placeholder="Set your expiration date" value="{{ old('expires_at', ($url->couldExpire() ? $url->expires_at->format('Y-m-d\TH:i') : null)) }}">
                            @if ($errors->has('expires_at'))
                                <small id="code-error" class="form-text text-danger">
                                    {{ $errors->first('expires_at') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        const clipboard = new ClipboardJS('.copy-clipboard');

        clipboard.on('success', function(e) {
            e.trigger.innerText = 'Copied!';
        });
    </script>
@endpush
