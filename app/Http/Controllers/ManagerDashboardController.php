<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Kategori;
use App\Charts; // Gunakan model yang benar
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tbl_menu = Menu::count();
        $tbl_kategori = Kategori ::count();
        return view('managerdashboard',compact('tbl_menu','tbl_kategori'));
    }

    public function showChart()
    {
        // Mengambil data dari database menggunakan Eloquent atau Query Builder
        $data = Charts::whereIn('jenis_pesanan', ['Makan di Tempat', 'Bawa Pulang'])
            ->groupBy('jenis_pesanan')
            ->select('jenis_pesanan', DB::raw('count(*) as jumlah'))
            ->get();

        // Format data untuk JavaScript
        $dataArray = $data->map(function ($row) {
            return [$row->jenis_pesanan, (float)$row->jumlah];
        })->toArray();

        return view('managerdashboard', compact('dataArray'));
    }
}
