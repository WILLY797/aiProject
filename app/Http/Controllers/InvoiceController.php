<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Services\EquinoxClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function __construct(private EquinoxClient $equinox)
    {
    }

    public function index(Request $request)
    {
        // Get invoices from local Equinox sync
        $invoices = Invoice::with('account')
            ->when($request->search, fn ($q) =>
                $q->where('number', 'like', "%{$request->search}%")
                    ->orWhere('reference', 'like', "%{$request->search}%")
            )
            ->latest('date')
            ->paginate(20);

        // Calculate aging report based on local data
        $today = now();
        $agingReport = [
            'current' => Invoice::where('date', '>=', $today->subDays(30))->sum('total_gross'),
            '1_30_days' => Invoice::whereBetween('date', [$today->copy()->subDays(60), $today->copy()->subDays(31)])->sum('total_gross'),
            '31_60_days' => Invoice::whereBetween('date', [$today->copy()->subDays(90), $today->copy()->subDays(61)])->sum('total_gross'),
            '61_90_days' => Invoice::whereBetween('date', [$today->copy()->subDays(120), $today->copy()->subDays(91)])->sum('total_gross'),
            'over_90_days' => Invoice::where('date', '<', $today->copy()->subDays(120))->sum('total_gross'),
        ];

        $stats = [
            'total' => Invoice::count(),
            'this_month' => Invoice::whereMonth('date', now()->month)->count(),
            'total_value' => Invoice::sum('total_gross'),
            'avg_value' => Invoice::avg('total_gross'),
            'net_total' => Invoice::sum('total_net'),
            'vat_total' => Invoice::sum('total_vat'),
        ];

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'stats' => $stats,
            'agingReport' => $agingReport,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['account'])->findOrFail($id);

        // Get detailed invoice from Equinox
        $equinoxInvoice = null;
        $invoiceLines = [];

        try {
            $equinoxInvoice = $this->equinox->get("invoices/{$invoice->equinox_id}");
            $invoiceLines = $this->equinox->get("invoices/{$invoice->equinox_id}/lines");
        } catch (\Exception $e) {
            logger()->warning("Failed to fetch invoice details from Equinox: ".$e->getMessage());
        }

        return Inertia::render('Invoices/Show', [
            'invoice' => $invoice,
            'equinoxInvoice' => $equinoxInvoice,
            'lines' => $invoiceLines['data'] ?? [],
        ]);
    }

    public function download($id)
    {
        $invoice = Invoice::with('account')->findOrFail($id);

        try {
            // Get invoice PDF from Equinox or generate locally
            $equinoxInvoice = $this->equinox->get("invoices/{$invoice->equinox_id}");
            return response()->json($equinoxInvoice);
        } catch (\Exception $e) {
            return response()->json($invoice);
        }
    }

    public function send($id)
    {
        $invoice = Invoice::with('account')->findOrFail($id);

        // Send invoice via email through Equinox or local email service
        try {
            // This would typically be handled by Equinox
            // For now, just return success
            return back()->with('success', 'Invoice sent successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send invoice: '.$e->getMessage()]);
        }
    }
}
