<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations =  Reservation::all();
        return $reservations;
    }

    public function show($book_id, $user_id, $start)
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
        $reservation->message = $request->message;
        $reservation->save();
    }

    public function update(Request $request, $book_id, $user_id, $start)
    {
        $reservation = ReservationController::show($book_id, $user_id, $start);
        $reservation->book_id = $request->book_id;
        $reservation->user_id = $request->user_id;
        $reservation->start = $request->start;
        $reservation->message = $request->message;
        $reservation->save();
    }

    public function older($day)
    {
        $user = Auth::user();
        $reservations = DB::table('reservations as r')
            ->select('r.book_id', 'r.start')
            ->where('r.user_id', $user->id)
            ->whereRaw('DATEDIFF(CURRENT_DATE, r.start) > ?', $day)
            ->get();

        return $reservations;
    }

    // raw : select r.user_id, count(*) from reservations r group by r.user_id having count(*) > 1

    public function elojegyzesCount()
    {
        $user = Auth::user();
        $reservations = DB::table('reservations as r')
            ->where('r.user_id', $user->id)
            ->count();

        return $reservations;
    }
}
