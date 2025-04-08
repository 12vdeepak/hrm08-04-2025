<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <!-- ROW -->
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-5">
                        <div class="col-md-6 col-lg-3">
                            <div wire:ignore class="form-group">
                                <label class="form-label">Employee Name:</label>
                                <select class="form-control custom-select select2" data-placeholder="Select Employee"
                                    wire:model="selectedEmployeeId" id="selected-employee">
                                    <option value="0" label="All">All</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            @if ($selectedEmployee) 
                                                {{ $selectedEmployee->id == $employee->id ? 'selected' : '' }} 
                                            @endif>
                                            {{ $employee->name . ' ' . $employee->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div wire:ignore class="form-group">
                                <label class="form-label">Month:</label>
                                <select wire:model="month" class="form-control custom-select select2"
                                    data-placeholder="Select Month" id="month">
                                    <option label="Select Month">Select Month</option>
                                    <option value="1" {{ $month == 1 ? 'selected' : '' }}>January</option>
                                    <option value="2" {{ $month == 2 ? 'selected' : '' }}>February</option>
                                    <option value="3" {{ $month == 3 ? 'selected' : '' }}>March</option>
                                    <option value="4" {{ $month == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ $month == 5 ? 'selected' : '' }}>May</option>
                                    <option value="6" {{ $month == 6 ? 'selected' : '' }}>June</option>
                                    <option value="7" {{ $month == 7 ? 'selected' : '' }}>July</option>
                                    <option value="8" {{ $month == 8 ? 'selected' : '' }}>August</option>
                                    <option value="9" {{ $month == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ $month == 10 ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ $month == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ $month == 12 ? 'selected' : '' }}>December</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div wire:ignore class="form-group">
                                <label class="form-label">Year:</label>
                                <select class="form-control custom-select select2" data-placeholder="Select Year"
                                    wire:model="year" id="year">
                                    <option label="Select Year"></option>
                                    <option value="2014" {{ $year == 2014 ? 'selected' : '' }}>2014</option>
                                    <option value="2015" {{ $year == 2015 ? 'selected' : '' }}>2015</option>
                                    <option value="2016" {{ $year == 2016 ? 'selected' : '' }}>2016</option>
                                    <option value="2017" {{ $year == 2017 ? 'selected' : '' }}>2017</option>
                                    <option value="2018" {{ $year == 2018 ? 'selected' : '' }}>2018</option>
                                    <option value="2019" {{ $year == 2019 ? 'selected' : '' }}>2019</option>
                                    <option value="2020" {{ $year == 2020 ? 'selected' : '' }}>2020</option>
                                    <option value="2021" {{ $year == 2021 ? 'selected' : '' }}>2021</option>
                                    <option value="2022" {{ $year == 2022 ? 'selected' : '' }}>2022</option>
                                    <option value="2023" {{ $year == 2023 ? 'selected' : '' }}>2023</option>
                                    <option value="2024" {{ $year == 2024 ? 'selected' : '' }}>2024</option>
                                    <option value="2025" {{ $year == 2025 ? 'selected' : '' }}>2025</option>
                                    <option value="2026" {{ $year == 2026 ? 'selected' : '' }}>2026</option>
                                    <option value="2027" {{ $year == 2027 ? 'selected' : '' }}>2027</option>
                                    <option value="2028" {{ $year == 2028 ? 'selected' : '' }}>2028</option>
                                    <option value="2029" {{ $year == 2029 ? 'selected' : '' }}>2029</option>
                                    <option value="2030" {{ $year == 2030 ? 'selected' : '' }}>2030</option>
                                    <option value="2031" {{ $year == 2031 ? 'selected' : '' }}>2031</option>
                                    <option value="2032" {{ $year == 2032 ? 'selected' : '' }}>2032</option>
                                    <option value="2033" {{ $year == 2033 ? 'selected' : '' }}>2033</option>
                                    <option value="2034" {{ $year == 2034 ? 'selected' : '' }}>2034</option>
                                    <option value="2035" {{ $year == 2035 ? 'selected' : '' }}>2035</option>
                                    <option value="2036" {{ $year == 2036 ? 'selected' : '' }}>2036</option>
                                    <option value="2037" {{ $year == 2037 ? 'selected' : '' }}>2037</option>
                                    <option value="2038" {{ $year == 2038 ? 'selected' : '' }}>2038</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center align-items-center">
                            <div class="form-group">
                                @php
                                    if($selectedEmployeeId == null)
                                        $id=0;
                                    else
                                        $id=$selectedEmployeeId;
                                    
                                @endphp
                                <a href="{{route('attendance_report_pdf',['user_id' => $id,'month' => $month, 'year' => $year]) }}" class="btn btn-info mb-1">Download Attendance Report</a>
                                <a href="{{route('checkins_report_pdf',['user_id' => $id,'month' => $month, 'year' => $year]) }}" class="btn btn-info mt-1">Download Checkins Report</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-6 mt-5">
                        <div class="me-3">
                            <label class="form-label">Note:</label>
                        </div>
                        <div>
                            <span class="badge badge-success-light me-2">P ---> Present</span>
                            <span class="badge badge-danger-light me-2">A ---> Absent</span>
                            {{-- <span class="badge badge-warning-light me-2"><i class="fa fa-star text-warning"></i> --->
                                Weekend/Holiday</span> --}}
                            <span class="badge badge-warning-light me-2">H ---> Holiday</span>
                            <span class="badge badge-warning-light me-2">W ---> Weekend</span>
                            <span class="badge badge-pink-light me-2">HD ---> Half Day</span>
                            <span class="badge badge-info-light me-2">O ---> Ongoing</span>
                            {{-- <span class="badge badge-danger-light me-2">AL ---> Approved Leave</span>
                            <span class="badge badge-danger-light me-2">UL ---> UnApproved Leave</span> --}}
                        </div>
                    </div>
                    <div class="table-responsive hr-attlist">
                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-attendance">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Employee Name</th>
                                    @for ($i = 1; $i <= $days; $i++)
                                        <th class="border-bottom-0 w-5">{{ $i }}</th>
                                    @endfor
                                    {{-- <th class="border-bottom-0">Total</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance_data->filter(function ($data) {
    return $data['detail']->employee_status == 1;
}) as $data)


                                {{-- {{dd($attendance_data)}} --}}
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <span class="avatar avatar brround me-3"
                                                    style="background-image: url({{ asset('assets/images/users/1.jpg)') }}"></span>
                                                <div class="me-3 mt-0 mt-sm-2 d-block">
                                                    <h6 class="mb-1 fs-14">
                                                        {{ $data['detail']->name . ' ' . $data['detail']->lastname }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>

                                        @foreach ($data['record']['attendance'] as $record)
                                            <td class="text-center">
                                                @if ($record['date'] > date('Y-m-d'))
                                                    <span class="badge badge-orange-light">-</span>
                                                @elseif ($record['status'] == 0)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-danger">A</span>
                                                @elseif($record['status'] == 0.5)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);"
                                                            wire:click="presentModalInit({{ $data['detail']->id }}, {{ $loop->iteration }})"
                                                            class="hr-listmodal"></a>
                                                        <span class="badge badge-pink-light">HD</span>
                                                    </div>
                                                @elseif($record['status'] == 1)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);"
                                                            wire:click="presentModalInit({{ $data['detail']->id }}, {{ $loop->iteration }})"
                                                            class="hr-listmodal"></a>
                                                        <span class="text-success ">P</span>
                                                    </div>
                                                @elseif($record['status'] == 2)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-info">O</span>
                                                    </div>
                                                @elseif($record['status'] == 3)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-warning">W</span>
                                                    </div>
                                                @elseif($record['status'] == 4)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-warning">H</span>
                                                    </div>
                                                {{-- @elseif($record['status'] == 5)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-warning">AL</span>
                                                    </div>
                                                @elseif($record['status'] == 6)
                                                    <div class="hr-listd">
                                                        <a href="javascript:void(0);" class="hr-listmodal"></a>
                                                        <span class="text-warning">UL</span>
                                                    </div> --}}
                                                @endif
                                            </td>
                                        @endforeach
                                        {{-- <td>
                                            <h6 class="mb-0">
                                                <span
                                                    class="text-primary">{{ $data['record']['days_absent'] }}</span>
                                                <span class="my-auto fs-8 font-weight-normal text-muted">/</span>
                                                <span class="">{{ $days }}</span>
                                            </h6>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->

    <!-- PRESENT MODAL -->
    <div wire:ignore.self class="modal fade" id="presentmodal" aria-hidden="true" tabindex="-1"
        style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Details</h5>
                    <button class="btn-close" wire:click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">{{ date('d/m/Y', strtotime($selectedDate)) }}</h5>
                    <div class="row mb-5 mt-4">
                        <div class="col-md-4">
                            <div class="pt-5 text-center">
                                <h6 class="mb-1 fs-16 font-weight-semibold">{{ $selectedClockInTime }}</h6>
                                <small class="text-muted fs-14">Clock In</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pt-5 text-center">
                                <div class="text-muted">{{ $selectedTotalTime }} hrs</div>
                                <div class="progress progress-sm mb-3">
                                    <div class="progress-bar bg-green" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pt-5 text-center">
                                <h6 class="mb-1 fs-16 font-weight-semibold">{{ $selectedClockOutTime }}</h6>
                                <small class="text-muted fs-14">Clock Out</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Clock-in Location</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $selectedClockInLocation }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Clock-in IP Address</label>
                                <input type="text" class="form-control" placeholder="{{ $selectedClockInIP }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Clock-out Location</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $selectedClockOutLocation }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Clock-out IP Address</label>
                                <input type="text" class="form-control" placeholder="{{ $selectedClockOutIP }}"
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-outline-primary" wire:click="closeModal">close</a>
                    {{-- <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editmodal" data-bs-dismiss="modal">Edit</a> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- END PRESENT MODAL -->

    <!-- EDIT MODAL -->
    <div wire:ignore.self class="modal fade" id="editmodal" aria-hidden="true" tabindex="-1"
        style="background: rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance Details</h5>
                    <button class="btn-close" wire:click="closeModal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Clock In</label>
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" value="9:30 AM">
                                    <div class="input-group-text">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-switch mt-md-6">
                                <input type="checkbox" name="custom-switch-checkbox"
                                    class="custom-switch-input orange">
                                <span class="custom-switch-indicator "></span>
                                <span class="custom-switch-description text-dark">Late</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Clock Out</label>
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" value="06: 30 PM">
                                    <div class="input-group-text">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-switch mt-md-6">
                                <input type="checkbox" name="custom-switch-checkbox"
                                    class="custom-switch-input  orange">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description text-dark">half Day</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">IP Address</label>
                        <input type="text" class="form-control" placeholder="225.192.145.1" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Working Form</label>
                        <select name="projects" class="form-control custom-select select2" disabled
                            data-placeholder="Select">
                            <option label="Select"></option>
                            <option value="1" selected>Office</option>
                            <option value="2">Home</option>
                            <option value="3">Others</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex">
                    <div>
                        <a href="javascript:void(0);" class="btn btn-light" data-bs-toggle="modal"
                            data-bs-target="#presentmodal" data-bs-dismiss="modal"><i
                                class="feather feather-arrow-left me-1"></i>Back</a>
                    </div>
                    <div class="ms-auto">
                        <a href="javascript:void(0);" class="btn btn-outline-primary"
                            wire:click="closeModal">close</a>
                        <a href="javascript:void(0);" class="btn btn-primary">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END EDIT MODAL -->

</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#month').select2();
            $('#month').on('change', function(e) {
                var data = $('#month').select2("val");
                @this.set('month', data);
            });

            $('#year').select2();
            $('#year').on('change', function(e) {
                var data = $('#year').select2("val");
                @this.set('year', data);
            });

            $('#selected-employee').select2();
            $('#selected-employee').on('change', function(e) {
                var data = $('#selected-employee').select2("val");
                @this.set('selectedEmployeeId', data);
            });
        });

        window.addEventListener('close-modal', event => {
            console.log('asdknd');
            $('.modal').modal('hide');
        });
        window.addEventListener('show-present-modal', event => {

            $('#presentmodal').modal('show');
        });
        window.addEventListener('show-edit-comment-modal', event => {
            $('#editCommentModal').modal('show');
        });
        $(document).ready(function() {
            $('.modal').on('hidden.bs.modal', function() {
                livewire.emit('forceClosedModal');
            });
        });
    </script>
@endsection
