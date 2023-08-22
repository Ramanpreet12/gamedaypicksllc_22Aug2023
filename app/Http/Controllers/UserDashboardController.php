<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use App\Models\Fixture;
use App\Models\UserTeam;
use App\Models\Season;
use App\Models\Winner;
use Auth , Hash,PDF;
use Cache;


class UserDashboardController extends Controller
{
    public function dashboard()
    {
        // $c_date = Carbon::now();
        // $c_season = DB::table('seasons')
        //     ->whereRaw('"' . $c_date . '" between `starting` and `ending`')
        //     ->first();

        $c_date = Season::where('status' , 'active')->value('starting');
        $c_season = DB::table('seasons')
            ->whereRaw('"' . $c_date . '" between `starting` and `ending`')
            ->where('status' , 'active')->first();


        $payment = Payment::where('user_id', auth()->user()->id)->latest()->take(5)->get();
        $user = DB::table('user_teams')->join('teams', 'teams.id', 'user_teams.team_id')->where(['user_id' => auth()->user()->id, 'season_id' => $c_season->id])->select('teams.name', 'teams.logo', 'user_teams.*')->orderby('user_teams.week', 'desc')->latest()->take(3)->get();
        $upcoming = Fixture::with('first_team_id','second_team_id')
        ->where('season_id',$c_season->id)
        // ->whereDate('date','>=',$c_date)
        ->orderby('date','asc')->latest()->take(5)->get()->groupby('week');
      
        $get_prizes = Winner::with('prize' , 'season')->where('user_id', auth()->user()->id)->orderBy('id' , 'desc')->get();


         return view('front.dashboard', compact('user', 'payment','upcoming','get_prizes'));
        //return view('front.test',compact('user', 'payment','upcoming','prize'));
    }
    public function userPayment()
    {
        $payment = Payment::with('season')->where('user_id', auth()->user()->id)->paginate(6);
        return view('front.payment', compact('payment'));
    }

    public function my_selections()
    {
        $my_selections = DB::table('user_teams')
         ->select('f.week As fweek' ,'f.date as fdate' ,'f.time as ftime' ,'f.time_zone as ftime_zone' ,'f.id','f.win As team_win','f.loss As team_loss','t.logo As team_logo', 't.name As user_team', 's.season_name As season_name','t1.name As first_name','t1.logo As first_logo','t2.name As second_name','t2.logo As second_logo','user_teams.points As user_point')
        ->join('teams as t', 't.id', '=', 'user_teams.team_id')
        ->join('seasons as s', 's.id', '=', 'user_teams.season_id')
        ->join('fixtures as f', 'f.id', '=', 'user_teams.fixture_id')
         ->join('teams as t1', 't1.id', '=', 'f.first_team')
        ->join('teams as t2', 't2.id', '=', 'f.second_team')
         ->where('user_id', auth()->user()->id)
        ->orderby('user_teams.week', 'desc')->get()->groupby('fweek');
        // $c_date = Season::where('status' , 'active')->value('starting');
        $get_current_year = Carbon::now()->format('Y');
        $get_current_season = Season::where(['status'=>'active' , 'season_name' => $get_current_year])->first();

        // $c_date = Season::where('status' , 'active')->value('starting');
         $c_date = $get_current_season->starting;

        $c_season = DB::table('seasons')->whereRaw('"' . $c_date . '" between `starting` and `ending`')
                ->where('status' , 'active')->first();
           $season_name = $c_season->season_name;



        return view('front.myselections', compact('my_selections' , 'season_name'));
    }

    public function past_selections()
    {
        $past_selections = DB::table('user_teams')
         ->select('f.time As ftime' ,'f.time_zone As tformat' ,'f.date As fdate','f.week As fweek','f.id','f.win As team_win','f.loss As team_loss','t.logo As tlogo' , 't.name As user_team', 's.season_name As season_name','t1.name As first_name','t1.logo As first_logo','t2.name As second_name','t2.logo As second_logo','user_teams.points As user_point')
        ->join('teams as t', 't.id', '=', 'user_teams.team_id')
        ->join('seasons as s', 's.id', '=', 'user_teams.season_id')
        ->join('fixtures as f', 'f.id', '=', 'user_teams.fixture_id')
         ->join('teams as t1', 't1.id', '=', 'f.first_team')
        ->join('teams as t2', 't2.id', '=', 'f.second_team')
         ->where('user_id', auth()->user()->id)
         ->whereNotNull(['f.win' , 'f.loss'])
        ->orderby('user_teams.week', 'desc')->get()->groupby('fweek');

        // $c_date = Season::where('status' , 'active')->value('starting');
        $get_current_year = Carbon::now()->format('Y');
        $get_current_season = Season::where(['status'=>'active' , 'season_name' => $get_current_year])->first();

        // $c_date = Season::where('status' , 'active')->value('starting');
         $c_date = $get_current_season->starting;
         
            $c_season = DB::table('seasons')->whereRaw('"' . $c_date . '" between `starting` and `ending`')
                    ->where('status' , 'active')->first();
               $season_name = $c_season->season_name;

        // $get_season_name = $past_selections->season_name;
        // dd($season_name);
        return view('front.past_selections', compact('past_selections' , 'season_name'));
    }

