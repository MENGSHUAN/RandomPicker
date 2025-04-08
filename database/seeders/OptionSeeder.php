<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Sophia', 'Mason', 'Isabella', 'Lucas',
            'Mia', 'Jackson', 'Charlotte', 'Aiden', 'Amelia', 'Grayson', 'Harper', 'Carter', 'Evelyn', 'Benjamin',
            'Abigail', 'Elijah', 'Emily', 'Luke', 'Elizabeth', 'Lincoln', 'Sofia', 'James', 'Avery', 'William',
            'Ella', 'Oliver', 'Scarlett', 'Henry', 'Grace', 'Alexander', 'Chloe', 'Michael', 'Victoria', 'Daniel',
            'Riley', 'Matthew', 'Aria', 'Samuel', 'Lily', 'David', 'Zoe', 'Joseph', 'Stella', 'Caleb',
            'Hazel', 'John', 'Ellie', 'Jack', 'Paisley', 'Wyatt', 'Audrey', 'Owen', 'Skylar', 'Dylan',
            'Violet', 'Gavin', 'Claire', 'Isaac', 'Bella', 'Jayden', 'Aurora', 'Thomas', 'Lucy', 'Charles',
            'Anna', 'Christopher', 'Samantha', 'Joshua', 'Caroline', 'Andrew', 'Genesis', 'Theodore', 'Aaliyah', 'Cameron',
            'Kennedy', 'Josiah', 'Kinsley', 'Sebastian', 'Allison', 'Ryan', 'Maya', 'Asher', 'Sarah', 'Nathan',
            'Madelyn', 'Aaron', 'Adeline', 'Isaiah', 'Alexa', 'Hunter', 'Ariana', 'Christian', 'Elena', 'Landon',
            'Gabriella', 'Colton', 'Naomi', 'Evan', 'Alice', 'Jonathan', 'Sadie', 'Nolan', 'Hailey', 'Robert'
        ];

        foreach ($names as $name) {
            Option::create([
                'name' => $name,
                'is_drawn' => false
            ]);
        }
    }
} 