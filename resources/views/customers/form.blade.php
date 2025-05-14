<div class="mb-3">
    <label>Company</label>
    <input type="text" name="company" class="form-control" value="{{ old('company', $customer->company ?? '') }}" required placeholder="Enter company name">
</div>
<div class="mb-3">
    <label>Contact Person</label>
    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $customer->contact_person ?? '') }}" required placeholder="Enter contact person">
</div>
<div class="mb-3">
    <label>TIN</label>
    <input type="text" name="TIN" class="form-control" value="{{ old('TIN', $customer->TIN ?? '') }}" required placeholder="Enter TIN">
</div>
<div class="mb-3">
    <label>VRN</label>
    <input type="text" name="VRN" class="form-control" value="{{ old('VRN', $customer->VRN ?? '') }}" required placeholder="Enter VRN">
</div>
<div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}" required placeholder="Enter phone number">
</div>
<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? '') }}" required placeholder="Enter email address">
</div>
<div class="mb-3">
    <label>Address</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address ?? '') }}" required placeholder="Enter address">
</div>
<div class="mb-3">
    <label>Abbreviation</label>
    <input type="text" name="abbreviation" class="form-control" value="{{ old('abbreviation', $customer->abbreviation ?? '') }}" required placeholder="Enter abbreviation">
</div>
<div class="mb-3">
    <label>Credit Term (days)</label>
    <input type="number" name="credit_term" class="form-control" value="{{ old('credit_term', $customer->credit_term ?? '') }}" placeholder="Enter credit term in days">
</div>
<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ (old('status', $customer->status ?? 1) == 1) ? 'selected' : '' }}>Active</option>
        <option value="0" {{ (old('status', $customer->status ?? 1) == 0) ? 'selected' : '' }}>Inactive</option>
    </select>
</div>

