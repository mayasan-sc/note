<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('index');
    }

    public function pages(){
      $pages = Page::orderBy('created_at', 'desc')->get();
      return response()->json([
        'pages'=>$pages
      ], 200);
    }

    public function store(Request $request)
    {
        $page = $request->validate([
                    'title'=>'required',
                    'content'=>'required'
                ]);
                
        Page::create($page);

        return response()->json([
            'page'=>$page
        ]);
    }

    public function edit(Request $request, $id)
    {
        $page = Page::find($request->id);
        $draft = $request->validate([
                    'title'=>'required',
                    'content'=>'required'
                ]);
        $page->fill($draft)->save();
        return response()->json([
            'page'=>$page
        ]);
    }

    public function delete(Request $request, $id)
    {
      $page = Page::find($request->id);
      $page->delete();
      return response()->json([
          'page'=>$page
      ]); 
    }
  
}