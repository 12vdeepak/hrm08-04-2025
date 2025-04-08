<!-- CHANGE PASSWORD MODAL -->

<div class="modal fade " id="changepasswordnmodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('change-password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" placeholder="password" value=""
                            name="password" @error('password') is-invalid @enderror>
                        @error('password')
                            <span class="invalid-feedback text-danger d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" placeholder="password" value=""
                            name="cpassword" @error('cpassword') is-invalid @enderror>
                        @error('cpassword')
                            <span class="invalid-feedback text-danger d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button> --}}
                        <button class="btn btn-primary" type="submit">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END CHANGE PASSWORD MODAL  -->
