<?php

namespace App\Http\Middleware;

use App\Models\Blacklist;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserBlacklistMiddleware
{

    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $userID = auth()->user()->uid;
        $blacklist = Blacklist::where('uid', $userID)->where('status', 'enabled')->whereDate('updated_at', '>=', Carbon::now()->subMinutes(10))->get();

        if ($blacklist->isEmpty() || $blacklist[0]->tries < 3) {

            Blacklist::updateOrCreate(
                [
                    ['uid', $userID],
                    ['status', '<>', 'disabled'],
                ],
                [
                    'uid' => $userID,
                    'tries' => DB::raw('tries + 1'),
                ]
            );

            return $next($request);
        }
        $id = $blacklist[0]->id;

        $minutes = $this->calcMinutes($blacklist[0]);
        Log::info($minutes);

        DB::unprepared("CREATE EVENT process_blacklist ON SCHEDULE AT $minutes DO UPDATE blacklists SET status = 'disabled' WHERE id = '$id' ");


        return $this->error('You have Been Blacklisted, Check back again in 2hours', 401);
    }

    private function calcMinutes($blacklist)
    {
        $currentDate = strtotime(now());
        $createdTime = $blacklist->created_at;
        $userLastActivity = strtotime($createdTime);
        $timeDiffrence = round(abs($currentDate - $userLastActivity) / 60); //diffrence of time in minutes

        #120 mins = 2 hours
        $minutes = (5 - $timeDiffrence);
        if ($minutes <= 0) return 'CURRENT_TIMESTAMP';

        return "CURRENT_TIMESTAMP + INTERVAL {$minutes} MINUTE";
    }
}
