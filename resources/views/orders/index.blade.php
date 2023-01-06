@extends('layouts.app')

@section('content')
    <div class="card-body px-0 pb-2">
        <a href="{{ route('orders.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Create
            Order</a>
        <div class="table-responsive p-0">
            <table class="table align-items-center" id="tblStocks">
                {{-- table head --}}
                <thead>
                    <tr>
                        <th scope="col" class="text-center">
                            Ingredient Name
                        </th>
                        <th scope="col" class="text-center">
                            Stock Level
                        </th>
                        <th scope="col" class="text-center">
                            In Stock
                        </th>
                        <th scope="col" class="text-center">
                            Consumed Stock
                        </th>
                        <th scope="col" class="text-center">
                            Default Amount
                        </th>
                    </tr>
                </thead>

                {{-- table body --}}
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="text-center">
                                {{ $item->name }}
                            </td>
                            <td class="text-center">
                                {{ $item->stock_level }}
                            </td>
                            <td class="text-center">
                                {{ $item->in_stock }}
                            </td>
                            <td class="text-center">
                                {{ $item->consumed_stock }}
                            </td>
                            <td class="text-center">
                                {{ $item->default_amount }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{-- {!! $items->links() !!} --}}
        </div> {{-- table-responsive p-0 --}}
    </div>{{-- col-11 --}}
@endsection
