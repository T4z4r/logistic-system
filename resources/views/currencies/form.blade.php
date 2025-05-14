<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $currency->name ?? '') }}" required placeholder="Enter currency name">
</div>
<div class="mb-3">
    <label>Symbol</label>
    <input type="text" name="symbol" class="form-control" value="{{ old('symbol', $currency->symbol ?? '') }}" required placeholder="Enter symbol (e.g. $)">
</div>
<div class="mb-3">
    <label>Code</label>
    <input type="text" name="code" class="form-control" maxlength="3" value="{{ old('code', $currency->code ?? '') }}" placeholder="Enter code (e.g. USD)">
</div>
<div class="mb-3">
    <label>Currency</label>
    <input type="text" name="currency" class="form-control" value="{{ old('currency', $currency->currency ?? '') }}" required placeholder="Enter currency (e.g. Dollar)">
</div>
<div class="mb-3">
    <label>Rate</label>
    <input type="text" name="rate" class="form-control" value="{{ old('rate', $currency->rate ?? '') }}" placeholder="Enter rate">
</div>
<div class="mb-3">
    <label>Value</label>
    <input type="number" step="any" name="value" class="form-control" value="{{ old('value', $currency->value ?? '') }}" placeholder="Enter value">
</div>
<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control" required>
        <option value="1" {{ (old('status', $currency->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
        <option value="0" {{ (old('status', $currency->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
    </select>
</div>

