<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $position->name ?? '') }}" required placeholder="Enter position name">
</div>
<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control" placeholder="Select status">
        <option value="" disabled {{ is_null(old('status', $position->status ?? null)) ? 'selected' : '' }}>Select status</option>
        <option value="1" {{ (old('status', $position->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
        <option value="0" {{ (old('status', $position->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
