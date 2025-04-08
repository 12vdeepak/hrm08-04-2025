<div>
    {{-- Stop trying to control. --}}
    @if($isCheckedIn)
        <div wire:poll.keep-alive.15000ms>
            {{ $this->end }}
            {{ $this->updateActivity() }}
        </div>
    @endif
</div>
