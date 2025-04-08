@extends('layouts.hr_app')

@section('styles')

@endsection

@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Attendence</div>
        </div>
    </div>

    @livewire('h-r.attendance')
@endsection

@section('scripts')

    <script>
        
        window.addEventListener('close-modal', event => {
            $('.modal').modal('hide');
        });
        window.addEventListener('show-add-comment-modal', event => {
            $('#addCommentModal').modal('show');
        });
        window.addEventListener('show-edit-comment-modal', event => {
            $('#editCommentModal').modal('show');
        });
        $(document).ready(function(){
            $('.modal').on('hidden.bs.modal', function(){
                livewire.emit('forceClosedModal');
            });
        });


    </script>
@endsection
