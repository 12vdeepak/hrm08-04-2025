<div wire:poll.5000ms>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @if(count($this->countnotifications())>0)
        <span class="bg-dot"></span>
    @endif
</div>
