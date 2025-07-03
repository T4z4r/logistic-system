<div class="mb-4">
    <label class="form-label" for="purchase_date">Purchase Date</label>
    <input type="date" name="purchase_date" id="purchase_date"
        class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}">
    @error('purchase_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="plate_number">Plate Number</label>
    <input type="text" name="plate_number" id="plate_number"
        class="form-control @error('plate_number') is-invalid @enderror" value="{{ old('plate_number') }}"
        placeholder="Enter plate number">
    @error('plate_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="body_type">Body Type</label>
    <input type="text" name="body_type" id="body_type" class="form-control @error('body_type') is-invalid @enderror"
        value="{{ old('body_type') }}" placeholder="Enter body type">
    @error('body_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label">Truck Type</label>
    <select name="truck_type" class="form-control">
        <option value="">--choose Truck Type--</option>
        <option value="1" {{ old('truck_type') == 1 ? 'selected' : '' }}>Semi</option>
        <option value="2" {{ old('truck_type') == 2 ? 'selected' : '' }}>Pulling</option>
        <option value="3" {{ old('truck_type') == 3 ? 'selected' : '' }}>Private</option>
    </select>
</div>

<div class="mb-4">
    <label class="form-label">Fuel Type</label>
    <select name="fuel_type" class="form-control">
        <option value="Diesel">Diesel</option>
        <option value="Petrol">Petrol</option>
        <option value="Electric">Electric</option>
        <option value="Hybrid">Hybrid</option>
    </select>
</div>

<div class="mb-4">
    <label class="form-label" for="fuel_capacity">Fuel Capacity</label>
    <input type="text" name="fuel_capacity" id="fuel_capacity"
        class="form-control @error('fuel_capacity') is-invalid @enderror" value="{{ old('fuel_capacity') }}"
        placeholder="Enter fuel capacity">
    @error('fuel_capacity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="trailer_connection">Trailer Connection</label>
    <input type="text" name="trailer_connection" id="trailer_connection"
        class="form-control @error('trailer_connection') is-invalid @enderror" value="{{ old('trailer_connection') }}">
    @error('trailer_connection')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="trailer_capacity">Trailer Capacity</label>
    <input type="number" name="trailer_capacity" id="trailer_capacity"
        class="form-control @error('trailer_capacity') is-invalid @enderror" value="{{ old('trailer_capacity') }}">
    @error('trailer_capacity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="transmission">Transmission</label>
    <input type="text" name="transmission" id="transmission"
        class="form-control @error('transmission') is-invalid @enderror" value="{{ old('transmission') }}">
    @error('transmission')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="mileage">Mileage</label>
    <input type="text" name="mileage" id="mileage" class="form-control @error('mileage') is-invalid @enderror"
        value="{{ old('mileage') }}">
    @error('mileage')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="form-label" for="vehicle_model">Vehicle Model</label>
    <input type="text" name="vehicle_model" id="vehicle_model"
        class="form-control @error('vehicle_model') is-invalid @enderror" value="{{ old('vehicle_model') }}">
    @error('vehicle_model')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
