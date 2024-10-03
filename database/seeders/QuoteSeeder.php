<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Quote;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            ['body' => "The greatest glory in living lies not in never falling, but in rising every time we fall.", 'person' => "Nelson Mandela"],
            ['body' => "The journey of a thousand miles begins with one step.", 'person' => "Lao Tzu"],
            ['body' => "What lies behind us and what lies before us are tiny matters compared to what lies within us.", 'person' => "Ralph Waldo Emerson"],
            ['body' => "You are not your mistakes. They are what you did, not who you are.", 'person' => "Lisa L. O'Neill"],
            ['body' => "Strength does not come from physical capacity. It comes from an indomitable will.", 'person' => "Mahatma Gandhi"],
            ['body' => "It does not matter how slowly you go as long as you do not stop.", 'person' => "Confucius"],
            ['body' => "The only limit to our realization of tomorrow will be our doubts of today.", 'person' => "Franklin D. Roosevelt"],
            ['body' => "In the middle of difficulty lies opportunity.", 'person' => "Albert Einstein"],
            ['body' => "It’s not whether you get knocked down, it’s whether you get up.", 'person' => "Vince Lombardi"],
            ['body' => "Your present circumstances don’t determine where you can go; they merely determine where you start.", 'person' => "Nido Qubein"],
            ['body' => "Success is not final, failure is not fatal: It is the courage to continue that counts.", 'person' => "Winston S. Churchill"],
            ['body' => "We are what we repeatedly do. Excellence, then, is not an act, but a habit.", 'person' => "Aristotle"],
            ['body' => "The best way out is always through.", 'person' => "Robert Frost"],
            ['body' => "Our greatest glory is not in never falling, but in rising every time we fall.", 'person' => "Confucius"],
            ['body' => "You can’t go back and change the beginning, but you can start where you are and change the ending.", 'person' => "C.S. Lewis"],
            ['body' => "The most difficult thing is the decision to act, the rest is merely tenacity.", 'person' => "Amelia Earhart"],
            ['body' => "We may encounter many defeats but we must not be defeated.", 'person' => "Maya Angelou"],
            ['body' => "Your life does not get better by chance, it gets better by change.", 'person' => "Jim Rohn"],
            ['body' => "The only person you are destined to become is the person you decide to be.", 'person' => "Ralph Waldo Emerson"],
            ['body' => "Courage doesn’t always roar. Sometimes courage is the quiet voice at the end of the day saying, 'I will try again tomorrow.'", 'person' => "Mary Anne Radmacher"],
            ['body' => "We cannot direct the wind, but we can adjust the sails.", 'person' => "Dolly Parton"],
            ['body' => "Believe you can and you're halfway there.", 'person' => "Theodore Roosevelt"],
            ['body' => "The secret of change is to focus all your energy not on fighting the old, but on building the new.", 'person' => "Socrates"],
            ['body' => "Hope is being able to see that there is light despite all of the darkness.", 'person' => "Desmond Tutu"],
            ['body' => "Change your thoughts and you change your world.", 'person' => "Norman Vincent Peale"],
            ['body' => "It’s not the load that breaks you down, it’s the way you carry it.", 'person' => "Lou Holtz"],
            ['body' => "Nothing is impossible, the word itself says 'I’m possible'!", 'person' => "Audrey Hepburn"],
            ['body' => "A journey of a thousand miles must begin with a single step.", 'person' => "Lao Tzu"],
            ['body' => "Life is not about waiting for the storm to pass but learning to dance in the rain.", 'person' => "Vivian Greene"],
            ['body' => "Life is 10% what happens to us and 90% how we react to it.", 'person' => "Charles R. Swindoll"],
            ['body' => "The only way to do great work is to love what you do.", 'person' => "Steve Jobs"],
            ['body' => "It always seems impossible until it’s done.", 'person' => "Nelson Mandela"],
            ['body' => "Sometimes the bravest and most important thing you can do is just show up.", 'person' => "Brene Brown"],
            ['body' => "The best way to predict the future is to create it.", 'person' => "Peter Drucker"],
            ['body' => "The greatest discovery of my generation is that a human being can alter his life by altering his attitude.", 'person' => "William James"],
            ['body' => "You have within you right now, everything you need to deal with whatever the world can throw at you.", 'person' => "Brian Tracy"],
        ];

        Quote::insert($quotes);
    }
}
