@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-12">
            <h1 class="text-center mb-5">
                <img class="logo-title" src="{{ asset('/gallib/short-urls/images/short.png') }}" alt="Laravel Short Url">
                <span>Laravel Short Url</span>
            </h1>
            @if (session('short_url'))
                <div class="alert alert-success" role="alert">
                    <span>Your shortened url has been deleted!</span>
                </div>
            @endif
            @if(count($urls) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th scope="col" class="text-center">Base Url</th>
                            <th scope="col" class="text-center">Short Url</th>
                            <th scope="col" class="text-center">Counter</th>
                            <th scope="col" class="text-center">User</th>
                            <th scope="col" class="text-center">
                                <a class="btn btn-sm btn-primary" href="{{ route('short-urls.create') }}">
                                    <i class="bi bi-plus-circle"></i>
                                </a>
                            </th>
                        </tr>
                        @foreach ($urls as $url)
                            <tr>
                                <td>
                                    <a href="{{ $url->url }}" class="btn link-primary btn-outline-light" target="_blank">
                                        {{ $url->url }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('short-urls.redirect', $url->code) }}" class="btn link-primary btn-outline-light" target="_blank">
                                        <span>{{ $url->code }}</span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $url->counter }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ optional($url->user)->name }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-clipboard-text="{{ route('short-urls.redirect', $url->code) }}">
                                        <i class="bi bi-cursor-fill"></i>
                                    </button>
                                    <a class="btn btn-sm btn-primary" href="{{ route('short-urls.edit', $url->id) }}" role="button">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" class="d-inline-block" action="{{ route('short-urls.destroy', $url->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm btn-danger" role="button">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <h5 class="text-center d-flex align-items-center flex-column">
                    <span class="bg-primary text-light text-uppercase rounded p-2 mb-2">You have not any shorten url yet</span>
                    <a class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center p-2" href="{{ route('short-urls.create') }}">
                        <i class="bi bi-plus-circle"></i>
                        <span class="text-uppercase h5 mb-0 ms-1">Create the first shorten url</span>
                    </a>
                </h5>
            @endif
            {{ $urls->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('.btn-success');

        clipboard.on('success', function(e) {
            e.trigger.innerText = 'Copied!';
        });
    </script>
@endpush
