<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $canManage = $request->user()->hasPermission('payroll.manage');

        $payrolls = Payroll::with('user.employeeProfile.department')
            ->when(! $canManage, fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($request->filled('period'), fn ($query) => $query->where('pay_period', $request->period))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('payroll.index', [
            'payrolls' => $payrolls,
            'employees' => $canManage ? User::where('status', 'active')->orderBy('name')->get() : collect(),
            'canManage' => $canManage,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'pay_period' => ['required', 'string', 'max:40'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['required', 'numeric', 'min:0'],
            'deductions' => ['required', 'numeric', 'min:0'],
            'tax' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,processed,paid'],
        ]);

        $data['net_pay'] = $data['basic_salary'] + $data['allowances'] - $data['deductions'] - $data['tax'];

        Payroll::updateOrCreate(
            ['user_id' => $data['user_id'], 'pay_period' => $data['pay_period']],
            $data
        );

        return back()->with('success', 'Payroll entry saved.');
    }

    public function markPaid(Payroll $payroll): RedirectResponse
    {
        $payroll->update([
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Payroll marked as paid.');
    }
}
