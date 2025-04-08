<div>
    <div wire:ignore.self class="modal fade" id="view_time_tracker"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStudentModalLabel">Time Tracker</h5>
                </div>
                    <div class="modal-body">
                        <table class="table table-bordered border-bottom">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Project Name</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S No.</td>
                                    <td>Project Name</td>
                                    <td>Title</td>
                                    <td>Description</td>
                                    <td>Start Time</td>
                                    <td>End Time</td>
                                    <td>Time</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div class="row">
                            <h6>Total Time : </h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="edit_log_time" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStudentModalLabel">Edit LogTime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update_log_time">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Start Time</label>
                            <input type="time" wire:model="start_time" class="form-control">
                            @error('start_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>End Time</label>
                            <input type="time" wire:model="end_time" class="form-control">
                            @error('end_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
