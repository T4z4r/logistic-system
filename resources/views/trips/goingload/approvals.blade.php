    {{-- start of approval  modal --}}
    <div id="trip-approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Approve this request ?</h6>
                    <form action="{{ url('trips/approve-trip') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="approval-id2" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="approve_yes" class="btn btn-main btn-sm px-4 ">Yes</button>

                                <button type="button" id="approve_no" class="btn btn-danger btn-sm  px-4 text-light"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>

                    </form>


            </div>
            </modal-body>
            <modal-footer>

            </modal-footer>


        </div>
    </div>
    </div>
    {{-- end of approval modal --}}


    {{-- start of disapproval  modal --}}
    <div id="trip-disapproval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Disapprove this request ?</h6>
                    <form action="{{ url('trips/disapprove-trip') }}" id="disapprove_form" method="post">
                        @csrf
                        <input name="allocation_id" id="approval-id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="disapprove_yes" class="btn btn-main btn-sm px-4 ">Yes</button>

                                <button type="button" id="disapprove_no" class="btn btn-danger btn-sm  px-4 text-light"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>

                    </form>


            </div>
            </modal-body>
            <modal-footer>

            </modal-footer>


        </div>
    </div>
    </div>
    {{-- end of disapproval modal --}}
    <script>
        $(document).on('click', '.edit-button', function() {
            $('#approval-name').empty();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            $('#approval-id').val(id);
            $('#approval-id2').val(id);
            $('#approval-name').append(name);
            $('#approval-description').val(description);
        });
    </script>
    {{-- For Approving --}}
    <script>
        $("#approve_form").submit(function(e) {
            $("#approve_yes").html("<i class='ph-spinner spinner me-2'></i> Approving")
                .addClass('disabled');
            $("#approve_no").hide();
        });
    </script>

    {{-- For Disapproving --}}
    <script>
        $("#disapprove_form").submit(function(e) {
            $("#disapprove_yes").html("<i class='ph-spinner spinner me-2'></i> Disapproving")
                .addClass('disabled');
            $("#disapprove_no").hide();
        });
    </script>

