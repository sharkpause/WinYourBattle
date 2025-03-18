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
            ['body' => 'It does not matter how slowly you go as long as you do not stop.', 'person' => 'Confucius'],
            ['body' => 'Believe you can, and you\'re halfway there.', 'person' => 'Theodore Roosevelt'],
            ['body' => 'I avoid looking forward or backward, and try to keep looking upward.', 'person' => 'Charlotte Brontë'],
            ['body' => 'I hated every minute of training, but I said, \'Don\'t quit. Suffer now and live the rest of your life as a champion.\'', 'person' => 'Muhammad Ali'],
            ['body' => 'Our greatest glory is not in never failing, but in rising up every time we fail.', 'person' => 'Ralph Waldo Emerson'],
            ['body' => 'Recovery is an acceptance that your life is in shambles and you have to change.', 'person' => 'Jamie Lee Curtis'],
            ['body' => 'Every form of addiction is bad, no matter whether the narcotic be alcohol or morphine or idealism.', 'person' => 'Carl Jung'],
            ['body' => 'Fall six times, stand up seven.', 'person' => 'Japanese Proverb'],
            ['body' => 'Addiction begins with the hope that something \'out there\' can instantly fill up the emptiness inside.', 'person' => 'Jean Kilbourne'],
            ['body' => 'Though no one can go back and make a brand new start, anyone can start from now and make a brand new ending.', 'person' => 'Carl Bard'],
            ['body' => 'I am worthy of investing in myself.', 'person' => 'Affirmation'],
            ['body' => 'Asking for help is a sign of self-respect and self-awareness.', 'person' => 'Affirmation'],
            ['body' => 'I grow towards my interests, like a plant reaching for the sun.', 'person' => 'Affirmation'],
            ['body' => 'I do not have to linger in dark places; there is help for me here.', 'person' => 'Affirmation'],
            ['body' => 'I am open. I am healing. I am happy.', 'person' => 'Affirmation'],
            ['body' => 'I am growing, and I am going at my own pace.', 'person' => 'Affirmation'],
            ['body' => 'I release the fears that do not serve me.', 'person' => 'Affirmation'],
            ['body' => 'Remember that just because you hit bottom doesn\'t mean you have to stay there.', 'person' => 'Robert Downey Jr.'],
            ['body' => 'Rock bottom became the solid foundation on which I rebuilt my life.', 'person' => 'J.K. Rowling'],
            ['body' => 'Before you can break out of prison, you must realize you are locked up.', 'person' => 'Anonymous'],
            ['body' => 'If you can quit for a day, you can quit for a lifetime.', 'person' => 'Benjamin Alire Sáenz'],
            ['body' => 'One of the hardest things was learning that I was worth recovery.', 'person' => 'Demi Lovato'],
            ['body' => 'The only person you are destined to become is the person you decide to be.', 'person' => 'Ralph Waldo Emerson'],
            ['body' => 'Recovery is about progression, not perfection.', 'person' => 'Unknown'],
            ['body' => 'If you accept the expectations of others, especially negative ones, then you never will change the outcome.', 'person' => 'Michael Jordan'],
            ['body' => 'I am capable of balancing ease and effort in my life.', 'person' => 'Affirmation'],
            ['body' => 'I affirm and encourage others, as I do myself.', 'person' => 'Affirmation'],
            ['body' => 'I am not defined by my relapses, but by my decision to remain in recovery despite them.', 'person' => 'Anonymous'],
            ['body' => 'The only journey is the journey within.', 'person' => 'Rainer Maria Rilke'],
            ['body' => 'Courage isn\'t having the strength to go on—it is going on when you don\'t have strength.', 'person' => 'Napoleon Bonaparte'],
            ['body' => 'The best way out is always through.', 'person' => 'Robert Frost'],
            ['body' => 'Hardships often prepare ordinary people for an extraordinary destiny.', 'person' => 'C.S. Lewis'],
            ['body' => 'It\'s not whether you get knocked down, it\'s whether you get up.', 'person' => 'Vince Lombardi'],
            ['body' => 'The only limit to our realization of tomorrow is our doubts of today.', 'person' => 'Franklin D. Roosevelt'],
            ['body' => 'What lies behind us and what lies before us are tiny matters compared to what lies within us.', 'person' => 'Ralph Waldo Emerson'],
            ['body' => 'You are never too old to set another goal or to dream a new dream.', 'person' => 'C.S. Lewis'],
            ['body' => 'The future depends on what you do today.', 'person' => 'Mahatma Gandhi'],
            ['body' => 'Don\'t let the past steal your present.', 'person' => 'Terri Guillemets'],
            ['body' => 'Out of difficulties grow miracles.', 'person' => 'Jean de La Bruyère'],
            ['body' => 'The greatest glory in living lies not in never falling, but in rising every time we fall.', 'person' => 'Nelson Mandela'],
            ['body' => 'The only way to achieve the impossible is to believe it is possible.', 'person' => 'Charles Kingsleigh'],
            ['body' => 'Start where you are. Use what you have. Do what you can.', 'person' => 'Arthur Ashe'],
            ['body' => 'What we achieve inwardly will change outer reality.', 'person' => 'Plutarch'],
            ['body' => 'Act as if what you do makes a difference. It does.', 'person' => 'William James'],
            ['body' => 'Keep your face always toward the sunshine—and shadows will fall behind you.', 'person' => 'Walt Whitman'],
            ['body' => 'The only thing standing between you and your goal is the story you keep telling yourself as to why you can\'t achieve it.', 'person' => 'Jordan Belfort'],
            ['body' => 'You are braver than you believe, stronger than you seem, and smarter than you think.', 'person' => 'A.A. Milne'],
            ['body' => 'The best time to plant a tree was 20 years ago. The second best time is now.', 'person' => 'Chinese Proverb'],
            ['body' => 'One day, or day one', 'person' => 'Dwayne \'The Rock\' Johnson']
        ];
        

        Quote::insert($quotes);
    }
}
