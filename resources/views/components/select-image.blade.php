@php
if($name === 'image1'){ $modal = 'modal-1'; }
if($name === 'image2'){ $modal = 'modal-2'; }
if($name === 'image3'){ $modal = 'modal-3'; }
if($name === 'image4'){ $modal = 'modal-4'; }
if($name === 'image5'){ $modal = 'modal-5'; }   // 暫定対応

$cImage = $currentImage ?? '' ;
$cId = $currentId ?? '' ;

@endphp

                                {{-- ↓ (*) --}}
<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
    {{-- モーダルが手前に表示されるようにzindexを指定する 指定しないと、「販売中 停止中」がモーダルより前に表示される --}}
    <div class="modal__overlay z-50" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
        <header class="modal__header">
          <h2 class="text-xl text-gray-700" id="{{ $modal }}-title">
            ファイルを選択してください
          </h2>
          {{-- submitしないようにtype="button"とする 3箇所ある --}}
          <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="{{ $modal }}-content">
            {{-- resources/views/owner/images/index.blade.php からコピー --}}
            <div class="flex flex-wrap">
                {{-- コントローラーから渡ってくる$imagesをコンポーネントに渡す必要がある。 --}}

                @foreach ($images as $image)
                    <div class="w-1/4 p-2 md:p-4 flex">
                        {{-- <a href="{{ route('owner.images.edit', ['image' => $image->id]) }}"> 不要なので削除--}}
                            <div class="border rounded-md p-2 md:p-4">

                                {{-- <x-thumbnail :filename="$image->filename" type="products"/>
                                    JSで操作できるようにするためにimgに変更 --}}

                                <img class="image"
                                    data-id="{{ $name }}_{{ $image->id }}"  {{-- image1_1 image3_10 など --}}
                                    data-file="{{ $image->filename }}"
                                    data-path="{{ asset('storage/products/') }}"
                                    data-modal="{{ $modal }}"
                                    src="{{ asset('storage/products/' . $image->filename) }}" >
                                            {{-- この部分は、thumbnail.blade.php と同じ --}}

                                {{--
                                <img class="image"
                                    data-id="image1_4"
                                    data-file="sample4.jpg"
                                    data-path="http://localhost/storage/products"
                                    data-modal="modal-1"
                                    src="http://localhost/storage/products/sample4.jpg" >
                                     --}}

                                <div class="text-gray-700">{{ $image->title }}</div>
                                {{-- タイトルはサムネイルの下に貼り付ける --}}
                            </div>
                        {{-- </a> --}}
                    </div>
                @endforeach
            </div>
        </main>
        <div id="image-clear" class="modal__btn" data-micromodal-close>
            ファイル選択をクリア
        </div>
        <footer class="modal__footer">
          <button type="button" class="modal__btn" data-micromodal-close aria-label="閉じる">閉じる</button>
        </footer>
      </div>
    </div>
</div>

{{-- <a data-micromodal-trigger="{{ $modal }}" href='javascript:;'>ファイルを選択</a> ① --}}
{{-- https://gist.github.com/ghosh/4f94cf497d7090359a5c9f81caf60699 から
<a data-micromodal-trigger="modal-1" href='javascript:;'>Open Modal Dialog</a>  ・・・(+)
を引っ張ってくる
modalボランとして使うことができる--}}


{{-- 画像を選んだら、プレジューをしつつ、選んだ画像をコントローラに渡す必要がある
プレビューエリアとinputタグ(hidden) --}}
<div class="flex justify-around items-center mb-4">
    {{-- ボタンっぽくする --}}
    <a class="py-2 px-4 bg-gray-200" data-micromodal-trigger="{{ $modal }}" href='javascript:;'>ファイルを選択</a> {{-- ①より --}}
    <div class="w-1/4">
        <img id="{{ $name }}_thumbnail" class="clear1" @if($cImage) src="{{ asset('storage/products/' . $cImage)}}" @else src="" @endif>
        {{-- <img id="image1_thumbnail" src="http://localhost/storage/products/sample5.jpg"> --}}
        {{-- ↓ 選択を変えると次のように変化する --}}
        {{-- <img id="image1_thumbnail" src="http://localhost/storage/products/sample3.jpg"> --}}
        {{-- ↓ ファイル選択をクリアすると消える--}}
    </div>
</div>

<input id="{{ $name }}_hidden" class="clear2" type="hidden" name="{{ $name }}" value="{{ $cId }}">
{{-- <input id="image1_hidden" type="hidden" name="image1" value="5"> --}}
{{-- ↓ 選択を変えると次のように変化する--}}
{{-- <input id="image1_hidden" type="hidden" name="image1" value="3"> --}}
{{-- ↓ ファイル選択をクリアを押すと次のように変化する--}}
{{-- <input id="image1_hidden" class="clear2" type="hidden" name="image1" value=""> --}}
