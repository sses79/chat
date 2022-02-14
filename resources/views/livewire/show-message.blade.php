<div>

    <div class="mt-3">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Contact List') }}
            </x-slot>

            <x-slot name="description">
                <div class="col-span-6 lg:col-span-4">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        @foreach ($this->users as $index => $user)

                            <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300
                                    focus:ring focus:ring-blue-200 {{ $index > 0 ? 'border-t border-gray-200 rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$emit('updateToId', '{{ $user->id }}')">
                                <div class="{{ $toId !== $user->id ? 'opacity-50' : '' }}">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600 font-semibold">
                                            {{ $user->name }}
                                        </div>
                                        @if ($toId == $user->id)
                                            <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round" stroke-width="2" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-xs text-gray-600 text-left">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </x-slot>

            <x-slot name="content">
                <div>

                    <div class="text-gray-600 w-full mb-4">One to One Message with: {{ $this->toUser->name }}</div>

                    @foreach ($this->messages as $index => $message)
                        @if (!is_object($message))
                            @php
                                $message = (object) $message;
                            @endphp
                        @endif
                        @if($message->from_id == Auth::user()->id)
                            <div class="my-4 text-right">
                                <p class="bg-blue-700 inline-flex text-white px-4 py-2 rounded-lg">
                                    {{ $message->body }}
                                    <small class="pl-5 pt-3 inline-flex">
                                        @if($message->seen == 1)
                                            <svg class="pt-1 pr-1 svg-inline--fa fa-check fa-w-16"
                                                 aria-hidden="true"
                                                 height="14px"
                                                 focusable="false" data-prefix="fas" data-icon="check" role="img"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                 data-fa-i2svg="">
                                                <path fill="currentColor"
                                                      d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path>
                                            </svg>
                                        @endif
                                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</small>
                                </p>
                            </div>
                        @else
                            <div class="my-4 text-left">
                                <p class="bg-gray-200 inline-flex px-4 py-2 rounded-lg">
                                    {{ $message->body }}

                                    <small class="pl-5 pt-3">
                                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</small>
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="flex flex-row justify-between">
                    <input
                        wire:model.lazy="messageBody"
                        wire:keydown.enter="sendMessage"
                        class="mr-4 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        placeholder="Type a message...">
                    <x-jet-button wire:loading.attr="disabled" wire:click="sendMessage">
                        {{ __('Send') }}
                    </x-jet-button>
                </div>
                @error('messageBody')
                <div class="error mt-2">{{ $message }}</div>
                @enderror
            </x-slot>
        </x-jet-action-section>
    </div>


</div>
