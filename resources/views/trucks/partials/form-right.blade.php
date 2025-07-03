<div class="mb-4">
    <label class="form-label" for="year">Year</label>
    <input type="text" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
        value="{{ old('year') }}">
    @error('year')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="color">Color</label>
    <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror"
        value="{{ old('color') }}">
    @error('color')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="engine_number">Engine Number</label>
    <input type="text" name="engine_number" id="engine_number"
        class="form-control @error('engine_number') is-invalid @enderror" value="{{ old('engine_number') }}">
    @error('engine_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="engine_capacity">Engine Capacity</label>
    <input type="text" name="engine_capacity" id="engine_capacity"
        class="form-control @error('engine_capacity') is-invalid @enderror" value="{{ old('engine_capacity') }}">
    @error('engine_capacity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="gross_weight">Gross Weight</label>
    <input type="number" step="0.01" name="gross_weight" id="gross_weight"
        class="form-control @error('gross_weight') is-invalid @enderror" value="{{ old('gross_weight') }}">
    @error('gross_weight')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="location">Location</label>
    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
        value="{{ old('location') }}">
    @error('location')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="status">Status</label>
    <select name="status" id="status" class="form-control">
        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="amount">Amount</label>
    <input type="number" step="0.01" name="amount" id="amount"
        class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
    @error('amount')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="capacity">Capacity</label>
    <input type="number" step="0.01" name="capacity" id="capacity"
        class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}">
    @error('capacity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="manufacturer">Manufacturer</label>
    <input type="text" name="manufacturer" id="manufacturer"
        class="form-control @error('manufacturer') is-invalid @enderror" value="{{ old('manufacturer') }}">
    @error('manufacturer')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
