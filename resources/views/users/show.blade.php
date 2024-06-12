@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <div class="sm:col-span-2 mt-4">
            @include('tasks.tasks')
        </div>
    </div>
@endsection