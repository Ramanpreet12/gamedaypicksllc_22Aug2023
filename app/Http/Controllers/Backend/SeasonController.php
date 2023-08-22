<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Support\Carbon;
use App\Http\Requests\SeasonRequest;
class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $seasons= Season::get();
        return view('backend.season.index' , compact('seasons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('backend.season.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request)
    {

        if ($request->isMethod('post')) {

            $starting_date= Carbon::parse($request->starting)->format('Y-m-d');
             $ending_date = Carbon::parse($request->ending)->format('Y-m-d');
            Season::create([
                'season_name' => $request->season_name,
                'league' => $request->league,
                'starting' => $starting_date,
                'ending' => $ending_date,
                'season_amount' => $request->season_amount,
                'status' => $request->status
              ]);
              return redirect()->route('season.index')->with('success' , 'Season Created successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $season = Season::find($id);

        return view('backend.season.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonRequest $request, $id)
    {
        if($request->isMethod('put')){

            $starting_date= Carbon::parse($request->starting)->format('Y-m-d');
            $ending_date = Carbon::parse($request->ending)->format('Y-m-d');

        Season::where('id' , $id)->update([
            'season_name' => $request->season_name,
            'league' => $request->league,
            'starting' => $starting_date,
            'ending' => $ending_date,
            'season_amount' => $request->season_amount,
            'status' => $request->status,
        ]);
        return redirect()->route('season.index')->with('success' , 'Season Updated successfully');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $del = Season::find($id)->delete();

        if ($del) {
            return redirect()->route('season.index')->with('success', 'Season Deleted Successfully');
        } else {
            return redirect()->route('season.index')->with('error_message', 'Something went wrong');
        }
    }

}
