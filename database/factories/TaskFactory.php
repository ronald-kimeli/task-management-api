<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskData = [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'inprogress', 'completed']),
            'category_id' => $this->faker->numberBetween(1, 5),
            'due_date' => $this->faker->date()
        ];

        User::pluck('id')->random(2); 

        return $taskData;
    }

    /**
     * Define a state that attaches users to the task.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withUsers(): Factory
    {
        return $this->afterCreating(function ($task) {
            $userIds = User::pluck('id')->random(2); 
            $task->users()->attach($userIds);
        });
    }
}
