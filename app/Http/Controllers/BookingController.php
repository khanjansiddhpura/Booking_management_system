<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index()
    {
        return view('bookings.index');
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_type' => 'required|in:Full Day,Half Day,Custom',
            'booking_slot' => 'nullable|in:First Half,Second Half|required_if:booking_type,Half Day',
            'booking_from_time' => 'nullable|date_format:H:i|required_if:booking_type,Custom',
            'booking_to_time' => 'nullable|date_format:H:i|required_if:booking_type,Custom|after:booking_from_time',
        ]);

        if ($validated['booking_type'] === 'Full Day') {
            $validated['booking_from_time'] = '00:00';
            $validated['booking_to_time'] = '23:59';
            $validated['booking_slot'] = null;
        }

        elseif ($validated['booking_type'] === 'Half Day') {
            if ($validated['booking_slot'] === 'First Half') {
                $validated['booking_from_time'] = '00:00';
                $validated['booking_to_time'] = '12:00';
            } else {
                $validated['booking_from_time'] = '12:00';
                $validated['booking_to_time'] = '23:59';
            }
        }

        elseif ($validated['booking_type'] === 'Custom') {
            $validated['booking_slot'] = null;
        }

        $validated['user_id'] = auth()->id();

        $errors = Booking::where('booking_date', $validated['booking_date'])
            ->where(function ($query) use ($validated) {
                $query->where('booking_from_time', '<', $validated['booking_to_time'])
                    ->where('booking_to_time', '>', $validated['booking_from_time']);
            })
            ->exists();

        if ($errors) {
            return back()
                ->withErrors(['booking_date' => 'This booking conflicts with an existing booking.'])
                ->withInput();
        }

        Booking::create($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }


    public function myBookings()
    {
        return view('bookings.my_bookings');
    }

    public function getMyBookingsData()
    {
        $bookings = Booking::where('user_id', auth()->id())
                          ->orderBy('booking_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->select('bookings.*');

        return DataTables::of($bookings)
            ->addColumn('booking_from_time', function($booking) {
                return $booking->booking_from_time ?? '-';
            })
            ->addColumn('booking_to_time', function($booking) {
                return $booking->booking_to_time ?? '-';
            })
            ->addColumn('booking_slot', function($booking) {
                return $booking->booking_slot ?? '-';
            })
            ->addColumn('action', function($booking) {
                $editBtn = '<a href="'.route('bookings.edit', $booking->id).'" class="btn btn-primary btn-sm"> <i class="fas fa-edit"></i> </a>';
                $showBtn = '<a href="'.route('bookings.show', $booking->id).'" class="btn btn-info btn-sm"> <i class="fas fa-eye"></i> </a>';
                $deleteBtn = '<button class="btn btn-danger btn-sm delete-booking" data-id="'.$booking->id.'" data-url="'.route('bookings.destroy', $booking->id).'"><i class="fas fa-trash"></i></button>';

                return $editBtn . ' ' . $showBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function editBooking($id)
    {
        $booking = Booking::find($id);
        return view('bookings.edit', compact('booking'));
    }

    public function updatebooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.my-bookings')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_type' => 'required|in:Full Day,Half Day,Custom',
            'booking_slot' => 'nullable|in:First Half,Second Half|required_if:booking_type,Half Day',
            'booking_from_time' => 'nullable|date_format:H:i|required_if:booking_type,Custom',
            'booking_to_time' => 'nullable|date_format:H:i|required_if:booking_type,Custom|after:booking_from_time',
        ]);

        if ($validated['booking_type'] === 'Full Day') {
            $validated['booking_from_time'] = '00:00';
            $validated['booking_to_time'] = '23:59';
            $validated['booking_slot'] = null;
        }

        elseif ($validated['booking_type'] === 'Half Day') {
            if ($validated['booking_slot'] === 'First Half') {
                $validated['booking_from_time'] = '00:00';
                $validated['booking_to_time'] = '12:00';
            } else {
                $validated['booking_from_time'] = '12:00';
                $validated['booking_to_time'] = '23:59';
            }
        }

        elseif ($validated['booking_type'] === 'Custom') {
            $validated['booking_slot'] = null;
        }

        $errors = Booking::where('booking_date', $validated['booking_date'])
            ->where('id', '!=', $id)
            ->where(function ($query) use ($validated) {
                $query->where('booking_from_time', '<', $validated['booking_to_time'])
                      ->where('booking_to_time', '>', $validated['booking_from_time']);
            })
            ->exists();

        if ($errors) {
            return back()
                ->withErrors(['booking_date' => 'This booking conflicts with an existing booking.'])
                ->withInput();
        }

        $booking->update($validated);

        return redirect()->route('bookings.my-bookings')->with('success', 'Booking updated successfully!');
    }


    public function showBooking($id)
    {
        $booking = Booking::find($id);
        return view('bookings.show', compact('booking'));
    }

    public function deleteBooking($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found.'], 404);
        }

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        if ($booking->booking_date < now()) {
            return response()->json(['error' => 'You cannot delete a booking that has already passed.'], 400);
        }

        $booking->delete();
        return response()->json(['success' => 'Booking deleted successfully!'], 200);
    }
}
