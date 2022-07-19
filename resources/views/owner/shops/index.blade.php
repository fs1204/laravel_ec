{{-- dashboardの内容をコピーする --}}

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
                    {{-- ログインに成功したら、$shopsという変数が渡ってくるので、foreach で書くことにする --}}
                    <x-flash-message status="{{ session('status') }}" />
                    {{-- // フラッシュメッセージを表示するタグを追加する必要がある
                    // resources/views/admin/owners/index.blade.php から 以下をコピー
                    // <x-flash-message status="{{ session('status') }}" /> --}}

                    @foreach ($shops as $shop)
                        <div class="w-1/2 p-4">
                            <a href="{{ route('owner.shops.edit', ['shop' => $shop->id]) }}">
                                <div class="borde rounded-md p-4">
                                    <div class="mb-4">
                                        @if($shop->is_selling)
                                            <span class="border p-2 rounded-md bg-blue-400 text-white">販売中</span>
                                        @else
                                            <span class="border p-2 rounded-md bg-red-400 text-white">停止中</span>
                                        @endif
                                    </div>

                                    {{-- このコードを他でも使うので、コンポネント化する。 --}}
                                    <div class="text-xl">{{ $shop->name }}</div>
                                    {{-- <x-shop-thumbnail :filename="$shop->filename" /> コンポーネントの名前を変えたので、こちらも変える必要がある。--}}
                                    <x-thumbnail :filename="$shop->filename" type="shops" />
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
