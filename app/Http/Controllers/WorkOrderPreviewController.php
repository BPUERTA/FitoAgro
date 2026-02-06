<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkOrderPreviewController extends Controller
{
    public function sample($id = null)
    {
        $user = Auth::user();

        $query = WorkOrder::with(['farms', 'products', 'client', 'organization']);
        if (!$user->is_admin) {
            $query->where('organization_id', $user->organization_id);
        }

        $workOrder = $id ? $query->findOrFail($id) : $query->firstOrFail();

        $productsByBlock = $workOrder->products->groupBy('bloque');

        $pdf = Pdf::loadView('work-orders.pdf', compact('workOrder', 'productsByBlock'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("workorder_{$workOrder->id}.pdf");
    }
}
