<?php

namespace Modules\Scouting\Processors\SideEffects\Tennis;

use Modules\Scouting\Exceptions\MatchAlreadyHasAWinnerException;
use Modules\Scouting\Entities\ScoutingActivity;
use Modules\Scouting\Processors\Statistic;
use Closure;

class Errors
{
    const ERROR_SIDE_EFFECT = 'ERROR_SIDE_EFFECT';

    /**
     * Processor used for calculate the score
     * for own and rival team
     *
     * @param Statistic $stats
     * @param Closure $next
     * @return Closure
     */
    public function handle(Statistic $stats, Closure $next)
    {
        $activities = $stats->activitiesOrderedByTime();

        try {
            $activities->each(function($activity) use ($stats){
                if ($this->point($activity)) {
                    if ($activity->action->rival_team_action) {
                        $stats->score->addToOwn(1);
                    } else {
                        $stats->score->addToRival(1);
                    }
                }
            });
        } catch (MatchAlreadyHasAWinnerException $exception) {
            return $next($stats);
        }

        return $next($stats);
    }

    /**
     * Determinate if the current activity is a point activity
     *
     * @param ScoutingActivity $activity
     * @return bool
     */
    public function point($activity)
    {
        $side_effect = $activity->action->side_effect;

        return $side_effect == self::ERROR_SIDE_EFFECT;
    }
}