<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Account;
use App\Services\EquinoxClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function __construct(private EquinoxClient $equinox)
    {
    }

    public function index(Request $request)
    {
        // Get quotes from Equinox via local sync
        $quotes = Quote::with('account')
            ->when($request->search, fn ($q) =>
                $q->where('number', 'like', "%{$request->search}%")
                    ->orWhere('reference', 'like', "%{$request->search}%")
            )
            ->latest('date')
            ->paginate(20);

        // Get additional quotes from Equinox API for fresh data
        $equinoxQuotes = [];
        try {
            $equinoxQuotes = $this->equinox->get('quotes', [
                'limit' => 50,
                'page' => 1
            ]);
        } catch (\Exception $e) {
            logger()->warning("Failed to fetch quotes from Equinox: ".$e->getMessage());
        }

        $stats = [
            'total' => Quote::count(),
            'this_month' => Quote::whereMonth('date', now()->month)->count(),
            'total_value' => Quote::sum('value'),
            'avg_value' => Quote::avg('value'),
        ];

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes,
            'equinoxQuotes' => $equinoxQuotes['data'] ?? [],
            'stats' => $stats,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show($id)
    {
        $quote = Quote::with('account')->findOrFail($id);

        // Get detailed quote from Equinox
        $equinoxQuote = null;
        try {
            $equinoxQuote = $this->equinox->get("quotes/{$quote->equinox_id}");
        } catch (\Exception $e) {
            logger()->warning("Failed to fetch quote details from Equinox: ".$e->getMessage());
        }

        return Inertia::render('Quotes/Show', [
            'quote' => $quote,
            'equinoxQuote' => $equinoxQuote,
        ]);
    }

    public function create()
    {
        // Get accounts for quote creation
        $accounts = Account::select('id', 'name', 'email')->get();

        return Inertia::render('Quotes/Create', [
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:equinox_accounts,id',
            'reference' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $account = Account::findOrFail($validated['account_id']);

            // Prepare quote data for Equinox
            $quoteData = [
                'account_id' => $account->equinox_id,
                'reference' => $validated['reference'] ?? null,
                'items' => $validated['items'],
                'notes' => $validated['notes'] ?? null,
            ];

            // Submit to Equinox
            $equinoxResponse = $this->equinox->post('quotes', $quoteData);

            // Create local record for tracking
            $quote = Quote::create([
                'account_id' => $account->id,
                'equinox_id' => $equinoxResponse['id'] ?? $equinoxResponse['quoteId'] ?? null,
                'number' => $equinoxResponse['number'] ?? null,
                'date' => $equinoxResponse['date'] ?? now()->toDateString(),
                'reference' => $validated['reference'],
                'value' => collect($validated['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_price']),
            ]);

            return redirect()->route('quotes.show', $quote->id)
                ->with('success', 'Quote created successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create quote: '.$e->getMessage()]);
        }
    }

    public function convert($id)
    {
        $quote = Quote::with('account')->findOrFail($id);

        try {
            // Get quote details from Equinox
            $equinoxQuote = $this->equinox->get("quotes/{$quote->equinox_id}");

            if (! $equinoxQuote) {
                return back()->withErrors(['error' => 'Quote not found in Equinox system']);
            }

            // Convert quote to order in Equinox
            $orderData = [
                'account_id' => $quote->account->equinox_id,
                'quote_reference' => $quote->number,
                'orderLines' => $equinoxQuote['items'] ?? [],
            ];

            $equinoxResponse = $this->equinox->post('orders', $orderData);

            return redirect()->route('quotes.index')
                ->with('success', 'Quote converted to order successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to convert quote: '.$e->getMessage()]);
        }
    }

    public function download($id)
    {
        $quote = Quote::with('account')->findOrFail($id);

        try {
            // Get quote PDF from Equinox or generate locally
            $equinoxQuote = $this->equinox->get("quotes/{$quote->equinox_id}");
            return response()->json($equinoxQuote);
        } catch (\Exception $e) {
            return response()->json($quote);
        }
    }
}
