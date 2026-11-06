<?php
namespace App\Http\Controllers;
use App\Models\Equipment;

class HomeController extends Controller {
    public function index() {
        $featured = Equipment::with('category')->where('status','active')->take(4)->get();
        return view('home', compact('featured'));
    }
}