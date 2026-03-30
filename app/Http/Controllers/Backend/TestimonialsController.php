<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Testimonial;
use Validator;

use App\Models\Languages;
use App\Models\Translation;
use App\Traits\TranslateTrait;

class TestimonialsController extends Controller
{

    use TranslateTrait;

    public function __construct()
    {
        // Minor Middleware to Check if Module is Active....
        $this->middleware(function ($request, $next)
        {
            if(settings('show_testimonials'))
            {
                return $next($request);
            }
            else
            {
                $request->session()->flash('message_danger', 'You do not have access to this resource');
                return redirect('/admin');
            }

        });

        $this->middleware(['auth','admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $testimonials = Testimonial::orderBy('priority', 'ASC')
            // filter other data
            ->when($request->input('q'), function($query) use ($request){
              return $query->where(function ($query) use ($request) {
                      $query->where('name', 'like', '%'.$request->input('q').'%')
                        ->orWhere('location', 'like', '%'.$request->input('q').'%')
                        ->orWhere('quote', 'like', '%'.$request->input('q').'%');
                  });
            })
            ->paginate(20);

        $data = array(
          'pageTitle'=>'Testimonials',
          'testimonials'=> $testimonials,
          'request'=> $request,
        );
        return view('backend.testimonials.index')->with($data);
    }

    /**
     * Sort items through drag
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id){
            $testimonial = Testimonial::find($id);
            $testimonial->priority = $order;
            $testimonial->save();
        }

        echo 'completed!';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
          'pageTitle'=>'Create Testimonial'
        );
        return view('backend.testimonials.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $rules = array(
            'name'       => 'required|max:190',
            'location'       => 'required|max:190',
            'quote'       => 'required|max:60000',
            'rating' => 'required|max:5',
            'date'       => 'date|required'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/testimonials/create')
                ->withInput($request->all());
        } else {
            // store
            $testimonial = new Testimonial;
            $testimonial->name = $request->input('name');
            $testimonial->location = $request->input('location');
            $testimonial->quote = $request->input('quote');
            $testimonial->rating = $request->input('rating');
            $testimonial->date = date('Y-m-d', strtotime($request->input('date')));
            $testimonial->priority = 0;
            $testimonial->save();

            // redirect
            $request->session()->flash('message_success', 'Successfully created testimonial!');
            return redirect('admin/testimonials/'.$testimonial->id.'/edit');
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
        // get the slide
        $testimonial = Testimonial::find($id);

        if(settings('translations'))
        {
            $languages = Languages::first();
            $languages = $languages->languages_friendly_array;
        }
        else
        {
            $languages = '';
        }

        $data = array(
          'pageTitle'   =>  'Edit Testimonial',
          'testimonial'       =>  $testimonial,
          'languages' => $languages,
        );

        return view('backend.testimonials.edit')->with($data);
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
        // validate
        $rules = array(
            'name'       => 'required|max:190',
            'location'       => 'required|max:190',
            'quote'       => 'required|max:60000',
            'rating' => 'required|max:5',
            'date'       => 'date|required'
        );
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/testimonials/'.$id.'/edit');

        } else {
            // store
            $testimonial = Testimonial::find($id);
            $testimonial->name = $request->input('name');
            $testimonial->location = $request->input('location');
            $testimonial->quote = $request->input('quote');
            $testimonial->rating = $request->input('rating');
            $testimonial->date = date('Y-m-d', strtotime($request->input('date')));
            $testimonial->save();

            if(settings('translations'))
            {
                //To note adding new field must also add in app/Models/Translation fillable
                $translationFields = [
                    'quote'
                ];
                $this->saveTranslation($request, $testimonial, $translationFields, 'App\Testimonial'); //Saving translations...
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully update testimonial!');
            return redirect('admin/testimonials/'.$testimonial->id.'/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // delete
        $testimonial = Testimonial::find($id);

        if($testimonial)
        {
            $testimonial->delete();

            // Send Confirmed Response...
            return response()->json([
                'error'     => 'false',
                'redirect'  => '/admin/testimonials',
                'message'   => 'Testimonial Deleted!'
            ]);

        }
    }

    public function delete($id)
    {
        // delete
        $testimonial = Testimonial::find($id);
        $testimonial->delete();

        $data = ['success' => 'Successfully deleted the testimony!'];
        return redirect(admin_url('testimonials/'))->with($data);
    }


}
