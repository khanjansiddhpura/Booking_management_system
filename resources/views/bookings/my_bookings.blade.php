@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-calendar-check"></i> My Bookings
            </h1>
            <a href="{{ route('bookings.index') }}" class="btn btn-primary" style="display: inline-block;">Create New Booking</a>
        </div>

        <div class="table-responsive">
            <table id="bookings-table" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Booking Date</th>
                        <th>Booking Type</th>
                        <th>Booking Slot</th>
                        <th>Booking From Time</th>
                        <th>Booking To Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#bookings-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('bookings.my-bookings.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'customer_name', name: 'customer_name' },
                { data: 'customer_email', name: 'customer_email' },
                { data: 'booking_date', name: 'booking_date' },
                { data: 'booking_type', name: 'booking_type' },
                { data: 'booking_slot', name: 'booking_slot' },
                { data: 'booking_from_time', name: 'booking_from_time' },
                { data: 'booking_to_time', name: 'booking_to_time' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[2, 'desc']],
            pageLength: 10,
            responsive: true,
            language: {
                processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
            }
        });

        $('#bookings-table').on('click', '.delete-booking', function(e) {
            e.preventDefault();

            const bookingId = $(this).data('id');
            const deleteUrl = $(this).data('url');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Your booking has been deleted.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred while deleting the booking.';

                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }

                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
