<div class='relative flex justify-center text-left'>
    <select
        class='block px-4 py-2 pr-8 leading-tight transition duration-150 ease-in-out bg-white border border-gray-400 rounded shadow appearance-none hover:border-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5'
        id='grid-state' data-id='{{ $model?->id }}'>
        <option value='' selected>-- Pilih Menu --</option>
        <option value="{{ route('change-managements.detail', ['id' => $model?->id]) }}">Detail Changes</option>
        <option value="{{ route('change-managements.export', ['pdf', 'id' => $model?->id]) }}">Export PDF</option>
        @if ($model?->status != 'Closed')
            @if ($permission['role'] === 'Admin' || (isset($access_with_department['Edit Changes']) &&
                ($model?->is_draft ||
                    ($model?->last_progress == 'Approval By Manager' && $model?->status_approval1 === 2) ||
                    ($model?->last_progress == 'Approval By GM' && $model?->status_approval2 === 2))))
                <option value="{{ route('change-managements.edit', ['id' => $model?->id]) }}">Edit Changes</option>
                <option value="delete-changes" class="delete-changes">Delete Changes</option>
            @endif
            {{-- Approve Manager --}}
            @if (
                (($model?->status_approval1 === null && $model?->is_draft != 1) ||
                    ($model?->last_progress == 'Edit' &&
                        ($model?->status_approval1 === null || $model?->status_approval1 === 2 || $model?->status_approval2 === 2))) &&
                    ($permission['role'] === 'Admin' || $permission['role'] === 'General Manager' ||
                        ($model?->approval_level1_id === auth()->id() && isset($access_with_department['Verif Changes']))))
                <option value='approval1' data-id='{{ $model?->id }}'>Update Approval</option>
            @endif

            {{-- Approve GM --}}
            @if (
                ($model?->status_approval2 === null ||
                    ($model?->status_approval2 === 2 && $model?->last_progress == 'Approval By Manager')) &&
                    $model?->status_approval1 === 1 &&
                    ($permission['role'] === 'Admin' ||
                        ($model?->approval_level2_id === auth()->id() && isset($access_with_department['Verif Changes']))))
                <option value='approval2' data-id='{{ $model?->id }}'>Update Approval</option>
            @endif
            @if ($model?->is_draft && ($permission['role'] === 'Admin' || isset($access_with_department['Create Changes'])))
                <option value='submit' data-id='{{ $model?->id }}'>Submit</option>
            @endif
            {{-- @if (
                ($permission['role'] === 'Admin' ||
                    (isset($access_with_department['Delete Changes']) && $model?->creator_id == auth()->id() && ($model?->is_draft ||
                    ($model?->last_progress == 'Approval By Manager' && $model?->status_approval1 === 2) ||
                    ($model?->last_progress == 'Approval By GM' && $model?->status_approval2 === 2)))) &&
                    $model?->status_approval2 !== 1)
                <option value="delete-changes" class="delete-changes">Delete Changes</option>
            @endif --}}
        @endif
    </select>
</div>
