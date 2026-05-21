<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Absolutely beautiful craftsmanship. Highly recommended!',
            ],
            [
                'rating' => 5,
                'comment' => 'The quality exceeded my expectations. Will order again!',
            ],
            [
                'rating' => 4,
                'comment' => 'Very nice handmade product, delivery was fast.',
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect gift! My friend loved it so much.',
            ],
            [
                'rating' => 4,
                'comment' => 'Great product but packaging could be improved.',
            ],
            [
                'rating' => 5,
                'comment' => 'Amazing detail and very well made!',
            ],
            [
                'rating' => 3,
                'comment' => 'Good overall, but took a bit longer than expected.',
            ],
            [
                'rating' => 5,
                'comment' => 'I love it! Will definitely buy again.',
            ],
            [
                'rating' => 4,
                'comment' => 'Really nice item, very satisfied with purchase.',
            ],
            [
                'rating' => 5,
                'comment' => 'Exceeded expectations in every way.',
            ],
            [
                'rating' => 5,
                'comment' => 'Beautiful work, you can really see the effort put into it.',
            ],
            [
                'rating' => 4,
                'comment' => 'Solid quality and nice design.',
            ],
            [
                'rating' => 5,
                'comment' => 'One of the best handmade items I’ve bought online.',
            ],
            [
                'rating' => 3,
                'comment' => 'It’s okay, but I expected a bit more detail.',
            ],
            [
                'rating' => 5,
                'comment' => 'Looks even better in person!',
            ],
        ];

        $userIds = [1, 2, 6];

        foreach ($reviews as $review) {
            Review::create([
                'user_id' => $userIds[array_rand($userIds)],
                'rating' => $review['rating'],
                'comment' => $review['comment'],
                'is_anonymous' => fake()->boolean(),
            ]);
        }
    }
}
