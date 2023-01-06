<x-mail::message>
    Please ReFill {{ $ingredient_name }}

<x-mail::button :url="'http://127.0.0.1:8000/orders'">
GO TO SUPPLY
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
