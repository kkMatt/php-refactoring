<?php

final class OrderProcessor {
    public function processOrders(array $orders)
    {
        foreach ($orders AS $order)
        {
            if (isset($order['status']) && $order['status'] == 'pending')
            {
                $total = 0;
                if(isset($order['items']))
                {
                    foreach ($order['items'] AS $item)
                    {
                        if(isset($item['price'], $item['quantity']))
                        {
                            $total += $item['price'] * $item['quantity'];
                        }
                    }
                }

                // 10% discount for orders over 100
                $discount = $total > 100 ? $total * 0.1 : 0;

                $finalTotal = $total - $discount;

                // Additional 10% discount from final amount for VIP customers
                if (isset($order['customer_type']) && $order['customer_type'] == 'vip')
                {
                    $finalTotal *= 0.9;
                }

                if(isset($order['customer_email']) && $order['customer_email'] != "")
                {
                    $this->sendEmail($order['customer_email'], "Your order total: $" . $finalTotal);
                } else
                {
                    echo "Error: Customer e-mail is not provided\n";
                }
            }
        }
    }

    private function sendEmail($email, $message)
    {
        // Simulating email sending
        echo "Sending email to $email: $message\n";
    }
}

$orders = [
    [
        'status' => 'pending',
        'customer_email' => 'customer1@example.com',
        'customer_type' => 'vip',
        'items' => [
            ['price' => 50, 'quantity' => 2],
            ['price' => 30, 'quantity' => 1]
        ]
    ],
    [
        'status' => 'completed',
        'customer_email' => 'customer2@example.com',
        'customer_type' => 'regular',
        'items' => [
            ['price' => 20, 'quantity' => 3]
        ]
    ]
];

$processor = new OrderProcessor();
$processor->processOrders($orders);