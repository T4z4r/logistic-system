<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalLevel;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = Approval::with('level')->paginate(10);
        return view('approvals.index', compact('approvals'));
    }

    public function create()
    {
        return view('approvals.create');
    }


    public function show($id)
    {
        $approval = Approval::with('level')->findOrFail($id);
        $roles = Role::all();
        return view('approvals.show', compact('approval', 'roles'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'process_name' => 'required|string|max:255|unique:approvals',
            'levels' => 'required|integer|min:0',
            'escallation' => 'required|boolean',
            'escallation_time' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Approval::create($request->only([
            'process_name',
            'levels',
            'escallation',
            'escallation_time',
        ]));

        return redirect()->route('approvals.list')->with('success', 'Approval process created successfully.');
    }

    public function edit($id)
    {
        $approval = Approval::findOrFail($id);
        return view('approvals.edit', compact('approval'));
    }

    public function update(Request $request, $id)
    {
        $approval = Approval::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'process_name' => 'required|string|max:255|unique:approvals,process_name,' . $approval->id,
            'levels' => 'required|integer|min:0',
            'escallation' => 'required|boolean',
            'escallation_time' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $approval->update($request->only([
            'process_name',
            'levels',
            'escallation',
            'escallation_time',
        ]));

        return redirect()->route('approvals.list')->with('success', 'Approval process updated successfully.');
    }

    public function destroy($id)
    {
        $approval = Approval::findOrFail($id);
        $approval->delete();
        return redirect()->route('approvals.list')->with('success', 'Approval process deleted successfully.');
    }

    public function createLevel($approval_id)
    {
        $approval = Approval::findOrFail($approval_id);
        $roles = Role::all();
        return view('approvals.levels.create', compact('approval', 'roles'));
    }

    public function storeLevel(Request $request, $approval_id)
    {
        $approval = Approval::findOrFail($approval_id);
        // dd($approval);

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'level_name' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'label_name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (ApprovalLevel::where('approval_id', $approval_id)
            ->where('level_name', $request->level_name)
            ->exists()
        ) {
            return redirect()->back()->withErrors(['level_name' => 'This level name already exists for this approval.'])->withInput();
        }

        $level = ApprovalLevel::create(array_merge(
            $request->only(['role_id', 'level_name', 'rank', 'label_name', 'status']),
            ['approval_id' => $approval_id]
        ));

        $approval = $level->approval;
        $approval->levels = $approval->levels + 1;
        $approval->save();

        return redirect()->route('approvals.list')->with('success', 'Approval level created successfully.');
    }

    public function editLevel($approval_id, $id)
    {
        $approval = Approval::findOrFail($approval_id);
        $approvalLevel = ApprovalLevel::findOrFail($id);
        $roles = Role::all();
        return view('approvals.levels.edit', compact('approval', 'approvalLevel', 'roles'));
    }

    public function updateLevel(Request $request, $approval_id, $id)
    {
        $approval = Approval::findOrFail($approval_id);
        $approvalLevel = ApprovalLevel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'level_name' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'label_name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (ApprovalLevel::where('approval_id', $approval_id)
            ->where('level_name', $request->level_name)
            ->where('id', '!=', $id)
            ->exists()
        ) {
            return redirect()->back()->withErrors(['level_name' => 'This level name already exists for this approval.'])->withInput();
        }

        $approvalLevel->update($request->only([
            'role_id',
            'level_name',
            'rank',
            'label_name',
            'status',
        ]));

        return redirect()->route('approvals.list')->with('success', 'Approval level updated successfully.');
    }

    public function destroyLevel($approval_id, $id)
    {
        // $approval = Approval::findOrFail($approval_id);
        // $approvalLevel = ApprovalLevel::findOrFail($id);
        // $approvalLevel->delete();

        $level = ApprovalLevel::where('id', $id)->first();

        // for changing approval level

        $appID = $level->approval_id;
        $approval = Approval::where('id', $appID)->first();
        $approval->levels = $approval->levels - 1;
        $approval->update();

        $level->delete();


        return redirect()->route('approvals.list')->with('success', 'Approval level deleted successfully.');
    }
}
