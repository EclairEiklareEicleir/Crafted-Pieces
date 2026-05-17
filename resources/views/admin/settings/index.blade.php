@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-[#4d3028]">
                Additional Charges
            </h1>

            <p class="mt-2 text-sm text-[#8f7a70]">
                Configure platform fees and optional order charges.
            </p>
        </div>

    </div>

    {{-- SETTINGS CARD --}}
    <form method="POST"
          action="{{ route('admin.settings.update') }}">

        @csrf

        <div class="overflow-hidden rounded-[2rem] border border-[#eadfd7] bg-white shadow-sm">

            {{-- TABLE HEADER --}}
            <div class="grid grid-cols-[1.2fr_0.8fr_1fr] border-b border-[#efe3da] bg-[#fcfaf8] px-6 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-[#8f7a70]">

                <div>Setting</div>
                <div>Value</div>
                <div>Status</div>

            </div>

            {{-- SETTINGS --}}
            <div class="divide-y divide-[#efe3da]">

                @foreach($settings as $key => $group)

                    @php
                        $setting = $group->first();
                    @endphp

                    <div class="grid grid-cols-[1.2fr_0.8fr_1fr] items-center px-6 py-5">

                        {{-- LABEL --}}
                        <div>

                            <p class="font-semibold text-[#4d3028]">
                                {{ $setting->label }}
                            </p>

                            @if($setting->description)
                                <p class="mt-1 text-xs text-[#8f7a70]">
                                    {{ $setting->description }}
                                </p>
                            @endif

                        </div>

                        {{-- VALUE --}}
                        <div>

                            @if($setting->type === 'boolean')

                                <select
                                    name="settings[{{ $key }}]"
                                    class="w-full rounded-2xl border border-[#eadfd7] px-4 py-2 text-sm">

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
                                    class="w-full rounded-2xl border border-[#eadfd7] px-4 py-2 text-sm">

                            @endif

                        </div>

                        {{-- STATUS --}}
                        <div>

                            @if($setting->type === 'boolean')

                                <span class="rounded-full bg-[#f4ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                                    Toggle Setting
                                </span>

                            @elseif($setting->type === 'percent')

                                <span class="rounded-full bg-[#f4ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                                    Percentage Fee
                                </span>

                            @else

                                <span class="rounded-full bg-[#f4ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
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
                class="rounded-2xl bg-[#5d342b] px-6 py-3 text-sm font-semibold text-white">
                Save Settings
            </button>

        </div>

    </form>

</div>

@endsection