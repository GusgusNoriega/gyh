@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white overflow-hidden shadow rounded-lg">
  <div class="p-6">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="ml-5 w-0 flex-1">
        <dl>
          <dt class="text-sm font-medium text-gray-500 truncate">
            Bienvenido al Dashboard
          </dt>
          <dd class="text-lg font-medium text-gray-900">
            {{ Auth::user()->name }}
          </dd>
        </dl>
      </div>
    </div>
  </div>
  <div class="bg-gray-50 px-6 py-4">
    <div class="text-sm">
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="font-medium text-indigo-600 hover:text-indigo-500">
        Cerrar Sesión
      </a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>
</div>
@endsection