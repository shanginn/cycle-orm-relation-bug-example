<extends:layout.base title="[[Payment page]]"/>
<stack:push name="styles">
    <style>
        .payment-button {
            width: 100%;
            height: 90vh;
            font-size: 40px;
        }
    </style>
</stack:push>
<define:body>
    <script type="text/javascript" src="https://telegram.org/js/telegram-web-app.js"></script>
    <script type="text/javascript" src="https://auth.robokassa.ru/Merchant/bundle/robokassa_iframe.js"></script>
    <script type="text/javascript">
        function startPayment() {
            Robokassa.StartPayment({
                @foreach($params as $key => $value)
                    {!! $key !!}: '{!! $value !!}',
                @endforeach
                @if($isTest)
                    IsTest: 1,
                @endif
            });
        }

        console.log(window.Telegram.WebApp)
        startPayment();
    </script>

    <button class="payment-button" onclick="startPayment()">
        [[reopen payment iframe]]
    </button>
</define:body>