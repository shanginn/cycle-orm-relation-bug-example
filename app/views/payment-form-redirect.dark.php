<extends:layout.base title="[[Redirecting to payment page...]]"/>
<define:body>
    <h1>[[Redirecting to payment page...]]</h1>
    <form method="POST" action="https://auth.robokassa.ru/Merchant/Index.aspx" id="autoSubmitForm">
        @foreach($params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
        @endforeach
        @if($isTest)
            <input type="hidden" name="IsTest" value="1">
        @endif
        <input type="submit" value="Оплатить" style="display:none;" />
    </form>
    <script type="text/javascript">
        document.getElementById("autoSubmitForm").submit();
    </script>
</define:body>