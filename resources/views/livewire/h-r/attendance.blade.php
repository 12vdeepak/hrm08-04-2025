<div>
    <div class="row mt-5">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0 justify-content-between">
                    <h4 class="card-title">Employees List</h4>
                    <div class="float-end">
                        <a href="{{ route('attendance_report') }}" class="btn btn-info">Report</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div id="hr-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-3">
                                    <div class="dataTables_length" id="hr-table_length">
                                        <input type="date" class="form-control" name="date" id="date"
                                            wire:model="date" value="{{ $date }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <select class="form-control custom-select" name="checkin_status" id="checkin_status" wire:model="status">
                                        <option value="0" label="Select">Select</option>
                                        <option value="1">Checked-in</option>
                                        <option value="2">Yet to Check-in</option>
                                        <option value="3">Checked-out</option>
                                        <option value="4">Yet to Check-out</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <div class="input-group mb-2">
                                        <div class="input-group-text"><i class="feather feather-search"></i></div>
                                        <input type="text" wire:model="search" placeholder="Search by employee"
                                            id="user" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-vcenter table-bordered" role="grid"
                                        aria-describedby="hr-table_info">
                                        <thead>
                                            <tr>
                                                <th>Emp Name</th>
                                                <th>First Check In</th>
                                                <th>Check In Location</th>
                                                <th>Last Check Out</th>
                                                <th>Check Out Location</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Comment</th>
                                              
                                                <th>Comment Action</th>
                                                   <th>Screen Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($employees->count() > 0)
                                                @foreach ($employees as $employee)
                                                
                                                    <tr>
                                                        <td>{{ $employee->name }}</td>
                                                        <td>{{ $employee->check_in_time }}</td>
                                                        <td>{{ $employee->check_in_location }}</td>
                                                        <td>{{ $employee->check_out_time }}</td>
                                                        <td>{{ $employee->check_out_location }}</td>
                                                        <td>{{ $employee->time }}</td>
                                                        <td><span class="badge @if($employee->attendance == 'Present') badge-success-light @elseif( $employee->attendance == 'Absent') badge-danger-light @else badge-pink-light @endif">{{ $employee->attendance }}</span></td>
                                                        <td>{{ $employee->comment }}</td>
                                                        @if ($employee->comment == '-')
                                                            <td>
                                                                <button class="btn btn-primary"
                                                                    wire:click="addCommentInit({{ $employee->id }})">Add</button>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <button class="btn btn-warning"
                                                                    wire:click="editCommentInit({{ $employee->id }})">Edit</button>
                                                            </td>
                                                        @endif
                                                        <?php
                                                        $id=$employee->id; $activities  = DB::table('activity_trackers')->where('user_id', $id)->whereDate('activity_time', '=', $date)->get();
                                                         $totalSeconds =0;
                                                        //dd($activities)
                                                       
        foreach($activities as $activity){
                 $startTime = strtotime($activity->start_time);
            $endTime = strtotime($activity->end_time);
            $secondsDiff = $endTime - $startTime;
            $totalSeconds += $secondsDiff;
            
           
        }
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds - ($hours * 3600)) / 60);
        $seconds = $totalSeconds - ($hours * 3600) - ($minutes * 60);
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
       
                                                        ?>
                                                     <td>{{$formattedTime}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" style="text-align: center;">No Employee Record
                                                        Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- @if ($hasPagination)
                                        {{ $employees->links() }}
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addCommentModal" aria-hidden="true" tabindex="-1"
        aria-labelledby="addCommentLabel" aria-hidden="true" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="addComment">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCommentLabel">Add Comment</h5>
                        <button type="button" class="btn btn-close" aria-label="Close"
                            wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea type="text" class="form-control @error('comment') is-invalid @enderror" wire:model.defer="comment"></textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editCommentModal" aria-hidden="true" tabindex="-1"
        aria-labelledby="editCommentLabel" aria-hidden="true" style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="editComment">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCommentLabel">Edit Comment</h5>
                        <button type="button" class="btn btn-close" aria-label="Close"
                            wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" wire:model.defer="comment">{{ $this->comment }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