    public function upcomingMatches()
    {
        // $c_date = Carbon::now();
        // $c_season = DB::table('seasons')
        //     ->whereRaw('"' . $c_date . '" between `starting` and `ending`')
        //     ->first();
        $c_date = Season::where('status' , 'active')->value('starting');
        $c_season = DB::table('seasons')
            ->whereRaw('"' . $c_date . '" between `starting` and `ending`')
            ->where('status' , 'active')->first();
        $upcoming = Fixture::with('first_team_id','second_team_id')->where('season_id',$c_season->id)->whereDate('date','>',$c_date)->get()->groupby('week');
        // echo "<pre>";
        // print_r( $upcoming);
        return view('front.upcoming',compact('upcoming'));
    }

    public function settings(Request $request)
    {
        if ($request->isMethod('put')) {

            $data = array();
                $image     =   $request->file('photo');
                if ($image) {
                    $filename =   $image->getClientOriginalName();
                    $success = $image->storeAs('public/images/user_images/' , $filename);
                    if (!isset($success)) {
                        return back()->withError('Could not upload Banner');
                    }
                    $data["photo"]=$filename;
                }

                $data["name"]=$request->name;
                $data["dob"]=$request->dob;
                $data["phone_number"]=$request->phone;
                $update_user =User::where('id', Auth::user()->id)->update($data);
                if(Cache::has('leader_board_regions_wise_users_results')){
                    Cache::forget('leader_board_regions_wise_users_results');
                }
                return redirect()->back()->with('success' , 'User updated successfully');
            }
        else {
            $get_user_details = User::where('id' , Auth::user()->id)->get();
            return view('front.settings.personal_details' , compact('get_user_details'));
        }


    }

    public function updatePassword(Request $request)
    {
        if ($request->isMethod('put')) {

            $input = $request->all();
            $request->validate([
                'new_password' => 'required|min:6|regex:/[a-z]/',
            ],
            [
             'new_password.required'=> 'New Password field is Required', // custom message
            ]
         );

            if (!(Hash::check($request->current_password ,Auth::user()->password))) {
                // return redirect()->back()->with('pass_message_error' , 'Current password is incorrect');
                return response()->json(['message' => 'Current password is incorrect' , 'status' => false ], 200);
            }
            if (($request->current_password === $request->new_password)) {
                return response()->json(['message' => 'New Password cannot be same as your current password' , 'status' => false ], 200);

            //    return redirect()->back()->with('pass_message_error' , 'New Password cannot be same as your current password');
            }
            if (($request->new_password != $request->confirm_password)) {
                // return redirect()->back()->with('pass_message_error' , 'Password not matched');
                return response()->json(['message' => 'Password not matched' , 'status' => false ], 200);

             }

            User::where(['id' => Auth::user()->id , 'role_as' => 0])->update(['password' => bcrypt($request->new_password)]);
            // return redirect()->back()->with('success' , 'Password updated successfully');
            return response()->json(['message' => 'Password updated successfully' , 'status' => true ], 200);


        } else {
            return view('front.settings.update_password');
        }


    }


    public function invoice($id)
    {
        try {
            $order = DB::table('payments')->join('addresses', 'addresses.payment_id','=', 'payments.id')->join('seasons', 'seasons.id','=', 'payments.season_id')->where(['payments.id' => $id])->select('seasons.season_name','payments.*','addresses.name','addresses.address','addresses.city','addresses.country','addresses.zip')->first();
            $invoice_date = date('jS F Y', strtotime($order->created_at));
            return view('backend.users.invoice', compact('order'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
