@extends('layouts.bookkeeper')

@section('title', 'Payments')

@section('content')
<style>
  .modal-title {
    font-weight: 600;
    letter-spacing: 0.02em;
  }
  .modal-header {
    border-bottom: none;
    align-items: flex-start;
  }
  .ledger-summary {
    font-size: 1rem;
    color: #333;
    margin-bottom: 0.5rem;
  }
  .modal-summary-card {
    border-radius: 8px;
    border: 1px solid #eee;
    background: #f9f9fa;
    margin-bottom: 0.5rem;
  }
</style>

  <h1>Welcome, {{ auth()->user()->name }} (Bookkeeper)</h1>

  <h2 class="mt-5">Released Loans</h2>
  @if($releasedApplications->isEmpty())
    <p>No loans are currently released.</p>
  @else
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Borrower</th>
          <th>Amount</th>
          <th>Term</th>
          <th>Next Due Date</th>
          <th>Remaining Months</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($releasedApplications as $app)
          @php
            $schedules = $app->expectedSchedules->sortBy('due_date');
            $today = \Carbon\Carbon::today();
            $nextSchedule = $schedules->first(fn($sch) => \Carbon\Carbon::parse($sch->due_date)->gte($today));
            $remainingCount = $schedules->filter(fn($sch) => \Carbon\Carbon::parse($sch->due_date)->gte($today))->count();
          @endphp
          <tr>
            <td>{{ $app->id }}</td>
            <td>{{ $app->client->name }}</td>
            <td>₱{{ number_format($app->amount,2) }}</td>
            <td>{{ $app->term }} mo.</td>
            <td>
              @if($nextSchedule)
                {{ \Carbon\Carbon::parse($nextSchedule->due_date)->format('M Y') }}
              @else
                <em>All paid</em>
              @endif
            </td>
            <td>{{ $remainingCount }}</td>
            <td>
              <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#ledgerModal{{ $app->id }}">Edit Ledger</button>
              <a href="{{ route('bookkeeper.ledger.view', $app) }}" class="btn btn-sm btn-success">Payments</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  @foreach($releasedApplications as $app)
    <div class="modal fade" id="ledgerModal{{ $app->id }}" tabindex="-1" aria-hidden="true" data-app-id="{{ $app->id }}">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header align-items-start" style="border-bottom: 0;">
            <div>
              <h5 class="modal-title mb-1">SUBSIDIARY LEDGER - SET UP</h5>
              <div class="text-muted" style="font-size:0.95rem;">SPU LMS</div>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
          </div>
          <div class="px-4 pt-0 pb-2">
            <div class="modal-summary-card p-3 mb-3">
              <div class="row">
                <div class="col-md-12">
                  <p class="mb-1"><strong>Borrower:</strong> {{ $app->client->name }}</p>
                  <p class="mb-1"><strong>Loan #:</strong> {{ $app->loan_key }}</p>
                  <p class="mb-1">
                    <strong>Amount:</strong>
                    <span style="letter-spacing:0.5px;">₱{{ number_format($app->amount, 2) }}</span>
                    over <strong>{{ $app->term }}</strong> months
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-body pt-0">
            <div class="mb-3">
              <button class="btn btn-outline-secondary btn-sm" onclick="autoFill({{ $app->term }}, {{ $app->amount }}, {{ $app->id }})">Auto-fill {{ $app->term }} months</button>
              <button class="btn btn-outline-secondary btn-sm" onclick="clearRows({{ $app->id }})">Clear</button>
            </div>
            <table class="table table-bordered" id="ledgerTable{{ $app->id }}">
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Payment (₱)</th>
                  <th class="text-center">Remove</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <button class="btn btn-sm btn-outline-primary" onclick="addRow({{ $app->id }}, '', '')">+ Add Row</button>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success" onclick="saveLedger({{ $app->id }})">Save Ledger</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('scripts')
<script>
  window.ledgerData = {};
  @foreach($releasedApplications as $app)
    window.ledgerData[{{ $app->id }}] = [
      @foreach($app->expectedSchedules->sortBy('due_date') as $sch)
        {
          month: '{{ \Carbon\Carbon::parse($sch->due_date)->format('Y-m') }}',
          payment: '{{ number_format($sch->amount_due, 2, '.', '') }}'
        },
      @endforeach
    ];
  @endforeach

  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', () => {
      const appId = modal.dataset.appId;
      populateRows(appId);
    });
  });

  function populateRows(appId) {
    const tbody = document.getElementById(`ledgerTable${appId}`).tBodies[0];
    tbody.innerHTML = '';
    (window.ledgerData[appId] || []).forEach(r => addRow(appId, r.month, r.payment));
  }

  function clearRows(appId) {
    document.getElementById(`ledgerTable${appId}`).tBodies[0].innerHTML = '';
  }

  function addRow(appId, monthValue = '', paymentValue = '') {
    const tbody = document.getElementById(`ledgerTable${appId}`).tBodies[0];
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="month" class="form-control" value="${monthValue}"></td>
      <td><input type="number" step="0.01" class="form-control text-end" value="${paymentValue}"></td>
      <td class="text-center">
        <button class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">&times;</button>
      </td>`;
    tbody.appendChild(tr);
  }

  function autoFill(term, amount, appId) {
    clearRows(appId);
    const per = (amount / term).toFixed(2);
    let d = new Date();
    for (let i = 0; i < term; i++) {
      const mo = d.toISOString().slice(0, 7);
      addRow(appId, mo, per);
      d.setMonth(d.getMonth() + 1);
    }
  }

  function saveLedger(appId) {
    const rows = document.querySelectorAll(`#ledgerTable${appId} tbody tr`);
    const entries = Array.from(rows).map(r => {
      const [m, p] = r.querySelectorAll('input');
      return { month: m.value, payment: parseFloat(p.value) || 0 };
    });

    const total = entries.reduce((sum, e) => sum + e.payment, 0);
    const allowed = parseFloat(@json($releasedApplications->keyBy('id')->map->amount)[appId]);
    const diff = parseFloat((total - allowed).toFixed(2));

    if (diff > 0) {
      return alert(`Cannot save: Over-allocated by ₱${diff.toFixed(2)}`);
    } else if (diff < 0) {
      return alert(`Cannot save: Under-allocated by ₱${Math.abs(diff).toFixed(2)}`);
    }

    fetch(`/bookkeeper/application/${appId}/ledger`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ entries })
    })
    .then(r => r.ok ? location.reload() : r.text().then(t => alert(t)))
    .catch(e => alert('Error: ' + e.message));
  }
</script>
@endpush
