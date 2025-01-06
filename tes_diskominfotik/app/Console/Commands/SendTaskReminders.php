<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send task reminders to users based on upcoming deadlines';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ambil semua task yang deadline-nya mendekati waktu saat ini (misalnya 1 jam ke depan)
        $tasks = Task::where('status', ['pending', 'in_progress'])
            ->whereBetween('deadline', [Carbon::now(), Carbon::now()->addMinutes(30)]) // Atau sesuaikan dengan waktu yang diinginkan
            ->get();

        foreach ($tasks as $task) {
            // Kirim notifikasi ke user yang terkait dengan task
            $user = $task->user; // Pastikan ada relasi dengan user
            $user->notify(new TaskReminder($task));

            $this->info('Reminder sent for task: ' . $task->title);
        }
    }
}
