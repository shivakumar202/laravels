<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\NewsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsManagementController extends Controller
{
    //
    public function index(NewsDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.channel-management.news.list');
    }

    public function manage()
    {
        return view('pages.apps.channel-management.news.actions.add_news');
    }
}
