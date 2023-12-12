<div class='relative inline-block text-left'>
    <select
        class='block w-full px-4 py-2 pr-8 leading-tight transition duration-150 ease-in-out bg-white border border-gray-400 rounded shadow appearance-none hover:border-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5'
        id='grid-state' data-id='{{ $model->id }}'>
        <option value='' selected>-- Pilih Menu --</option>
        @if (
            $permission['role'] === 'Admin' ||
                isset($access_with_department['View Detail Root Cause Analysis'][$model->department_id]))
            <option value="{{ route('problem-managements.detail', ['id' => $model->id]) }}">Detail RCA</option>
        @endif
        @if (
            $model->is_verified === 1 &&
                ($permission['role'] === 'Admin' ||
                    isset($access_with_department['Update Root Cause Analysis'][$model->department_id])))
            <option value="{{ route('problem-managements.edit', ['problem' => $model->id]) }}">Update RCA</option>
        @endif
        @if (
            ($permission['role'] === 'Admin' ||
                isset($access_with_department['Verif Root Cause Analysis'][$model->department_id])) &&
                $model->is_verified === 0)
            <option value='verif' data-id='{{ $model->id }}'>Update Persetujuan</option>
        @endif
    </select>
</div>
