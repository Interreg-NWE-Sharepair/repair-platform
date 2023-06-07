<?php

namespace App\Http\Controllers\Replog\Repairer\Device;

use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\RepairLogNoteRequest;
use App\Models\RepairerOld;
use App\Models\RepairLog;
use App\Models\RepairLogNote;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/** @deprecated
 *I believe this controller is obsolete..
 */
class DeviceRepairLogNoteController extends ReplogController
{
    public function add(RepairLogNoteRequest $request, RepairerOld $repairer, $uuid)
    {
        $repairLog = RepairLog::whereUuid($uuid)->with('repairer', 'device')->firstOrFail();
        $device = $repairLog->device;

        $repairer = $repairer->getRepairerByUser(Auth::user()->id);
        if ($repairer->id !== $repairLog->repairer_id) {
            return back()->with('warning', trans('messages.not_repairer_of_log'));
        }

        $repairLog->addNote($request->input('note_content'));

        return redirect()->route('device_log_show', $repairLog->uuid)
                         ->with('success', trans('messages.success_note_added'));
    }

    public function edit(RepairerOld $repairer, $uuid, $id)
    {
        $repairLogNote = RepairLogNote::find($id);
        $repairLog = RepairLog::whereUuid($uuid)->with('repairer', 'device')->firstOrFail();

        $repairer = $repairer->getRepairerByUser(Auth::user()->id);
        if ($repairer->id !== $repairLog->repairer_id || $repairer->id !== $repairLogNote->repairLog->repairer_id || !$repairLogNote) {
            return back()->with('warning', trans('messages.not_repairer_of_log'));
        }

        return Inertia::render('Device/Log/Note/NoteEdit', [
            'repairNote' => $repairLogNote,
            'device' => $repairLog->device,
            'log' => $repairLog,
            'title' => trans('messages.form_note_edit'),
        ]);
    }

    public function update(RepairLogNoteRequest $request, RepairerOld $repairer, $uuid, $id)
    {
        try {
            $repairLogNote = RepairLogNote::find($id);
            $repairLog = RepairLog::whereUuid($uuid)->with('repairer', 'device')->firstOrFail();
            $device = $repairLog->device;

            $repairer = $repairer->getRepairerByUser(Auth::user()->id);
            if ($repairer->id !== $repairLog->repairer_id || $repairer->id !== $repairLogNote->repairLog->repairer_id || !$repairLogNote) {
                return back()->with('warning', trans('messages.not_repairer_of_log'));
            }

            DB::beginTransaction();
            $repairLogNote->content = $request->input('note_content');
            $repairLogNote->save();
            DB::commit();

            return redirect()->route('device_log_show', $repairLog->uuid)
                             ->with('success', trans('messages.success_note_updated'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }
}
