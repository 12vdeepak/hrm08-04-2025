@extends('layouts.hr_app')
@section('content')
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">Announcements</div>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                <div class="btn-list">
                    <a href="" class="btn btn-primary me-3" data-bs-toggle = "modal" data-bs-target = "#annoucement">Add Annoucement</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Announcements List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-holiday">
                        <thead>
                            <tr>
                                <th class="border-bottom-0 w-5">S No.</th>
                                <th class="border-bottom-0">Title</th>
                                <th class="border-bottom-0">Date</th>
                                <th class="border-bottom-0">Message</th>
                                <th class="border-bottom-0">Department</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                    
                        <tbody>
                            @foreach ($announcements as $announcement )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $announcement->title }}
                                    
                                    </td>
                                     <td>{{ date('d-m-Y', strtotime($announcement->created_at)) }}
                                    
                                    </td>
                                    
                                    <td>{!! $announcement->announcement !!}</td>
                               
                                    <td>{{ $announcement->department }}</td>
                                   
                                
                                    <td >
                                      <div class="d-flex"> 
                                      <form action="{{ route('announcement.destroy',['announcement' => $announcement]) }}" method = "POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger btn-icon btn-sm" data-bs-toggle="tooltip" data-original-title="Delete"><i class="feather feather-trash-2"></i></button>
                                        </form>
                                          
                    <a href="" class="btn btn-warning btn-sm" data-bs-toggle = "modal" data-bs-target = "#annoucement_edit{{$announcement->id}}"><i class="fa fa-pencil"></i></a>
                
                
                </div>
                                      
                                    </td>
                                        <!---edit modal---->
 <div class="modal fade" id="annoucement_edit{{$announcement->id}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Announcement</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('announcement.update',['announcement' => $announcement]) }}" method ="POST">
                       @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Announcement Title"
                            value="{{$announcement->title}}"
                             name = "title" required></input>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea rows = 5 class=" ckeditor form-control " placeholder="Announcement Message" name = "announcement" required>{!! $announcement->announcement !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <select class="form-control" name = "department">
                                <option value="All">All</option>
                                @foreach($departments as $department)
                                <option  {{ ( "$announcement->department" == "$department->name") ? 'selected' : '' }}  value={{$department->name}}>{{$department->name}}</option>
                                @endforeach
                              </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Edit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
                <!--edit modal end---->
             

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')

    <!-- Announcement modal-->
    <div class="modal fade" id="annoucement">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Announcement</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('announcement.store') }}" method ="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Announcement Title" name = "title" required></input>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea rows = 5 class=" ckeditor form-control " placeholder="Announcement Message" name = "announcement" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <select class="form-control" name = "department">
                                <option value="All">All</option>
                                @foreach($departments as $department)
                                <option value={{$department->name}}>{{$department->name}}</option>
                                @endforeach
                              </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Add</button>
                    </div>

                </form>
            </div>
        </div>
    </div>




   
    <!-- end holiday modal-->


<!---all js code goes here--->
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $('.ckeditor').ckeditor();
    });
    <!---end of js code---->
</script>

@endsection()