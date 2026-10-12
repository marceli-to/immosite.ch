<?php

namespace App\Http\Controllers\Admin;

use App\Apartments\Status;
use App\Http\Controllers\Controller;
use App\Models\CommercialObject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * The marketer edits status, rent and charges. Objects themselves come from
 * the building (config/commercial.php → commercial:seed) and are not created
 * or deleted here.
 */
class CommercialObjectController extends Controller
{
    public function index()
    {
        return view('admin.objects.index', [
            'objects' => CommercialObject::orderBy('position')->orderBy('id')->get(),
            'statuses' => collect(Status::cases())->mapWithKeys(fn (Status $s) => [$s->value => $s->label()]),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'objects' => ['required', 'array'],
            'objects.*.status' => ['required', Rule::enum(Status::class)],
            'objects.*.rent_net' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'objects.*.charges' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ], [
            'objects.*.status.required' => 'Bitte einen Status wählen.',
            'objects.*.status.*' => 'Unbekannter Status.',
            'objects.*.rent_net.integer' => 'Die Miete muss eine ganze Zahl sein.',
            'objects.*.charges.integer' => 'Die Nebenkosten müssen eine ganze Zahl sein.',
        ]);

        $objects = CommercialObject::whereKey(array_keys($validated['objects']))->get()->keyBy('id');
        $changed = 0;

        foreach ($validated['objects'] as $id => $values) {
            $object = $objects->get($id)?->fill($values);

            if ($object?->isDirty()) {
                $object->updated_by = $request->user()->name;
                $object->save();
                $changed++;
            }
        }

        return redirect()->route('admin.objects.index')->with('status', match ($changed) {
            0 => 'Keine Änderungen.',
            1 => '1 Objekt gespeichert.',
            default => "{$changed} Objekte gespeichert.",
        });
    }
}
