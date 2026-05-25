<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the FAQ table with default questions.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Do you accept custom orders?',
                'answer' => 'Yes, we accept custom crochet requests depending on complexity and schedule. You can submit a custom order request through our website.',
                'order' => 1,
                'active' => true,
            ],
            [
                'question' => 'How long does production take?',
                'answer' => 'Usually 3–10 days depending on the item complexity. Custom orders may take longer. You\'ll receive an estimated delivery time with your quotation.',
                'order' => 2,
                'active' => true,
            ],
            [
                'question' => 'Do you require full payment upfront?',
                'answer' => 'Yes, we require full payment upfront for custom orders to ensure quality and on-time delivery. For regular products, payment is required at checkout.',
                'order' => 3,
                'active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept GCash and Maya for secure payments. All transactions are processed securely.',
                'order' => 4,
                'active' => true,
            ],
            [
                'question' => 'Do you ship internationally?',
                'answer' => 'Currently, we ship within the Philippines. Contact us for international shipping inquiries.',
                'order' => 5,
                'active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
