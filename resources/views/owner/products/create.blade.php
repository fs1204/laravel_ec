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
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                         {{-- updateをstoreに変える パラメータは必要ない enctypeもファイルを選ぶわけではないので消しておく--}}
                    <form method="post" action="{{ route('owner.products.store')}}" >
                        @csrf
                        <div class="-m-2">

                            {{-- shops/edit.blade.php からコピー --}}
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名 *必須</label>
                                                                                {{-- バリデーションではじかれたときにoldで持って来れる
                                                                                    "バリデーションエラーとリクエスト入力は自動的にセッションに一時保持保存" --}}
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{-- requiredで必須となる --}}
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">商品情報 *必須</label>
                                    <textarea id="information" name="information" rows="10"  required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('information') }}</textarea>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="price" class="leading-7 text-sm text-gray-600">価格 *必須</label>
                                        {{-- 数値のみの入力が可能 --}}
                                    <input type="number" id="price" name="price" value="{{ old('price') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{-- requiredで必須となる --}}
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">表示順</label>
                                                                                                        {{-- 必須ではないのでrequiredは消しておく --}}
                                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order') }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{-- requiredで必須となる --}}
                                </div>
                            </div>

                            {{-- stockテーブルに保存する --}}
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">初期在庫 ※必須</label>
                                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{-- requiredで必須となる --}}
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                            {{-- 上のクラスをコピー&ペースト --}}
                                    <label for="shop_id" class="leading-7 text-sm text-gray-600">販売する店舗</label>
                                    <select name="shop_id" id="shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

                                        @foreach($shops as $shop)
                                            <option value="{{ $shop->id}}">{{ $shop->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="category" class="leading-7 text-sm text-gray-600">カテゴリー</label>
                                    <select name="category" id="category" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        @foreach($categories as $category)
                                            <optgroup label="{{ $category->name }}">
                                                @foreach($category->secondary as $secondary)
                                                    <option value="{{ $secondary->id}}" >{{ $secondary->name }}</option>
                                                @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <x-select-image name="image1" :images="$images"/>
                            <x-select-image name="image2" :images="$images"/>
                            <x-select-image name="image3" :images="$images"/>
                            <x-select-image name="image4" :images="$images"/>

                            {{-- image1~3を選んだ後にimage4を選ぶと 以前に選んだimage1~3のモーダルが開く
                                最初にimage4で4番目の画像を選ぶと、data-id="image4_4" となる
                                image3を選んだ後に、image4で4番目の画像を選ぶと、data-id="image3_4"となる data-modal="modal-3"
                                暫定対応:
                                image5を作成 (Controller側ではimage4まで保存対象) --}}
                            <x-select-image name="image5" :images="$images"/>


                            <div class="p-2 w-1/2 mx-auto">
                                {{-- flex box を追記 --}}
                                <div class="relative flex justify-around">
                                    {{-- DBの情報でtrueだったら、販売中にしたい。 --}}
                                                                                        {{-- 新規作成の場合、値がないので販売中にcheckedをつけておく --}}
                                    <div><input type="radio" name="is_selling" value="1" class="mr-2" checked>販売中</div>
                                    <div><input type="radio" name="is_selling" value="0" class="mr-2">停止中</div>
                                                                                        {{-- バリデーションで値を残す場合は、oldを使えばOK --}}
                                </div>
                            </div>


                            <div class="p-2 w-full mt-4 flex justify-around">
                                <button type="button" onclick="location.href='{{ route('owner.products.index')}}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        'use strict';
        const images = document.querySelectorAll('.image'); //全てのimageタグを取得
        images.forEach(image => { // 1つずつ繰り返す
            image.addEventListener('click', function(e) { // クリックしたら
                const imageName = e.target.dataset.id.substr(0, 6); //data-idの6文字  image1
                const imageId = e.target.dataset.id.replace(imageName + '_', ''); //6文字カット   image1_
                const imageFile = e.target.dataset.file;
                const imagePath = e.target.dataset.path;
                const modal = e.target.dataset.modal;
                // サムネイルと input type=hiddenのvalueに設定
                document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile;
                document.getElementById(imageName + '_hidden').value = imageId;
                MicroModal.close(modal); //モーダルを閉じる
            });
        });
    </script>
</x-app-layout>
