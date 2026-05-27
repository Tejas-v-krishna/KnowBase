<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'reportable'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function dismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        return back()->with('success', 'Report dismissed.');
    }

    public function resolve(Report $report)
    {
        if ($report->reportable) {
            $report->reportable->delete();
        }
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Report resolved and content deleted.');
    }
}
