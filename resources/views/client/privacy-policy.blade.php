@extends('layout.main')

@section("main")
<div class="container py-5">
    <h1 class="mb-4">{{ __('messages.privacy_title') }}</h1>

    <p>{{ __('messages.intro') }}</p>

    <h2 class="mt-4">{{ __('messages.info_collected_title') }}</h2>
    <ul>
        @foreach(__('messages.info_collected') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <h2 class="mt-4">{{ __('messages.cookie_usage_title') }}</h2>
    <p>{{ __('messages.cookie_usage_intro', [], false) ?? __('messages.cookie_usage_title') }}</p>
    <ul>
        @foreach(__('messages.cookie_usage') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
    <p>{{ __('messages.cookie_note') }}</p>

    <h2 class="mt-4">{{ __('messages.purpose_title') }}</h2>
    <p>{{ __('messages.purpose_intro', [], false) ?? '' }}</p>
    <ul>
        @foreach(__('messages.purpose_list') as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <h2 class="mt-4">{{ __('messages.security_title') }}</h2>
    <p>{{ __('messages.security_text') }}</p>

    <h2 class="mt-4">{{ __('messages.sharing_title') }}</h2>
    <p>{{ __('messages.sharing_text') }}</p>

    <h2 class="mt-4">{{ __('messages.rights_title') }}</h2>
    <p>{{ __('messages.rights_text') }}</p>

    <h2 class="mt-4">{{ __('messages.changes_title') }}</h2>
    <p>{{ __('messages.changes_text') }}</p>

    <p class="mt-5">{!! __('messages.contact', ['email' => '<strong>support@rivernew.vn</strong>']) !!}</p>
</div>
@endsection
