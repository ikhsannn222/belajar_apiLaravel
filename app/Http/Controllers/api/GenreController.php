<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Validator;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genre = Genre::latest()->get();
        $response = [
            'succes'=>true,
            'message'=>'Data actor',
            'data'=> $genre,
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'nama_genre'=>'required|unique:genres',
        ],[
            'nama_genre.required'=>'Masukan genre',
            'nama_genre.unique'=>'genre Sudah digunakan',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 400);
        } else {
            $genre = new Genre;
            $genre->nama_genre = $request->nama_genre;
            $genre->save();
        }

        if($genre) {
            return response()->json([
                'succes'=>true,
                'message'=> 'data berhasil disimpan',
            ], 201);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=>'data gagal disimpan',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $genre = Genre::find($id);

        if($genre){
            return response()->json([
                'succes'=>true,
                'message'=> 'Detail genre',
                'data'=> $genre,
            ], 200);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=> 'Actor Tidak Di temukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // validate data
         $validator = Validator::make($request->all(), [
            'nama_genre'=>'required',
        ],[
            'nama_genre.required'=>'Masukan genre',
        ]);

        if($validator->fails()){
            return response()->json([
                'succes'=>false,
                'message'=> 'Silahkan isi dengan benar',
                'data'=> $validator->errors(),
            ], 401);
        } else {
            $genre = Genre::find($id);
            $genre->nama_genre = $request->nama_genre;
            $genre->save();
        }

        if($genre) {
            return response()->json([
                'succes'=>true,
                'message'=> 'data berhasil diperbarui',
            ], 200);
        } else {
            return response()->json([
                'succes'=>false,
                'message'=>'data gagal diperbarui',
            ], 401);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);
        if($genre){
        $genre->delete();
        return response()->json([
            'succes'=>true,
            'message'=>'data ' .$genre->nama_genre . ' Berhasil dihapus',
        ], 200);

        } else {
            return response()->json([
            'succes'=>false,
            'message'=>'data tidak di temukan'
            ], 404);
        }

    }
}
