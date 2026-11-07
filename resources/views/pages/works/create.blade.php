@extends('layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing mt-4">
                @include('pages.works._form', [
                    'formAction' => route('works.store'),
                ])
            </div>
        </div>
    </div>
</div>
@endsection
