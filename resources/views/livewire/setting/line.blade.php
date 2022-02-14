<div>
    <div class="mt-2 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Change Line Setting') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Change Line Setting, Demo/get endpoint will use new Line Setting in next call.') }}
            </x-slot>

            <x-slot name="content">

                @if (session()->has('message'))
                    <div class="mr-3 text-green-400 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                @if (count($this->lines) > 0)
                    <div class="col-span-6 lg:col-span-4">

                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            @foreach ($this->lines as $key => $value)
                                <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200
{{ $loop->first ? '' : 'border-t border-gray-200 rounded-t-none' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                        wire:click="$emit('updateLine', '{{ $key }}')">
                                    <div
                                        class="{{ isset($line_setting) && $line_setting !== $key ? 'opacity-50' : '' }}">
                                        <!-- Role Name -->
                                        <div class="flex items-center">
                                            <div
                                                class="text-sm text-gray-600 {{ $line_setting == $key ? 'font-semibold' : '' }}">
                                                {{ $value }}
                                            </div>

                                            @if ($line_setting == $key)
                                                <svg class="ml-2 h-5 w-5 text-green-400" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Role Description -->
                                        <div class="mt-2 text-xs text-gray-600 text-left">
                                            {{ $key }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

            </x-slot>


        </x-jet-action-section>
    </div>
</div>

