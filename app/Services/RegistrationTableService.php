<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;

class RegistrationTableService
{
    /**
     * Go through the process of creating the registrations table..
     *
     * @param $request
     * @return array|\Illuminate\Support\Collection
     */
    public function getRegistrationsForTable($request)
    {
        if ($request->input('showdata')) {
            $query = $this->getData();

            return $query->orderBy('created_at', 'desc')->get();
        }

        $columns = ['user_name', 'user_id', 'start_time', 'end_time'];
        $length = $request->input('length');
        $column = $request->input('column');
        $search_input = $request->input('search');

        $query = $this->getData();
        $query->orderBy($columns[$column]);

        if ($search_input) {
            $this->searchData($query, $search_input);
        }
        $users = $query->paginate($length);

        return ['data' => $users];
    }

    /**
     * Get registrations and related users.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getData()
    {
        return DB::table('users')
            ->join('registrations', 'users.id', '=', 'registrations.user_id')
            ->select(DB::raw('users.name AS user_name'), 'registrations.*');
    }

    /**
     * Apply search conditions.
     *
     * @param $query
     * @param $search_input
     * @return mixed
     */
    public function searchData($query, $search_input)
    {
        $query->where(function ($query) use ($search_input) {
            $query->where('user_name', 'like', '%' . $search_input . '%')
                ->orWhere('user_id', 'like', '%' . $search_input . '%')
                ->orWhere('start_time', 'like', '%' . $search_input . '%')
                ->orWhere('end_time', 'like', '%' . $search_input . '%');
        });

        return $query;
    }
}
