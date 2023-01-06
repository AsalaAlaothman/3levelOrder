<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Events\CheckIngriedientLevel;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Ingredient::select(
            "ingredients.id",
            "name",
            "stock_level",
            "in_stock",
            "consumed_stock",
            "default_amount"
        )->get();
        return view('orders.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $session = Session::get('orders');
        $products = Product::with('ingredients')->get();
        return view('orders.create', compact(['products', 'session']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        // Session::forget('orders');
        // Session::save();

        if (!Session::get('order')) {
            return redirect()->back();
        }
        DB::beginTransaction();
        try {

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'customer_name' => Auth::user()->name,
            ]);

            $items = Session::get('order');

            $products = [];
            $ingredients = [];

            foreach (json_decode($items) as $item) {
                $product = [
                    'product_id' => $item->product_id,
                    'product_count' => $item->product_count,
                ];
                if (count($item->ingredients) > 0) {
                    array_push($products, $product);
                    foreach ($item->ingredients as $sub_item) {

                        $ingredient = [
                            'product_id' => $item->product_id,
                            'ingredient_id' => $sub_item->ingredient_id,
                            'ingredient_amount' => $sub_item->ingredient_amount,
                        ];

                        // update ingredient amount

                        $updated_ingredient = Ingredient::select('id', 'name', 'stock_level', 'in_stock', 'consumed_stock')
                            ->findOrFail($sub_item->ingredient_id);
                        $new_ingredient_amount = $updated_ingredient->in_stock - ($item->product_count * $sub_item->ingredient_amount);
                        $new_ingredient_consumed = $updated_ingredient->consumed_stock + ($item->product_count * $sub_item->ingredient_amount);
                        if ($new_ingredient_amount <= 0) {

                            return response()->json([
                                'status' => 403,
                                'message' => 'Please choose less than this mounts for' . $updated_ingredient->name
                            ]);
                        }
                        $updated_ingredient->update([
                            'in_stock' =>  $new_ingredient_amount,
                            'consumed_stock' => $new_ingredient_consumed,
                        ]);

                        Event::dispatch(new CheckIngriedientLevel($updated_ingredient));

                        array_push($ingredients, $ingredient);
                    }
                } else {
                    continue;
                }
            }

            $order->products()->attach($products);
            $order->order_product_ingredients()->attach($ingredients);

            Session::forget('orders');
            Session::save();
            DB::commit();
            return response()->json([
                'order' => $order,
                'message' => 'Your Order Saved'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function addToCart(Request $request)
    {

        $request->validate([
            'product_id' => 'required|integer',
            'product_count' => 'required|integer',
        ]);

        $product = [];
        $ingredients = $request->ingredients;
        $product_ingredients = [];
        if ($ingredients) {
            for ($i = 0; $i < count($ingredients['name']); $i++) {
                $ingredient =  [
                    'ingredient_id' => $ingredients['name'][$i],
                    'ingredient_amount' => $ingredients['amount'][$i]
                ];
                array_push($product_ingredients, $ingredient);
            }
        } else {
            //default value
            $product_ingredient = Product::with('ingredients')->findOrFail($request->product_id);
            foreach ($product_ingredient->ingredients as $item) {
                $ingredient =  [
                    'ingredient_id' => $item->id,
                    'ingredient_amount' => $item->default_amount
                ];
                array_push($product_ingredients, $ingredient);
            }
        }

        $product = [
            'product_id' => $request->product_id,
            'product_count' => $request->product_count,
            'ingredients' => $product_ingredients
        ];

        if (Session::has('orders')) {
         
            Session::push('orders.products', $product);
        } else {
            $product = [
                [
                    'product_id' => $request->product_id,
                    'product_count' => $request->product_count,
                    'ingredients' => $product_ingredients
                ]

            ];

            Session::put('orders.products', ($product));
        }
        Session::put('order', json_encode(Session::get('orders.products')));

        return redirect()->route('orders.create');
    }
}
