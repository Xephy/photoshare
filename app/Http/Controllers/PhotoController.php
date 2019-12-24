<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(StorePhoto $request)
    {
        $extension = $request->photo->extension();

        $photo = new Photo();

        $photo->filename = $photo->id . '.' . $extension;

        Storage::disk('local')->putFileAs('', $request->photo, $photo->filename, 'public');

        DB::beginTransaction();

        try
        {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch(Exception $exception)
        {
            DB::rollBack();
            Storage::disk('local')->delete($photo->filename);
            throw $extension;
        }

        return response($photo, 201);
    }
}
