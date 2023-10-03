<?php

use Spatie\Activitylog\Models\Activity;

if (!function_exists('operationInfoLogsAll')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Operation Info ID

     */
    function operationInfoLogsAll($id)
    {
        $logs = Activity::where('subject_id', $id)
            ->where('log_name', 'Operation Info')
            ->with(['subject', 'causer'])->get();

        return $logs;
    }
}

if (!function_exists('operationInfoLogsLast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Operation Info ID

     */
    function operationInfoLogsLast($id)
    {
        $lastActivity = Activity::where('subject_id', $id)
            ->where('log_name', 'Operation Info')
            ->with(['subject', 'causer'])
            ->latest()
            ->first();
        return $lastActivity;
    }
}
