<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
    }
    public function store(Request $request)
    {
        $report = Report::create($request->all());
        return $report->load("user", "station");
    }
    public function show($id)
    {
        $report = Report::findOrFail($id);
        return $report->load("user", "station", "Comments.user");
    }
    public function update(Request $request, report $report)
    {
        $body = $request->input('body');
        $station_id = $request->input('station_id');
        $report->body = $body;
        $report->station_id = $station_id;
        $report->save();
        return $report;
    }
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
    }
}
