<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class FeedbackController extends Controller
{
    /**
     * Hiển thị danh sách phản ánh
     */
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Feedback::class);

        return view('pages.feedback.index');
    }

    /**
     * Hiển thị form tạo phản ánh mới
     */
    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Feedback::class);

        return view('pages.feedback.create');
    }

    /**
     * Hiển thị chi tiết phản ánh
     */
    public function show(Feedback $feedback): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $feedback);

        return view('pages.feedback.show', compact('feedback'));
    }

    /**
     * Hiển thị form chỉnh sửa phản ánh
     */
    public function edit(Feedback $feedback): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $feedback);

        return view('pages.feedback.edit', compact('feedback'));
    }

    /**
     * Hiển thị danh sách phản ánh cho giáo viên
     */
    public function teacherIndex(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Feedback::class);

        return view('pages.feedback.teacher-index');
    }
}
