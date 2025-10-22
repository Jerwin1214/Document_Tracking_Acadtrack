<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class PromotionHistoryController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Get distinct school years (for filtering)
        $years = Enrollment::select('school_year')
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // 🔹 Start query
        $query = Enrollment::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('lrn', 'like', '%' . $request->search . '%')
                  ->orWhere('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('year')) {
            $query->where('school_year', $request->year);
        }

        // 🔹 Sort by newest first
        $promotions = $query->orderByDesc('school_year')->paginate(10);

        return view('pages.admin.promotion-history.index', compact('promotions', 'years'));
    }
}
