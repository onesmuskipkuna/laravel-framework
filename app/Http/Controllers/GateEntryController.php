<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Driver;
use App\Models\GateEntry;
use App\Models\ShippingLine;
use App\Models\Truck;
use Illuminate\Http\Request;

class GateEntryController extends Controller
{
    /**
     * Display a listing of gate entries.
     */
    public function index()
    {
        $entries = GateEntry::with(['container', 'truck', 'driver', 'shippingLine'])
            ->orderBy('entry_time', 'desc')
            ->paginate(20);

        return view('gate-entries.index', compact('entries'));
    }

    /**
     * Show the form for creating a new gate entry.
     */
    public function create()
    {
        $shippingLines = ShippingLine::where('is_active', true)->get();
        $drivers = Driver::where('is_active', true)->get();
        $trucks = Truck::where('is_active', true)->get();

        return view('gate-entries.create', compact('shippingLines', 'drivers', 'trucks'));
    }

    /**
     * Store a newly created gate entry in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_number' => 'required|string|unique:containers,container_number',
            'container_type' => 'required|string',
            'shipping_line_id' => 'required|exists:shipping_lines,id',
            'truck_id' => 'required|exists:trucks,id',
            'driver_id' => 'required|exists:drivers,id',
            'booking_details' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        // Create container
        $container = Container::create([
            'container_number' => $validated['container_number'],
            'type' => $validated['container_type'],
            'shipping_line_id' => $validated['shipping_line_id'],
            'status' => 'pending',
            'booking_details' => $validated['booking_details'] ?? null,
        ]);

        // Create gate entry
        $gateEntry = GateEntry::create([
            'container_id' => $container->id,
            'truck_id' => $validated['truck_id'],
            'driver_id' => $validated['driver_id'],
            'shipping_line_id' => $validated['shipping_line_id'],
            'entry_time' => now(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('gate-entries.show', $gateEntry->id)
            ->with('success', 'Container registered successfully at gate.');
    }

    /**
     * Display the specified gate entry.
     */
    public function show(GateEntry $gateEntry)
    {
        $gateEntry->load(['container', 'truck', 'driver', 'shippingLine']);

        return view('gate-entries.show', compact('gateEntry'));
    }
}
