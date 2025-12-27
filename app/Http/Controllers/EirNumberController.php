<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\EirNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EirNumberController extends Controller
{
    /**
     * Display a listing of EIR numbers.
     */
    public function index()
    {
        $eirNumbers = EirNumber::with('container')
            ->orderBy('issue_date', 'desc')
            ->paginate(20);

        return view('eir-numbers.index', compact('eirNumbers'));
    }

    /**
     * Show the form for creating a new EIR number.
     */
    public function create(Container $container)
    {
        // Verify container is in good condition
        if ($container->status !== 'inspected' && $container->status !== 'repaired') {
            return redirect()->back()->with('error', 'Container must be inspected before generating EIR.');
        }

        return view('eir-numbers.create', compact('container'));
    }

    /**
     * Store a newly created EIR number in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_id' => 'required|exists:containers,id',
            'issued_by' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $container = Container::find($validated['container_id']);

        // Check if EIR already exists
        if ($container->eirNumber) {
            return redirect()->back()->with('error', 'EIR number already exists for this container.');
        }

        // Generate unique EIR number
        $eirNumber = 'EIR-' . date('Y') . '-' . str_pad($container->id, 6, '0', STR_PAD_LEFT);

        // Create EIR
        $eir = EirNumber::create([
            'container_id' => $container->id,
            'eir_number' => $eirNumber,
            'issue_date' => now(),
            'issued_by' => $validated['issued_by'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Update container status
        $container->status = 'stored';
        $container->save();

        return redirect()->route('eir-numbers.show', $eir->id)
            ->with('success', 'EIR number generated successfully.');
    }

    /**
     * Display the specified EIR number.
     */
    public function show(EirNumber $eirNumber)
    {
        $eirNumber->load('container.shippingLine');

        return view('eir-numbers.show', compact('eirNumber'));
    }
}
