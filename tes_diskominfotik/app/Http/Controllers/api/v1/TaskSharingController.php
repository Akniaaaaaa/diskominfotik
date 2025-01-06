<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskSharingController extends Controller
{
    // Fungsi untuk berbagi task dengan pengguna lain
    public function shareTask(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|in:view,edit',
        ]);

        // Pastikan pengguna yang sedang login adalah pemilik task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to share this task.'], 403);
        }

        // Bagikan task kepada pengguna dengan izin yang dipilih
        $task->users()->attach($request->user_id, ['permission' => $request->permission]);

        return response()->json(['message' => 'Task shared successfully.']);
    }

    // Fungsi untuk melihat daftar pengguna yang memiliki akses ke task
    public function getUsersWithAccess(Task $task)
    {
        // Ambil semua pengguna yang memiliki akses ke task
        $users = $task->users;

        return response()->json($users);
    }

    // Fungsi untuk menghapus akses pengguna dari task
    public function removeUserAccess(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Pastikan pengguna yang sedang login adalah pemilik task
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to remove this user.'], 403);
        }

        // Hapus akses pengguna dari task
        $task->users()->detach($request->user_id);

        return response()->json(['message' => 'User access removed successfully.']);
    }
}
