<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChatbotFaq;
class ChatbotFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        ChatbotFaq::insert([
            [
                'question' => 'How do I track my order?',
                'answer' => 'You can track your order from the Track Order page inside your account.',
                'keywords' => 'track,order,shipping,status',
                'is_active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We currently support PayMongo payments including GCash, cards, and e-wallets.',
                'keywords' => 'payment,paymongo,gcash,card',
                'is_active' => true,
            ],
            [
                'question' => 'How do custom orders work?',
                'answer' => 'You can submit a custom order request, and our team will review and provide a quotation.',
                'keywords' => 'custom,commission,request,design',
                'is_active' => true,
            ],
            [
                'question' => 'How long is shipping?',
                'answer' => 'Shipping usually takes 3–7 business days depending on your location.',
                'keywords' => 'shipping,delivery,time',
                'is_active' => true,
            ],
        ]);
    }
}
