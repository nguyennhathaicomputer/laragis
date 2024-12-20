<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    // Show all Listings
    public function index(){
       
        return view('listings.index',[
            'listings' =>Listing::latest()->paginate(2)
            
        ]);
    }

    //show a listing
    public function show(Listing $listing){
        return view('listings.show', [
            'listing'=>$listing
        ]);
    }


    //show create form
    public function create(){
        return view('listings.create');
    }

    public function store(Request $request){
        $formField = $request->validate([
            'title'=>'required',
            'company'=>['required', Rule::unique('listings', 'company')],
            'location'=>'required',
            'email'=>['required','email'],
            'website'=>'required',
            'tags' =>'required', 
            'description' =>'required'
        ]);

        if($request->hasfile('logo')){
            $formField['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formField['user_id'] = auth()->id();
        Listing::create($formField); 
        return redirect('/')->with('message', 'Listing created successfully!');
    }


    public function edit(Listing $listing){
        
        return view('listings.edit', ['listing'=>$listing]);
    }

    public function update(Request $request,Listing $listing){

        if($listing->user_id!=auth()->id()){
            abort(403, "Unauthorized Action");
        }

        $formField = $request->validate([
            'title'=>'required',
            'company'=>['required'],
            'location'=>'required',
            'email'=>['required','email'],
            'website'=>'required',
            'tags' =>'required', 
            'description' =>'required'
        ]);
        
        if($request->hasfile('logo')){
            $formField['logo'] = $request->file('logo')->store('logos', 'public');
        }

        
        $listing->update($formField);
        return back()->with('message', 'Listing update successfully!');
    }


    public function destroy(Listing $listing){
        if($listing->user_id!=auth()->id()){
            abort(403, "Unauthorized Action");
        }
        $listing->delete();
        return redirect('/')->with('message', 'Delete Listing Successfully!');

    }

    public function manage(){
        return view('listings.manage', ['listings'=>auth()->user()->listings()->get()]);

    }

    public function searchJobs(Request $request){
        $searchTerm = $request->input('search');
        $listings = Listing::select('*')->where('title', 'LIKE', "%{$searchTerm}%")
                                        ->orwhere('tags', 'LIKE', "%{$searchTerm}%")
                                        ->latest()->paginate(10)->appends(['search' => $searchTerm]);
        
        return view('listings.index', ['listings'=>$listings,'searchTerm'=>$searchTerm]);
    }
    
}
