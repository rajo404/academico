<?php

namespace App\Http\Controllers;

use App\Interfaces\CertificatesInterface;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * Create or update a result for the selected enrollment.
     */
    public function store(Request $request)
    {
        $enrollment = Enrollment::findOrFail($request->input('enrollment'));

        if (Gate::forUser(backpack_user())->denies('edit-result', $enrollment)) {
            abort(403);
        }

        $result = Result::firstOrNew([
            'enrollment_id' => $enrollment->id,
        ]);

        $result->result_type_id = $request->input('result');

        $result->save();

        Log::info('Enrollment result saved by user '.backpack_user()->id);

        return $result;
    }

    public function exportResult(Enrollment $enrollment, CertificatesInterface $certificatesService)
    {
        $certificatesService->exportResult($enrollment);
    }

    public function exportCourseResults(Course $course, CertificatesInterface $certificatesService)
    {
        $certificatesService->exportCourseResults($course);
    }

    public function exportCertificate(Enrollment $enrollment, CertificatesInterface $certificatesService)
    {
        $certificatesService->exportCertificate($enrollment);
    }
}
