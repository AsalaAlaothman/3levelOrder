@extends('layouts.app')

@section('content')
   <div class="row" style="margin: 5%;"><label for="">Create Order:</label>
    <br>

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <x-primary-button id="">Send order</x-primary-button>

    </form>
    @foreach ($products as $product)
        <div class="col-sm-6" style="display: inline-block;width: 30%; margin: 5%;">
            <form method="POST" action="{{ route('orders.addTocart') }}">
                @csrf
                <input type="checkbox" class='form-check' name="product_id" id="{{ $product->slug }}"
                    onclick="addProducts({{ $product->slug }})" value="{{ $product->id }}">
                <x-input-label class="inline-block">{{ $product->name }}</x-input-label>
                <div style="margin-left:3%; display:none" id="{{ $product->slug }}_ingredients">
                    <label for="">choose your {{ $product->name }} count</label>
                    <x-text-input id="" class="inline mt-1" type="number" step='1' min='1'
                        name="product_count" :value="1"  />
                    <br>
                    <label for="">choose your {{ $product->name }} Ingredients</label>
                    <x-input-label class="block">*If You Didn't Enter Amounts </x-input-label>
                    @foreach ($product->ingredients as $ingredient)
                        <div class="mt-4">
                            <input type="checkbox" class='form-check' name="ingredients[name][]"
                                value="{{ $ingredient->id }}" onclick="addIngredients('{{ $ingredient->slug }}','{{$product->slug}}')"
                                id="{{ $ingredient->slug }}_{{$product->slug}}">
                            <x-input-label class="inline-block">{{ $ingredient->name }} : </x-input-label>
                            <x-text-input id="{{ $ingredient->slug }}_amount_{{$product->slug}}" class="inline mt-1" type="number"
                                step='.5' min='1' name="ingredients[amount][]" value="{{ $ingredient->default_amount }}" disabled
                                 />
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 float-end">
                    <x-primary-button disabled id="submit_{{$product->slug}}">Add To Cart</x-primary-button>
                </div>
        </div>
        </form>
    @endforeach
</div>
@endsection
<script>
    function addProducts(slug) {
        slug = slug.id
        if (document.getElementById(slug).checked) {
            document.getElementById(slug + '_ingredients').style.display = 'block';
            document.getElementById('submit_'+slug).removeAttribute('disabled')

        } else {
            document.getElementById(slug + '_ingredients').style.display = 'none';
            document.getElementById('submit_'+slug).setAttribute('disabled', true)

        }
    }

    function addIngredients(ing_slug , prd_slug) {

        if (document.getElementById(ing_slug+"_"+prd_slug).checked) {
            document.getElementById(ing_slug + '_amount_'+prd_slug).removeAttribute('disabled');
        } else {
            document.getElementById(ing_slug + '_amount_'+prd_slug).setAttribute('disabled', true);
        }
    }
</script>
