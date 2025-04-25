<extends:layout.base title="[[{{$title ?? 'Something went wrong'}}]]"/>

<define:body>
    <h1 class="main-title">
        <span>[[{{$title ?? 'Something went wrong'}}]]</span>
    </h1>

    <p class="main-description">{{ $message ?? 'Unknown error' }}</p>
</define:body>
