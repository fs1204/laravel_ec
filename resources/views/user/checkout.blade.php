<p>決済ページへリダイレクトします。</p>
{{-- 一瞬だけページが表示される --}}
{{-- <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script> --}}
<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const publicKey = '{{ $publicKey }}';
    const stripe = Stripe(publicKey);

    // 画面を読み込んだ瞬間に実行する。
    window.onload = function() {
        stripe.redirectToCheckout({ // チェックアウトページに飛ばす
            sessionId: '{{ $session->id }}';    // session->id は \Stripe\Checkout\Session::create で作った id 作った檀家でidも振られている。
        }).then(function (result) { // もしエラーが発生した場合は、.thenでroute('user.cart.index')に飛ばす
            window.location.href = '{{ route('user.cart.index') }}';    // 指定された URL に移動する
        });
    }
</script>
