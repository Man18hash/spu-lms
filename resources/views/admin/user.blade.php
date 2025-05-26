@extends('layouts.admin')

@php
  use Illuminate\Support\Carbon;
@endphp

@section('title','Users')

@section('content')
  <h1 class="mb-4">User Management</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>DOB</th>
        <th>Address</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          @if($user->dob)
            {{ Carbon::parse($user->dob)->format('M d, Y') }}
          @endif
        </td>
        <td>{{ $user->address }}</td>
        <td class="text-center">

          <!-- Edit Button -->
          <button
            class="btn btn-sm btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#editUserModal{{ $user->id }}"
          >Edit</button>

          <!-- Delete Form -->
          <form
            action="{{ route('admin.user.destroy', $user) }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Delete this user?');"
          >
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
          </form>

          <!-- Edit Modal -->
          <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
               aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ route('admin.user.update', $user) }}" method="POST">
                  @csrf
                  @method('PUT')

                  <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel{{ $user->id }}">
                      Edit User & Employment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row g-3">
                      <!-- User Info -->
                      <div class="col-md-6">
                        <h6>User Info</h6>
                        <div class="mb-2">
                          <label class="form-label">Name</label>
                          <input
                            name="name"
                            value="{{ old('name',$user->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                          >
                          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Email</label>
                          <input
                            type="email"
                            name="email"
                            value="{{ old('email',$user->email) }}"
                            class="form-control @error('email') is-invalid @enderror"
                          >
                          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Password <small>(leave blank to keep)</small></label>
                          <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                          >
                          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Confirm Password</label>
                          <input
                            type="password"
                            name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                          >
                          @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">DOB</label>
                          <input
                            type="date"
                            name="dob"
                            value="{{ old('dob', $user->dob ? Carbon::parse($user->dob)->format('Y-m-d') : '') }}"
                            class="form-control @error('dob') is-invalid @enderror"
                          >
                          @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Address</label>
                          <textarea
                            name="address"
                            rows="2"
                            class="form-control @error('address') is-invalid @enderror"
                          >{{ old('address',$user->address) }}</textarea>
                          @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <!-- Employment Info -->
                      <div class="col-md-6">
                        <h6>Employment Info</h6>
                        @php $ed = $user->employmentDetail; @endphp

                        <div class="mb-2">
                          <label class="form-label">Department</label>
                          <input
                            name="department"
                            value="{{ old('department', $ed->department ?? '') }}"
                            class="form-control @error('department') is-invalid @enderror"
                          >
                          @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Position</label>
                          <input
                            name="position"
                            value="{{ old('position', $ed->position ?? '') }}"
                            class="form-control @error('position') is-invalid @enderror"
                          >
                          @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Date Hired</label>
                          <input
                            type="date"
                            name="date_hired"
                            value="{{ old('date_hired', isset($ed->date_hired) ? Carbon::parse($ed->date_hired)->format('Y-m-d') : '') }}"
                            class="form-control @error('date_hired') is-invalid @enderror"
                          >
                          @error('date_hired')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Monthly Salary</label>
                          <input
                            type="number"
                            step="0.01"
                            name="monthly_basic_salary"
                            value="{{ old('monthly_basic_salary', $ed->monthly_basic_salary ?? '') }}"
                            class="form-control @error('monthly_basic_salary') is-invalid @enderror"
                          >
                          @error('monthly_basic_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Payroll Acct #</label>
                          <input
                            name="payroll_account_number"
                            value="{{ old('payroll_account_number', $ed->payroll_account_number ?? '') }}"
                            class="form-control @error('payroll_account_number') is-invalid @enderror"
                          >
                          @error('payroll_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Bank Name</label>
                          <input
                            name="bank_name"
                            value="{{ old('bank_name', $ed->bank_name ?? '') }}"
                            class="form-control @error('bank_name') is-invalid @enderror"
                          >
                          @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2">
                          <label class="form-label">Bank Acct #</label>
                          <input
                            name="bank_account_number"
                            value="{{ old('bank_account_number', $ed->bank_account_number ?? '') }}"
                            class="form-control @error('bank_account_number') is-invalid @enderror"
                          >
                          @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /Edit Modal -->

        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endsection
