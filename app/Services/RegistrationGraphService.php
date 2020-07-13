<?php

namespace App\Services;


use App\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrationGraphService
{
    /**
     * Go through the process of creating a graph.
     *
     * @return mixed
     */
    public function retrieveGraph()
    {
        $registrations = $this->getLastMonthRegistrations();

        $durationsAndLabels = $this->getAllDurationsAndLabels($registrations);

       return $this->constructGraph($durationsAndLabels['datesLabels'], $durationsAndLabels['allDurations']);

    }

    /**
     * Get the registrations of the current user for the last month
     *
     * @return mixed
     */
    public function getLastMonthRegistrations()
    {
        return Registration::whereUserId(Auth::user()->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get all durations and labels that are needed for the chart.
     *
     * @param $registrations
     * @return array
     */
    public function getAllDurationsAndLabels($registrations)
    {
        $durationsAndLabels=[];
        $allDurations = [];
        $datesLabels = [];
        foreach ($registrations as $registration) {

            $hours = intdiv($registration->duration_minutes, 60) . '.' . ($registration->duration_minutes % 60);

            $allDurations[] = (float)$hours;

            $datesLabels[] = $registration->created_at->toDateString();
        }
        $durationsAndLabels['allDurations'] = $allDurations;
        $durationsAndLabels['datesLabels'] = $datesLabels;

        return $durationsAndLabels;
    }

    /**
     * Construct the graph blueprint.
     *
     * @param $datesLabels
     * @param $allDurations
     * @return mixed
     */
    public function constructGraph($datesLabels, $allDurations)
    {
        return app()->chartjs
            ->name('lineChartTest')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($datesLabels)
            ->datasets([
                [
                    "label" => Auth::user()->name . ' time in hours and minutes',
                    'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                    'borderColor' => "rgba(38, 185, 154, 0.7)",
                    "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                    "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => $allDurations,
                ],
            ])
            ->options([]);
    }
}
