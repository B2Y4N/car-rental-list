<!DOCTYPE html>
<html>
    <head>
        <title>Car Rental List</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <style>
            table#carList.dataTable thead tr th{
                background-color: #42aaac !important;
                color: white;
            }

            table#carList.dataTable tbody tr.highlight td{
                background-color: #fcbb16 !important;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row mb-3">
                <h2>Car List</h2>
            </div>
            <div class="row mb-3">
                <div class="col-sm-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Start Date</span>
                        <input type="date" class="form-control" id="startDate" name="startDate" onchange="setMinDate()">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">End Date</span>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                    </div>
                </div>
                <div class="col-sm-2">
                    <p class="d-inline-flex gap-1">
                        <button type="button" class="btn btn-primary btn-sm" onclick="customFilter()">Filter</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilter()">Clear</button>
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="carList" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Car Plate No.</th>
                                <th>Colour</th>
                                <th>Propellant</th>
                                <th>Seats</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- START add car modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Add New Car</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('car.store') }}" method="POST" class="was-validated">
                        @csrf
                        <div class="mb-3">
                            <label for="carPlate" class="form-label">Car Plate No.</label>
                            <input type="text" class="form-control" id="carPlate" name="carPlate" pattern="^[A-Z]{3}[\d]{3,4}[A-Z]{1}$" required>
                            <div class="invalid-feedback">Please enter correct format</div>
                        </div>
                        <div class="mb-3">
                            <label for="colour" class="form-label">Colour</label>
                            <input type="text" class="form-control" id="colour" name="colour" required>
                            <div class="invalid-feedback">Please enter a colour</div>
                        </div>
                        <div class="mb-3">
                            <label for="propellant" class="form-label">Propellant</label>
                            <select class="form-select" id="propellant" name="propellant" required>
                                <option value="" selected disabled>Please select one</option>
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="petrol">Petrol</option>
                            </select>
                            <div class="invalid-feedback">Please choose an option</div>
                        </div>
                        <div class="mb-3">
                            <label for="seats" class="form-label">Seats</label>
                            <select class="form-select" id="seats" name="seats" required>
                                <option value="" selected disabled>Please select one</option>
                                <option value="2">2</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                            <div class="invalid-feedback">Please choose an option</div>
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Expiry date</label>
                            <input type="date" class="form-control" id="expiryDate" name="expiryDate" required>
                            <div class="invalid-feedback">Please select a date</div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- END add car modal -->

        <!-- START edit car modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST" class="was-validated">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="editCarPlate" class="form-label">Car Plate No.</label>
                            <input type="text" class="form-control" id="editCarPlate" name="editCarPlate" pattern="^[A-Z]{3}[\d]{3,4}[A-Z]{1}$" required>
                            <div class="invalid-feedback">Please enter correct format</div>
                        </div>
                        <div class="mb-3">
                            <label for="editColour" class="form-label">Colour</label>
                            <input type="text" class="form-control" id="editColour" name="editColour" required>
                            <div class="invalid-feedback">Please enter a colour</div>
                        </div>
                        <div class="mb-3">
                            <label for="editPropellant" class="form-label">Propellant</label>
                            <select class="form-select" id="editPropellant" name="editPropellant" required>
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="petrol">Petrol</option>
                            </select>
                            <div class="invalid-feedback">Please choose an option</div>
                        </div>
                        <div class="mb-3">
                            <label for="editSeats" class="form-label">Seats</label>
                            <select class="form-select" id="editSeats" name="editSeats" required>
                                <option value="2">2</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                            <div class="invalid-feedback">Please choose an option</div>
                        </div>
                        <div class="mb-3">
                            <label for="editExpiryDate" class="form-label">Expiry date</label>
                            <input type="date" class="form-control" id="editExpiryDate" name="editExpiryDate" required>
                            <div class="invalid-feedback">Please select a date</div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- END edit car modal -->

        <!-- START delete car modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Car <span class="deleteCarPlate"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" action="" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="mb-3">
                            <p style="color: red;">Are you sure you want to delete this car(<span class="deleteCarPlate"></span>)?</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- END delete car modal -->
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> -->
    <script type="text/javascript">
        var startDate = "";
        var endDate = "";

        // $(function(){
            var table = $('#carList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('car.index') }}",
                    data: function(data){
                        data.startDate = startDate;
                        data.endDate = endDate;
                    },
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'carPlate', name: 'carPlate'},
                    {data: 'colour', name: 'colour'},
                    {data: 'propellant', name: 'propellant'},
                    {data: 'seats', name: 'seats'},
                    {data: 'expiryDate', name: 'expiryDate'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                createdRow: function(row, data, dataIndex){
                    // if(data.highlight){
                    //     $(row).addClass('highlight');
                    // }

                    var expiryDate = new Date(data.expiryDate);
                    var today = new Date(Date.now());

                    var timeDiff = expiryDate.getTime() - today.getTime();
                    var dayDiff = timeDiff / (1000 * 3600 * 24);

                    if(dayDiff <= 14 && dayDiff >= 0){
                        $(row).addClass('highlight');
                    }
                }
            });
        // });

        function editCar(carId){
            var showUrl = "{{ route('car.show', ':id') }}";
            showUrl = showUrl.replace(':id', carId);

            var editUrl = "{{ route('car.update', ':id') }}";
            editUrl = editUrl.replace(':id', carId);
            $.ajax({
                url: showUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('#editModal').modal('show');
                    $('#editForm').attr('action', editUrl);
                    $('#editCarPlate').val(data.carPlate);
                    $('#editColour').val(data.colour);
                    $('#editPropellant').val(data.propellant);
                    $('#editSeats').val(data.seats);
                    $('#editExpiryDate').val(data.expiryDate);
                }
            })
        };

        function deleteCar(carId, carPlate){
            var deleteUrl = "{{ route('car.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', carId);
            $('#deleteModal').modal('show');
            $('#deleteForm').attr('action', deleteUrl);
            $('.deleteCarPlate').text(carPlate);
        };

        function customFilter(){
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
            table.draw();
        };

        function clearFilter(){
            $('#startDate').val("");
            startDate = "";
            $('#endDate').val("");
            endDate = "";
            table.draw();
        }

        function setMinDate(){
            var minDate = $('#startDate').val();

            if(minDate > $('#endDate').val()){
                $('#endDate').val("");
            }

            $('#endDate').prop('min', minDate);
        }
    </script>
</html>