@extends('shopify-app::layouts.default')

@section('content')
    <div>
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">

    </script>
@endsection
