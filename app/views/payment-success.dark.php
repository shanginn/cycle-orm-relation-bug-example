<extends:layout.base title="[[Payment successful]]"/>

<stack:push name="styles">
    <link rel="stylesheet" href="/styles/welcome.css"/>
</stack:push>

<define:body>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <h1 class="main-title">[[Payment successful]]</h1>
    <p>
        [[Payment successful text]]
    </p>

    <script type="text/javascript">
        setTimeout(function() {
            window.Telegram.WebApp.close();
        }, 2000);
    </script>
</define:body>
