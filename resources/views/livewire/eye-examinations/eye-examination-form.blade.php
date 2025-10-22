<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold">Eye Examination</h3>
            @if($visitModel)
                <p class="text-sm text-gray-500">Patient: {{ $visitModel->patient->name ?? 'N/A' }} — Checked in: {{ optional($visitModel->checked_in_at)->format('d M Y H:i') }}</p>
            @endif
        </div>

        <div class="space-x-2">
            <button wire:click="switchTab('diagnosis')" class="px-3 py-1 rounded {{ $tab==='diagnosis' ? 'bg-indigo-600 text-white' : 'bg-gray-100' }}">Diagnosis</button>
            <button wire:click="switchTab('prescription')" class="px-3 py-1 rounded {{ $tab==='prescription' ? 'bg-indigo-600 text-white' : 'bg-gray-100' }}">Prescription</button>
            <button wire:click="switchTab('notes')" class="px-3 py-1 rounded {{ $tab==='notes' ? 'bg-indigo-600 text-white' : 'bg-gray-100' }}">Notes</button>
        </div>
    </div>

    <div>
        {{-- Diagnosis Tab --}}
        <div x-cloak style="display: {{ $tab==='diagnosis' ? 'block' : 'none' }};">
            <div class="mb-4">
                <label class="block text-sm font-medium">Select Diagnoses</label>
                <select wire:model="selectedDiagnoses" multiple class="w-full mt-1 rounded border-gray-300">
                    @foreach($diagnosesMaster as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Prescription Tab --}}
        <div x-cloak style="display: {{ $tab==='prescription' ? 'block' : 'none' }};">
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold">Prescriptions</h4>
                    <button wire:click="$emit('openAddPrescriptionTypeModal')" class="px-3 py-1 bg-indigo-600 text-white rounded">+ Add Prescription</button>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach($prescriptions as $i => $p)
                        <div class="p-3 border rounded flex justify-between items-center">
                            <div>
                                <strong>{{ strtoupper($p['type']) }}</strong>
                                <div class="text-sm text-gray-600">
                                    @if($p['type'] === 'eye_drops')
                                        {{ collect($p['data']['drops'] ?? [])->pluck('name')->join(', ') ?: 'Eye drops' }}
                                    @else
                                        PD: {{ $p['data']['pd'] ?? '-' }} — R: {{ $p['data']['re_ds'] ?? '-' }} / L: {{ $p['data']['le_ds'] ?? '-' }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                <button wire:click="removePrescription({{ $i }})" class="text-red-500">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Notes Tab --}}
        <div x-cloak style="display: {{ $tab==='notes' ? 'block' : 'none' }};">
            <label class="block text-sm font-medium mb-1">Visual Acuity</label>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <input wire:model.defer="visual_acuity_r" placeholder="R.E (e.g. 6/6)" class="rounded border-gray-300 p-2" />
                <input wire:model.defer="visual_acuity_l" placeholder="L.E (e.g. 6/6)" class="rounded border-gray-300 p-2" />
            </div>

            <label class="block text-sm font-medium">Notes</label>
            <textarea wire:model.defer="notes" class="w-full rounded border-gray-300 p-2 mt-1" rows="4"></textarea>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="save" class="px-4 py-2 bg-green-600 text-white rounded">Save Examination</button>
        </div>
    </div>

    {{-- Include modal components --}}
    @livewire('eye-examinations.add-prescription-type-modal')
    @livewire('eye-examinations.add-eye-drops-modal')
    @livewire('eye-examinations.add-eye-glasses-modal')
</div>
