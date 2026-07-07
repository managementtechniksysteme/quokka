<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset
    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
    @endisset

    <div x-data="LivewireUISpotlight({ componentId: '{{ $this->id() }}', placeholder: '{{ trans('livewire-ui-spotlight::spotlight.placeholder') }}', commands: {{ $commands }} })"
         x-init="init()"
         x-show="isOpen"
         x-cloak
         @foreach(config('livewire-ui-spotlight.shortcuts') as $key)
            @keydown.window.prevent.cmd.{{ $key }}="toggleOpen()"
            @keydown.window.prevent.ctrl.{{ $key }}="toggleOpen()"
         @endforeach
         @keydown.window.escape="isOpen = false"
         @toggle-spotlight.window="toggleOpen()"
         class="position-fixed z-5000 px-5 align-items-start justify-content-center vw-100 vh-100"
         :class="{'d-none': !isOpen, 'd-flex': isOpen}"
         style="display: none">
        <div x-show="isOpen" @click="isOpen = false" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="position-fixed vw-100 vh-100 transition-opacity">
            <div class="position-absolute vw-100 vh-100 q-spotlight__backdrop"></div>
        </div>

        <div x-show="isOpen" x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="position-relative q-spotlight__panel mt-16 overflow-hidden transform transition-all max-w-2xl w-100">
            <div class="position-relative">
                <div class="position-absolute h-100 right-5 d-flex align-items-center">
                    <svg class="animate-spin h-5 w-5 q-spotlight__spinner" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" wire:loading.delay>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <input @keydown.tab.prevent="" @keydown.prevent.stop.enter="go()" @keydown.prevent.arrow-up="selectUp()"
                       @keydown.prevent.arrow-down="selectDown()" x-ref="input" x-model.debounce.500ms="input"
                       type="text"
                       class="spotlight-input w-100 bg-transparent lead border-0 outline-none"
                       x-bind:placeholder="inputPlaceholder">
            </div>
            <div class="q-spotlight__divider" x-show="filteredItems().length > 0">
                <ul x-ref="results" class="m-0 p-0 spotlight-results overflow-auto">
                    <template x-for="(item, i) in filteredItems()" :key>
                        <li>
                            <button @click="go(item[0].item.id)" class="q-spotlight__item d-block w-100 border-0 outline-none text-start"
                                    :class="{ 'is-selected': selected === i }">
                                <span x-text="item[0].item.name" class="q-spotlight__item-name"></span>
                                <span x-text="item[0].item.description" class="q-spotlight__item-desc ms-1"></span>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
</div>
