<?php

namespace App\Http\Controllers;

use App\Models\GoogleAdsenseCode;
use App\Http\Requests\CreateGoogleAdsenseCodeRequest;
use App\Http\Requests\UpdateGoogleAdsenseCodeRequest;

class GoogleAdsenseController extends Controller
{
     public function index()
    {
        $codes = GoogleAdsenseCode::latest()->paginate();
        //TODO: Add index adsense view
        return view('panel.admin.google_adsense_codes', compact('codes'));
    }

    public function show(GoogleAdsenseCode $googleAdsenseCode)
    {
        //TODO: Add show adsense view 
        return view('panel.admin.google_adsense_codes.edit', compact('googleAdsenseCode'));
    }
    public function store(CreateGoogleAdsenseCodeRequest $request)
    {
        auth()->user()->googleAdsensecodes()->create($request->validated());

        return back()->with(['message' => 'Adsense Code created succesfully', 'type' => 'success']);
    }

    public function update(UpdateGoogleAdsenseCodeRequest $request, GoogleAdsenseCode $googleAdsenseCode)
    {
        $googleAdsenseCode->update($request->validated());

        return back()->with(['message' => 'Adsense Code updated succesfully', 'type' => 'success']);
    }

    public function destroy(GoogleAdsenseCode $googleAdsenseCode)
    {

        if($googleAdsenseCode->user_id != auth()->id()){
            return back()->with(['message' => 'This action is unauthorized', 'type' => 'error']);
        }
        
        $googleAdsenseCode->delete();

        return back()->with(['message' => 'Adsense Code deleted succesfully', 'type' => 'success']);
    }
}
