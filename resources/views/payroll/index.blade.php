<x-app-layout>
    <div class="mb-4">
        <h3 class="fw-semibold mb-1">Payroll</h3>
        <p class="text-muted mb-0">Create payroll entries, track payment status, and let employees view payslips.</p>
    </div>

    <div class="row">
        @if ($canManage)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Payroll Entry</h5>
                        <form method="post" action="{{ route('payroll.store') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Employee</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">Select employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} · {{ $employee->employee_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pay Period</label>
                                <input name="pay_period" value="{{ now()->format('F Y') }}" class="form-control" required>
                            </div>
                            @foreach (['basic_salary' => 'Basic Salary', 'allowances' => 'Allowances', 'deductions' => 'Deductions', 'tax' => 'Tax'] as $field => $label)
                                <div class="col-md-6">
                                    <label class="form-label">{{ $label }}</label>
                                    <input type="number" step="0.01" name="{{ $field }}" value="0" class="form-control" required>
                                </div>
                            @endforeach
                            <div class="col-md-6">
                                <label class="form-label">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="processed">Processed</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Save Payroll</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="{{ $canManage ? 'col-lg-8' : 'col-lg-12' }}">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">{{ $canManage ? 'Payroll Register' : 'My Payslips' }}</h5>
                        <form method="get">
                            <input name="period" value="{{ request('period') }}" class="form-control form-control-sm" placeholder="May 2026">
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Gross</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    @if ($canManage)
                                        <th class="text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payrolls as $payroll)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $payroll->user->name }}</div>
                                            <div class="text-muted fs-2">{{ $payroll->user->employeeProfile?->department?->name ?? 'Unassigned' }}</div>
                                        </td>
                                        <td>{{ $payroll->pay_period }}</td>
                                        <td>₹{{ number_format($payroll->basic_salary + $payroll->allowances, 2) }}</td>
                                        <td>₹{{ number_format($payroll->deductions + $payroll->tax, 2) }}</td>
                                        <td class="fw-semibold">₹{{ number_format($payroll->net_pay, 2) }}</td>
                                        <td><span class="badge bg-light-primary text-primary">{{ ucfirst($payroll->status) }}</span></td>
                                        @if ($canManage)
                                            <td class="text-end">
                                                @if ($payroll->status !== 'paid')
                                                    <form action="{{ route('payroll.paid', $payroll) }}" method="post">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-sm btn-outline-success">Mark Paid</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">{{ $payroll->payment_date?->format('d M Y') }}</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-muted">No payroll entries found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $payrolls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
