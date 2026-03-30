<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestimonialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Start with these two
        DB::table('testimonials')->insert([
            'name' => 'John',
            'location' => 'London',
            'quote' => 'Brilliant, thanks!',
            'priority' => 1,
            'date' => '2018-05-30',
        ]);
        DB::table('testimonials')->insert([
            'name' => 'James',
            'location' => 'Hartlepool',
            'quote' => 'Highly recommended, would use again!',
            'priority' => 2,
            'date' => '2018-09-01',
        ]);
        // Add some more
        foreach (range(1, 18) as $i) {
            $type = factory(App\Testimonial::class)->create();
        }
    }
}
