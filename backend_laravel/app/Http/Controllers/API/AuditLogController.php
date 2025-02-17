<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

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
        return response()->json($logs);
    }

    /**
     * Retrieve the details of a specific audit log.
     *
     * @param  \App\Models\AuditLog  $log
     * @return \Illuminate\Http\Response
     */
    public function show(AuditLog $log)
    {
        return response()->json($log);
    }

    /**
     * Delete a specific audit log.
     *
     * @param  \App\Models\AuditLog  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditLog $log)
    {
        $log->delete();
        return response()->json(['message' => 'Audit log deleted successfully']);
    }
}
