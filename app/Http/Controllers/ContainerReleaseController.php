<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerRelease;
use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;

class ContainerReleaseController extends Controller
{
    /**
     * Display a listing of container releases.
     */
    public function index()
    {
        $releases = ContainerRelease::with(['container', 'truck', 'driver'])
            ->orderBy('release_date', 'desc')
            ->paginate(20);

        return view('releases.index', compact('releases'));
    }

    /**
     * Show the form for creating a new container release.
     */
    public function create(Container $container)
    {
        // Verify container has EIR and is stored
        if ($container->status !== 'stored') {
            return redirect()->back()->with('error', 'Container must be stored before release.');
        }

        $drivers = Driver::where('is_active', true)->get();
        $trucks = Truck::where('is_active', true)->get();

        return view('releases.create', compact('container', 'drivers', 'trucks'));
    }

    /**
     * Store a newly created container release in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_id' => 'required|exists:containers,id',
            'release_order_number' => 'required|string|unique:container_releases,release_order_number',
            'truck_id' => 'required|exists:trucks,id',
            'driver_id' => 'required|exists:drivers,id',
            'release_order_details' => 'required|string',
        ]);

        // Create release
        $release = ContainerRelease::create([
            'container_id' => $validated['container_id'],
            'release_order_number' => $validated['release_order_number'],
            'truck_id' => $validated['truck_id'],
            'driver_id' => $validated['driver_id'],
            'release_date' => now(),
            'release_order_details' => $validated['release_order_details'],
            'validated_at_gate_b' => false,
        ]);

        return redirect()->route('releases.show', $release->id)
            ->with('success', 'Container release order created successfully.');
    }

    /**
     * Display the specified container release.
     */
    public function show(ContainerRelease $release)
    {
        $release->load(['container', 'truck', 'driver']);

        return view('releases.show', compact('release'));
    }

    /**
     * Validate release at gate B.
     */
    public function validateAtGateB(ContainerRelease $release)
    {
        if ($release->validated_at_gate_b) {
            return redirect()->back()->with('error', 'Container already validated at Gate B.');
        }

        $release->validated_at_gate_b = true;
        $release->gate_b_validation_time = now();
        $release->save();

        // Update container status
        $container = $release->container;
        $container->status = 'released';
        $container->save();

        return redirect()->route('releases.show', $release->id)
            ->with('success', 'Container validated and released at Gate B.');
    }
}
