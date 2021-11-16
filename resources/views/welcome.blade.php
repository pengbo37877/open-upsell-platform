@extends('shopify-app::layouts.default')

@section('content')
    <!-- You are: (shop domain name) -->
    {{-- <p>You are: {{ $shopDomain ?? Auth::user()->name }}</p> --}}
    <p><a href="{{ route('billing', ['plan' => 1, 'shop' => Auth::user()->name]) }}">Upgrade 1</a></p>
    <p><a href="{{ route('billing', ['plan' => 2, 'shop' => Auth::user()->name]) }}">Upgrade 2</a></p>
@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>
@endsection
