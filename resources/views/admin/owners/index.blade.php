<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    エロクエント
                    @foreach ($e_all as $e_owner)
                        {{ $e_owner->name }}
                        {{-- {{ $e_owner->created_at }} エロクエントの場合、Carbonインスタンスになっている --}}
                        {{ $e_owner->created_at->diffForHumans() }}
                    @endforeach
                    {{-- エロクエント test1 1年前 test2 1年前 test3 1年前 --}}
                    <br>
                    クエリビルダ
                    @foreach ($q_get as $q_owner)
                        {{ $q_owner->name }}
                        {{-- {{ $q_owner->created_at->diffForHumans() }}  Call to a member function diffForHumans() on string  --}}
                        {{-- $q_owner->created_at クエリビルダの場合、文字列となっているので、インスタンス化する --}}
                        {{ Carbon\Carbon::parse($q_owner->created_at)->diffForHumans() }}
                    @endforeach
                    {{-- クエリビルダ test1 1年前 test2 1年前 test3 1年前 --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
