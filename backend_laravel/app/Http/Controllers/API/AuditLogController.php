<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditLogRequest;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

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

        $logDetail = AuditLog::findOrFail($log);

        return response()->json($logDetail);
    }

    /**
     * Create a new audit log.
     *
     * @param  \App\Http\Requests\StoreAuditLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuditLogRequest $request)
    {
        $validated = $request->validated();

        $log = AuditLog::create([
            'activity' => $validated['activity'],
            'user_id' => Auth::id(),
            'details' => $validated['details'],
        ]);

        return response()->json(['message' => 'Log created successfully', 'log' => $log], 201);
    }

    /**
     * Delete a specific audit log.
     *
     * @param  \App\Models\AuditLog  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditLog $log)
    {

        $logDetail = AuditLog::findOrFail($log);
        $logDetail->delete();

        return response()->json(['message' => 'Activity log deleted successfully.']);
    }
}
