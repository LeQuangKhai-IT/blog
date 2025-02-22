<?php

namespace App\Http\Controllers\API;

use App\Models\AuditLog;
use App\Http\Controllers\Controller;


class AuditLogController extends Controller
{
    /**
     * Retrieve a list of audit logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $logs = AuditLog::paginate(15);

        if ($logs->isEmpty()) {
            return response()->json(['message' => 'No logs found'], 404);
        }

        return response()->json([
            'message' => 'Logs retrieved successfully',
            'data' => $logs
        ]);
    }

    /**
     * Delete a specific audit log.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $logDetail = AuditLog::find($id);

        if (!$logDetail) {
            return response()->json([
                'message' => 'Log not found',
            ], 404);
        }

        $logDetail->delete();

        return response()->json([
            'message' => 'Activity log deleted successfully.'
        ]);
    }
}
