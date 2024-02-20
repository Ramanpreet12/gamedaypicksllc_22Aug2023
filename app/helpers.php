<?php

use Illuminate\Support\Str;

use App\Models\UserTeam;
use App\Models\Season;

if (!function_exists('get_main_menus')) {

    function get_main_menus($id){

        $menuData = Illuminate\Support\Facades\DB::table('menus')->where('parent_id' , '=' , $id)->count();

        if($menuData > 0){

            return "dropdown-toggle";

        }

        else{

            return "";

        }

    }

}



if (!function_exists('get_main_submenus')) {

    function get_main_submenus($id){

        $menuData = Illuminate\Support\Facades\DB::table('menus')->where('parent_id' , '=' , $id)->count();

        if($menuData > 0){

            return "dropdown";

        }

        else{

            return "";

        }

    }

}



if (!function_exists('general_images')) {

    function general_images($img_file)

   {

       if ($img_file) {

           if(is_file($img_file)){

               $filename = $img_file->getClientOriginalName();

               $image_file_name = str_replace( " ", "-", $filename );

               $img_file->storeAs('public/images/general/' , $image_file_name);

               return $image_file_name;

           }else{

               return false;

           }

       }



   }

}

// if (!function_exists('update_userPoints')) {

//     function update_userPoints($team_id,$season_id , $week){

//         Illuminate\Support\Facades\DB::table('user_teams')->where(['week'=>$week, 'season_id'=>$season_id, 'team_id' => $team_id])->update(['points'=>1]);

//     }



// }

if (!function_exists('isSelected')) {

    function isSelected($season=null, $week=null, $team=null)

    {

       if(($season && $week && $team) != null){

         $selected = Illuminate\Support\Facades\DB::table('user_teams')->where(['user_id'=>auth()->user()->id,'season_id'=>$season,'week'=>$week,'team_id'=>$team])->first();

         if($selected){

            return true;

         }else{

            return false;

         }

       }

    }

}

if (!function_exists('get_team_name')) {

    function get_team_name($team=null)

    {

       if($team != null){

         $selected = Illuminate\Support\Facades\DB::table('teams')->where('id',$team)->first();

         if($selected){

           return $selected->name;

         }else{

            return '';

         }

       }

    }

}



if (!function_exists('get_team_logo')) {

    function get_team_logo($team=null)

    {

       if($team != null){

         $selected = Illuminate\Support\Facades\DB::table('teams')->where('id',$team)->first();

         if($selected){

           return $selected->logo;

         }else{

            return '';

         }

       }

    }

}





if (!function_exists('key_value')) {
    function key_value($key, $value, $ar)
    {
        $ret = [];
        foreach($ar as $k => $v) {
            $ret[$v[$key]] = $v[$value];
        }
        return $ret;
    }
}


    //get selected teams by users from user teams
if (!function_exists('get_selected_teams')) {
    function get_selected_teams($team_id , $season_id , $fixture_id , $week){
    //   dd(Auth::check());
        //
        // get current season
        $get_season = Season::where('status' , 'active')->first();

        if (Auth::check()) {
            // $ut = UserTeam::where(['user_id'=>Auth::user()->id, 'team_id'=>$team_id, "season_id"=> $get_season->id, 'fixture_id'=>$fixture_id,'week'=>$week])->first();
            $ut = UserTeam::where(['user_id'=>Auth::user()->id, 'team_id'=>$team_id, "season_id"=> $season_id, 'fixture_id'=>$fixture_id,'week'=>$week])->first();

            // dd($ut);
            return true;
        } else {
            return false;
        }




        // if ($ut) {
        //   return true;
        // }else{
        //     return false;
        // }
    }
}





 // custom function to unlink image or video from db
 if (!function_exists('unlink_image_video_from_db')) {
    function unlink_image_video_from_db($storage_path , $image_or_video){
        $image_video_to_be_deleted = $storage_path.$image_or_video;
      if (file_exists( $image_video_to_be_deleted)) {
       unlink( $image_video_to_be_deleted);
      }
        return $image_video_to_be_deleted;
    }
}

