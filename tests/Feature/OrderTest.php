<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{

    public function test_store_product_happy_scenario()
    {
        // One Product Multi ingredients
        $this->seed();
        // Please Replace email to recieve Email
        $user = User::where('email', 'alothmanasala@gmail.com')->first();
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $data = [
            "user_d" => 1,
            "customer_name" => 'asasa',
            "product_id" => 1
        ];
        $products = [
            [
                "product_id" => "1",
                "product_count" => "7",
                "ingredients" =>
                [
                    [
                        "ingredient_id" => "1",
                        "ingredient_amount" => "150"
                    ],
                    [
                        "ingredient_id" => "2",
                        "ingredient_amount" => "30"
                    ],
                    [
                        "ingredient_id" => "3",
                        "ingredient_amount" => "20"
                    ]
                ],
            ]

        ];
        $response = $this->withSession(['order' => json_encode($products)])->post('/orders', $data);

        $response->assertStatus(200);
    }


    public function test_store_multi_product_scenario()
    {
        // NO ingredient
        $user = User::where('email', 'alothmanasala@gmail.com')->first();
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $data = [
            "user_d" => 1,
            "customer_name" => 'asasa',
            "product_id" => 1
        ];
        $product = [
            [
                "product_id" => "3",
                "product_count" => "10",
                "ingredients" =>     [
                    [
                        "ingredient_id" => "1",
                        "ingredient_amount" => "150"
                    ], [
                        "ingredient_id" => "2",
                        "ingredient_amount" => "30"
                    ], [
                        "ingredient_id" => "3",
                        "ingredient_amount" => "20"
                    ]
                ],
            ],
            [
                "product_id" => "1",
                "product_count" => "7",
                "ingredients" =>
                [
                    [
                        "ingredient_id" => "1",
                        "ingredient_amount" => "150"
                    ],
                    [
                        "ingredient_id" => "2",
                        "ingredient_amount" => "30"
                    ],
                    [
                        "ingredient_id" => "3",
                        "ingredient_amount" => "20"
                    ]
                ],
            ]
        ];
        $response = $this->withSession(['order' => json_encode($product)])->post('/orders', $data);
        $response->assertStatus(200);
    }

}
