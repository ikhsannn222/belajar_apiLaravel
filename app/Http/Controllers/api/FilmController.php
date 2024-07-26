<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use Storage;
use Validator;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = Film::with(['genre','actor'])->get();
        return  response()->json([
            'succes'=> true,
            'message'=> 'Data Film',
            'data'=> $films,
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'judul'=> 'required|string|unique:films',
            'deskripsi'=> 'required|string',
            'foto'=> 'required|image|max:2048',
            'url_video'=> 'required|string',
            'id_kategori'=> 'required|',
            'genre'=> 'required',
            'actor'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes'=>false,
                'message'=>'Validasi Gagal',
                'errors'=> $validator->errors(),
            ], 422);
        }
        try {
            $path = $request->file('foto')->store('public/foto');

            $film = Film::create([
                'judul'=> $request->judul,
                'deskripsi'=> $request->deskripsi,
                'foto'=> $request->foto,
                'url_video'=> $request->url_video,
                'id_kategori'=> $request->id_kategori,
            ]);

            $film->genre()->attach($request->genre);
            $film->actor()->attach($request->actor);

            return response()->json([
                'succes'=>true,
                'message'=>'Data Berhasil Disimpan',
                'data'=> $film,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'succes'=>false,
                'message'=>'Terjadi Kesalahan',
                'errors'=> $e->getMessage(),
            ], 500);
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
        try {
            $film = Film::with(['genre','actor'])->findOrFail($id);
            return response()->json([
                'succes'=>true,
                'message'=> 'Detail Film',
                'data'=>$film,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'succes'=>false,
                'message'=>'Data Tidak Ditemukan',
                'errors'=> $e->getMessage(),
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
        $film = Film::findOrFail($id);

        $validator = Validator::make($request->all(),  [
            'judul'=> 'required|string|unique:films',
            'deskripsi'=> 'required|string',
            'foto'=> 'required|image|max:2048',
            'url_video'=> 'required|string',
            'id_kategori'=> 'required|exists:kategoris,id',
            'genre'=> 'required|array',
            'actor'=> 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes'=>false,
                'message'=>'Validasi Gagal',
                'errors'=> $validator->errors(),
            ], 422);
        }
        try {
            if ($request->hashFile('foto')) {

                storage::delete($film->foto);

                $path = $request->file('foto')->store('public/foto');
                $film->foto = $path;
            }

            $film->update($request->only(['judul','deskripsi','url_video','id_kategori']));

            if ($request->has('genre')){
                $film->genre()->sync($request->genre);
            }
            if ($request->has('actor')){
                $film->actor()->sync($request->actor);
            }
            return response()->json([
                'succes'=>true,
                'message'=> 'Data Berhasil Diperbarui',
                'data'=> $film,
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'succes'=>false,
                'message'=>'An Error occured',
                'errors'=> $e->getMessage(),
            ], 500);
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
        try {
            $film = Film::findOrFail($id);

            Storage::delete($film->foto);

            $film->delete();

            return response()->json([
                'succes'=>true,
                'message'=>'Data Delete Succesfully',
                'data'=> null,
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'succes'=>false,
                'message'=>'Data Not Found',
                'errors'=> $e->getMessage(),
            ], 404);
        }
    }
}
