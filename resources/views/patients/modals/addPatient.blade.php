<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientLabel">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="form-horizontal" method="post" action="{{route('addPatient')}}">
                <div class="modal-body">

                    {{-- General error message --}}
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Oops!
                            {{ $errors->first('general') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{csrf_field()}}

                    <div class="mb-3{{ $errors->has('firstName') ? ' has-error' : '' }}">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstName" id= "firstName" value="{{ old('firstName') }}" required>
                        @if ($errors->has('firstName'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('firstName') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('lastName') ? ' has-error' : '' }}">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastName" id= "lastName" value="{{ old('lastName') }}">
                        @if ($errors->has('lastName'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('lastName') }}</strong>
                            </div>
                        @endif
                    </div>

                      <!-- Date of Birth with Tempus Dominus datetimepicker -->
                      <div class="mb-3{{ $errors->has('dob') ? ' has-error' : '' }}">
                        <label class="form-label">Date of Birth</label>
                        <div class="input-group" id="dob-picker">
                            <input class="form-control" id="dob" name="dob" type="text" value="{{ old('dob') }}" required>
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        @if ($errors->has('dob'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('dob') }}</strong>
                            </div>
                        @endif
                    </div>


                    <div class="mb-3{{ $errors->has('gender') ? ' has-error' : '' }}">
                        <label class="form-label">Gender</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="gender" value="Male" checked>
                            <label class="form-check-label">Male</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="gender" value="Female">
                            <label class="form-check-label">Female</label>
                        </div>
                        @if ($errors->has('gender'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                        @if ($errors->has('address'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('nic') ? ' has-error' : '' }}">
                        <label class="form-label">NIC</label>
                        <input type="text" class="form-control" name="nic" value="{{ old('nic') }}" pattern="[0-9]{9}[vV]">
                        @if ($errors->has('nic'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('nic') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label class="form-label">Contact No.</label>
                        <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </div>
                        @endif
                    </div>

                    <?php $bloodGroups = ["N/A", "A +", "A-", "B +", "B -", "AB +", "AB -", "O +", "O -"]; ?>
                    <div class="mb-3{{ $errors->has('bloodGroup') ? ' has-error' : '' }}">
                        <label class="form-label">Blood Group</label>
                        <select name="bloodGroup" class="form-select">
                            @foreach($bloodGroups as $bloodGroup)
                                <option value="{{$bloodGroup}}" @if(strcmp($bloodGroup,old('bloodGroup'))==0) selected @endif>
                                    {{$bloodGroup}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('bloodGroup'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('bloodGroup') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('allergies') ? ' has-error' : '' }}">
                        <label class="form-label">Known Allergies</label>
                        <textarea class="form-control" name="allergies" rows="2">{{old('allergies')}}</textarea>
                        @if ($errors->has('allergies'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('allergies') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('familyHistory') ? ' has-error' : '' }}">
                        <label class="form-label">Family History</label>
                        <textarea class="form-control" placeholder="Notable medical conditions run in the family"
                                  name="familyHistory" rows="2">{{old('familyHistory')}}</textarea>
                        @if ($errors->has('familyHistory'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('familyHistory') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('medicalHistory') ? ' has-error' : '' }}">
                        <label class="form-label">Medical History</label>
                        <textarea class="form-control" rows="2" name="medicalHistory">{{old('medicalHistory')}}</textarea>
                        @if ($errors->has('medicalHistory'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('medicalHistory') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('postSurgicalHistory') ? ' has-error' : '' }}">
                        <label class="form-label">Post Surgical History</label>
                        <textarea class="form-control" rows="2"
                                  name="postSurgicalHistory">{{old('postSurgicalHistory')}}</textarea>
                        @if ($errors->has('postSurgicalHistory'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('postSurgicalHistory') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3{{ $errors->has('remarks') ? ' has-error' : '' }}">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" rows="2" name="remarks">{{old('remarks')}}</textarea>
                        @if ($errors->has('remarks'))
                            <div class="invalid-feedback d-block">
                                <strong>{{ $errors->first('remarks') }}</strong>
                            </div>
                        @endif
                    </div>

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div><!-- /.modal-footer -->
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@if(session('type') && session('type')==="patient" && $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addPatientModal = new bootstrap.Modal(document.getElementById('addPatientModal'));
            addPatientModal.show();
        });
    </script>
@endif
