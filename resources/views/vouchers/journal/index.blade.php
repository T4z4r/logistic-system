@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Journal Vouchers</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage journal vouchers</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Journal Vouchers</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 rounded-0">
      @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif
      <div class="mb-4 text-center">
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createJournalVoucherModal">
          <i class="fa fa-plus me-1"></i> Create Journal Voucher
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Voucher Number</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Narration</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($vouchers as $voucher)
              <tr>
                <td>{{ $voucher->voucher_number }}</td>
                <td>{{ $voucher->date->format('Y-m-d') }}</td>
                <td>{{ number_format($voucher->amount, 2) }}</td>
                <td>{{ $voucher->narration ?? 'N/A' }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editJournalVoucherModal{{ $voucher->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('vouchers.journal.destroy', $voucher->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">No journal vouchers found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Journal Voucher Modal -->
  <div class="modal fade" id="createJournalVoucherModal" tabindex="-1" aria-labelledby="createJournalVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createJournalVoucherModalLabel">Create Journal Voucher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('vouchers.journal.store') }}" method="POST" id="createJournalVoucherForm">
            @csrf
            <div class="mb-3">
              <label for="create_date" class="form-label">Date</label>
              <input type="date" class="form-control @error('date') is-invalid @enderror" id="create_date" name="date" value="{{ old('date') }}">
              @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_narration" class="form-label">Narration</label>
              <textarea class="form-control @error('narration') is-invalid @enderror" id="create_narration" name="narration">{{ old('narration') }}</textarea>
              @error('narration')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Entries</label>
              <div id="create_entries">
                @if (old('entries'))
                  @foreach (old('entries') as $index => $entry)
                    <div class="row mb-2 entry-row">
                      <div class="col-md-4">
                        <select class="form-control @error('entries.' . $index . '.ledger_id') is-invalid @enderror" name="entries[{{ $index }}][ledger_id]">
                          <option value="">Select Ledger</option>
                          @foreach ($ledgers as $ledger)
                            <option value="{{ $ledger->id }}" {{ old('entries.' . $index . '.ledger_id') == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
                          @endforeach
                        </select>
                        @error('entries.' . $index . '.ledger_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control @error('entries.' . $index . '.amount') is-invalid @enderror" name="entries[{{ $index }}][amount]" value="{{ old('entries.' . $index . '.amount') }}" placeholder="Amount">
                        @error('entries.' . $index . '.amount')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-2">
                        <select class="form-control @error('entries.' . $index . '.type') is-invalid @enderror" name="entries[{{ $index }}][type]">
                          <option value="debit" {{ old('entries.' . $index . '.type') == 'debit' ? 'selected' : '' }}>Debit</option>
                          <option value="credit" {{ old('entries.' . $index . '.type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                        @error('entries.' . $index . '.type')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3">
                        <select class="form-control @error('entries.' . $index . '.cost_center_id') is-invalid @enderror" name="entries[{{ $index }}][cost_center_id]">
                          <option value="">None</option>
                          @foreach ($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('entries.' . $index . '.cost_center_id') == $costCenter->id ? 'selected' : '' }}>{{ $costCenter->name }}</option>
                          @endforeach
                        </select>
                        @error('entries.' . $index . '.cost_center_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="row mb-2 entry-row">
                    <div class="col-md-4">
                      <select class="form-control" name="entries[0][ledger_id]">
                        <option value="">Select Ledger</option>
                        @foreach ($ledgers as $ledger)
                          <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <input type="number" step="0.01" class="form-control" name="entries[0][amount]" placeholder="Amount">
                    </div>
                    <div class="col-md-2">
                      <select class="form-control" name="entries[0][type]">
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" name="entries[0][cost_center_id]">
                        <option value="">None</option>
                        @foreach ($costCenters as $costCenter)
                          <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row mb-2 entry-row">
                    <div class="col-md-4">
                      <select class="form-control" name="entries[1][ledger_id]">
                        <option value="">Select Ledger</option>
                        @foreach ($ledgers as $ledger)
                          taboo1 value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <input type="number" step="0.01" class="form-control" name="entries[1][amount]" placeholder="Amount">
                    </div>
                    <div class="col-md-2">
                      <select class="form-control" name="entries[1][type]">
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" name="entries[1][cost_center_id]">
                        <option value="">None</option>
                        @foreach ($costCenters as $costCenter)
                          taboo1 value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                @endif
              </div>
              <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addJournalEntry()">Add Entry</button>
              @error('entries')
                <div class="text-danger mt-2">{{ $message }}</div>
              @endif
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createJournalVoucherForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Journal Voucher Modal -->

  <!-- Edit Journal Voucher Modals -->
  @foreach ($vouchers as $voucher)
    <div class="modal fade" id="editJournalVoucherModal{{ $voucher->id }}" tabindex="-1" aria-labelledby="editJournalVoucherModalLabel{{ $voucher->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editJournalVoucherModalLabel{{ $voucher->id }}">Edit Journal Voucher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('vouchers->journal->update', $voucher->id) }}" method="POST" id="editJournalVoucherForm{{ $voucher->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_date_{{ $voucher->id }}" class="form-label">Date</label>
                <input type="date" class="form-control @error('date') is-auto @error('date') }}" id="edit_date_{{ $voucher->id }}" name="date" value="{{ old('date', $voucher->date->format('Y-m-d')) }}">
                @error('date')
                </div>
              </div>
              <div class="mb-3">
                <label for="edit_narration_{{ $voucher->id }}" class="form-label">Narration</label>
                <textarea class="form-control @error('narration') is-auto @error('narration') }}" id="edit_narration_{{ $voucher->id }}" name="narration">{{ old('narration', $voucher->narration) }}</textarea>
                @error('narration')
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Accounts</label>
                <div id="edit_entries_{{ $voucher->id }}">
                  @foreach ($voucher->entries as $index => $entry)
                    <div class="row mb-2 entry-row">
                      <div class="col-md-4">
                        <select class="form-control @error('entries.' . $index . '.ledger_id') is-auto @error('entries.' . $index . '.ledger_id') }}" name="entries[{{ $index }}][ledger_id]">
                          <option value="">Select Ledger</option>
                          @foreach ($ledgers as $ledger)
                            <option value="{{ $ledger->id }}" {{ old('entries.' . $index . '.ledger_id', $entry->ledger_id) == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
                          @endforeach
                        </select>
                        @error('entries.' . $index . '.ledger_id')
                          <div class="auto-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control @error('entries.' . $index . '.amount') is-auto @error('entries.' . $index . '.amount') }}" name="entries[{{ $index }}][amount]" value="{{ old('entries.' . $index . '.amount', $entry->amount) }}" placeholder="Amount">
                        @error('entries.' . $index . '.amount')
                          <div class="auto-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-2">
                        <select class="form-control @error('entries.' . $index . '.type') is-auto @error('entries.' . $index . '.type') }}" name="entries[{{ $index }}][type]">
                          <option value="debit" {{ old('entries.' . $index . '.type', $entry->type) == 'debit' ? 'selected' : '' }}>Debit</option>
                          <option value="credit" {{ old('entries.' . $index . '.type', $entry->type) == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                        @error('entries.' . $index . '.type')
                          <div class="auto-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3">
                        <select class="form-control @error('entries.' . $index . '.cost_center_id') is-auto @error('entries.' . $index . '.cost_center_id') }}" name="entries[{{ $index }}][cost_center_id]">
                          <option value="">None</option>
                          @foreach ($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('entries.' . $index . '.cost_center_id', $entry->cost_center_id) == $costCenter->id ? 'selected' : '' }}>{{ $costCenter->name }}</option>
                          @endforeach
                        </select>
                        @error('entries.' . $index . '.cost_center_id')
                          <div class="auto-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-3" onclick="addJournalEntry('edit_entries_{{ $voucher->id }}', {{ $voucher->entries->count() }})">Add Entry</button>
                @error('entries')
                  <div class="text-danger mt-2">{{ $message }}</div>
                @endif
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editJournalVoucherForm{{ $voucher->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Journal Voucher Modals -->

  <script>
    let entryIndex = document.querySelectorAll('#create_entries .entry-row').length;

    function addJournalEntry(containerId = 'create_entries', startIndex = entryIndex) {
      const container = document.getElementById(containerId);
      const newEntry = document.createElement('div');
      newEntry.className = 'row mb-2 entry-row';
      newEntry.innerHTML = `
        <div class="col-md-4">
          <select class="form-control" name="entries[${startIndex}][ledger_id]">
            <option value="">Select Ledger</option>
            @foreach ($ledgers as $ledger)
              <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" step="0.01" class="form-control" name="entries[${startIndex}][amount]" placeholder="Amount">
        </div>
        <div class="col-md-2">
          <select class="form-control" name="entries[${startIndex}][type]">
            <option value="debit">Debit</option>
            <option value="credit">Credit</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-control" name="entries[${startIndex}][cost_center_id]">
            <option value="">None</option>
            @foreach ($costCenters as $costCenter)
              <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
            @endforeach
          </select>
        </div>
      `;
      container.appendChild(newEntry);
      entryIndex++;
    }
  </script>
@endsection

