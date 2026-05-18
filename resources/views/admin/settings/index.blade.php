@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-brand-primary">
                Additional Charges
            </h1>

            <p class="mt-2 text-sm text-brand-ink/55">
                Configure platform fees and optional order charges.
            </p>
        </div>

    </div>

    {{-- SETTINGS CARD --}}
    <form method="POST"
          action="{{ route('admin.settings.update') }}">

        @csrf

        <div class="overflow-hidden rounded-[2rem] border border-brand-border bg-white shadow-sm">

            {{-- TABLE HEADER --}}
            <div class="grid grid-cols-[1.2fr_0.8fr_1fr] border-b border-brand-border bg-brand-light/35 px-6 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-brand-ink/55">

                <div>Setting</div>
                <div>Value</div>
                <div>Status</div>

            </div>

            {{-- SETTINGS --}}
            <div class="divide-y divide-brand-border">

                @foreach($settings as $key => $group)

                    @php
                        $setting = $group->first();
                    @endphp

                    <div class="grid grid-cols-[1.2fr_0.8fr_1fr] items-center px-6 py-5">

                        {{-- LABEL --}}
                        <div>

                            <p class="font-semibold text-brand-primary">
                                {{ $setting->label }}
                            </p>

                            @if($setting->description)
                                <p class="mt-1 text-xs text-brand-ink/55">
                                    {{ $setting->description }}
                                </p>
                            @endif

                        </div>

                        {{-- VALUE --}}
                        <div>

                            @if($setting->type === 'boolean')

                                <select
                                    name="settings[{{ $key }}]"
                                    class="brand-input py-2">

                                    <option value="1" {{ $setting->value ? 'selected' : '' }}>
                                        Enabled
                                    </option>

                                    <option value="0" {{ !$setting->value ? 'selected' : '' }}>
                                        Disabled
                                    </option>

                                </select>

                            @else

                                <input
                                    type="number"
                                    step="0.01"
                                    name="settings[{{ $key }}]"
                                    value="{{ $setting->value }}"
                                    class="brand-input py-2">

                            @endif

                        </div>

                        {{-- STATUS --}}
                        <div>

                            @if($setting->type === 'boolean')

                                <span class="rounded-full bg-brand-light px-3 py-1 text-xs font-semibold text-brand-primary">
                                    Toggle Setting
                                </span>

                            @elseif($setting->type === 'percent')

                                <span class="rounded-full bg-brand-light px-3 py-1 text-xs font-semibold text-brand-primary">
                                    Percentage Fee
                                </span>

                            @else

                                <span class="rounded-full bg-brand-light px-3 py-1 text-xs font-semibold text-brand-primary">
                                    Fixed Amount
                                </span>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- ACTION --}}
        <div class="flex justify-end">

            <button
                class="brand-btn-primary px-6 py-3 text-sm">
                Save Settings
            </button>

        </div>

    </form>

</div>

@endsection