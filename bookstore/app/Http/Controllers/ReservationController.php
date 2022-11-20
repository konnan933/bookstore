<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){
        $reservations =  Reservation::all();
        return $reservations;
    }

    public function show ($book_id, $user_id, $start)
    {
        $reservation = Reservation::where('book_id', $book_id)->where('user_id', $user_id)->where('start', $start)->get();
        return $reservation[0];
    }
    public function destroy($book_id, $user_id, $start)
    {
        ReservationController::show($book_id, $user_id, $start)->delete();
    }

    public function store(Request $request)
    {
        $reservation = new Reservation();
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->save();
    }

    public function update(Request $request, $book_id, $user_id, $start)
    {
        $reservation = ReservationController::show($book_id, $user_id, $start);
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->save();
    }

}
