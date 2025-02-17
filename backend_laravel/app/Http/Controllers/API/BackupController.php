<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Retrieve a list of backups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get a list of available backup files in the backup directory
        $backupFiles = Storage::disk('local')->files('backups');
        return response()->json($backupFiles);
    }

    /**
     * Create a data backup.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // Create a database backup using `mysqldump`

        $backupFileName = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $backupFileName);

        $command = 'mysqldump -u ' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' ' . env('DB_DATABASE') . ' > ' . $backupPath; // Update database information
        $result = exec($command);

        if ($result === null) {
            return response()->json(['message' => 'Backup created successfully', 'backup_file' => $backupFileName]);
        } else {
            return response()->json(['message' => 'Failed to create backup'], 500);
        }
    }

    /**
     * Restore data from a backup.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        // Check if the backup file exists
        $file = $request->file('backup_file');
        if (!$file || !Storage::disk('local')->exists('backups/' . $file->getClientOriginalName())) {
            return response()->json(['message' => 'Backup file not found'], 404);
        }

        // Restore data from backup file
        $backupFilePath = storage_path('app/backups/' . $file->getClientOriginalName());

        // Database recovery command
        $command = 'mysql -u ' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' ' . env('DB_DATABASE') . ' < ' . $backupFilePath; // Update database information
        $result = exec($command);

        if ($result === null) {
            return response()->json(['message' => 'Data restored successfully']);
        } else {
            return response()->json(['message' => 'Failed to restore data'], 500);
        }
    }
}
