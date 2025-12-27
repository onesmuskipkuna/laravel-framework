<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerDamage;
use App\Models\ContainerInspection;
use App\Models\DamageCode;
use App\Models\Tariff;
use Illuminate\Http\Request;

class ContainerInspectionController extends Controller
{
    /**
     * Display a listing of inspections.
     */
    public function index()
    {
        $inspections = ContainerInspection::with('container')
            ->orderBy('inspection_date', 'desc')
            ->paginate(20);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Show the form for creating a new inspection.
     */
    public function create(Container $container)
    {
        $damageCodes = DamageCode::where('is_active', true)->get();

        return view('inspections.create', compact('container', 'damageCodes'));
    }

    /**
     * Store a newly created inspection in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_id' => 'required|exists:containers,id',
            'surveyor_name' => 'required|string',
            'condition' => 'required|in:good,damaged,rejected',
            'inspection_notes' => 'nullable|string',
            'action' => 'nullable|in:proceed_to_eir,repair_required,wash_required,write_off',
            'damages' => 'nullable|array',
            'damages.*.damage_code_id' => 'required|exists:damage_codes,id',
            'damages.*.description' => 'nullable|string',
        ]);

        // Create inspection
        $inspection = ContainerInspection::create([
            'container_id' => $validated['container_id'],
            'surveyor_name' => $validated['surveyor_name'],
            'inspection_date' => now(),
            'condition' => $validated['condition'],
            'inspection_notes' => $validated['inspection_notes'] ?? null,
            'action' => $validated['action'] ?? null,
        ]);

        // Update container status
        $container = Container::find($validated['container_id']);
        $container->status = $validated['condition'];
        $container->save();

        // Create damages if any
        if (!empty($validated['damages'])) {
            foreach ($validated['damages'] as $damageData) {
                // Get tariff for repair cost
                $tariff = Tariff::where('shipping_line_id', $container->shipping_line_id)
                    ->where('damage_code_id', $damageData['damage_code_id'])
                    ->where('is_active', true)
                    ->first();

                ContainerDamage::create([
                    'container_id' => $container->id,
                    'damage_code_id' => $damageData['damage_code_id'],
                    'inspection_id' => $inspection->id,
                    'description' => $damageData['description'] ?? null,
                    'repair_cost' => $tariff ? $tariff->repair_cost : null,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('inspections.show', $inspection->id)
            ->with('success', 'Container inspection completed successfully.');
    }

    /**
     * Display the specified inspection.
     */
    public function show(ContainerInspection $inspection)
    {
        $inspection->load(['container', 'damages.damageCode']);

        return view('inspections.show', compact('inspection'));
    }
}
