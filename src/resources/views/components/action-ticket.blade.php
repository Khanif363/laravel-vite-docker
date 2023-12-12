<div class="relative inline-flex flex-row space-x-2 text-left">
    <select
        class="block w-full px-4 py-2 pr-8 leading-tight transition duration-150 ease-in-out bg-white border border-gray-400 rounded shadow appearance-none hover:border-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5"
        id="grid-state" data-id="{{ $model->id }}">
        <option value="" selected>-- Pilih Menu --</option>
        @if ($permission['role'] === 'Admin' || isset($access_with_department['View Detail Ticket']))
            <option value="{{ route('tickets.detail', ['id' => $model->id]) }}">Detail Ticket</option>
        @endif
        @if ($model->status == 'Closed')
        @else
            @if ($model->step == 1 && ($permission['role'] === 'Admin' || isset($access_with_department['Create Ticket'])))
                <option value="{{ route('tickets.create', ['id' => $model->id]) }}">Create Step 2</option>
            @endif
            @if ($model->status != 'Waiting Close' && $model->step == 2)
                <option value="{{ route('tickets.edit', ['ticket' => $model->id]) }}">Update Ticket</option>
            @endif
            @if (
                ($model->department_name ?? null) &&
                    ($model->resume_gm == 0 || $model->resume_cto == 0) &&
                    ($permission['role'] === 'Admin' || isset($access_with_department['Send Resume'][$model->department_id])))
                <option value="{{ route('tickets.send-resume', $model->id) }}">Send Resume</option>
            @endif
            @if (
                $permission['role'] === 'Admin' ||
                    (isset($access_with_department['Delete Ticket']) && $model->resume_gm == 0))
                <option value="delete-ticket" class="delete-ticket">Delete Ticket</option>
            @endif
        @endif
        @if (empty($model->rca) &&
                ($permission['role'] === 'Admin' ||
                    isset($access_with_department['Create Root Cause Analysis'][$model->department_id])))
            <option value="{{ route('problem-managements.create', ['ticket_id' => $model->id]) }}">Create RCA</option>
        @endif
        @if (empty($model->changes) &&
                ($permission['role'] === 'Admin' || isset($access_with_department['Create Changes'][$model->department_id])))
            <option value="{{ route('change-managements.create', ['ticket_id' => $model->id]) }}">Create Changes
            </option>
        @endif
    </select>
    @if (
        $permission['role'] === 'Admin' ||
            isset($access_with_department['Push Notification Ticket'][$model->department_id]))
        <div class="push-notification py-0.5 px-2 cursor-pointer rounded-md items-center flex bg-green-0 text-white font-medium text-sm leading-tight shadow-md hover:bg-green-1 hover:shadow-lg focus:bg-green-1 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-2 active:shadow-lg transition duration-150 ease-in-out"
            data-id="{{ $model->id }}"><i class="text-white fa-solid fa-volume-high"></i></div>
    @endif
</div>
