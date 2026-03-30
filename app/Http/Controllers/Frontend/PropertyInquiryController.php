<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendModalQueryEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Support\Facades\Validator;
use stdClass;

class PropertyInquiryController extends Controller
{
    /**
     * Handle the property inquiry form submission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitInquiry(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:12', //regex:/^[A-Z][a-z]*$/
            'lastname' => 'required|string|max:12', //regex:/^[A-Z][a-z]*$/
            'country' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:255',
            'message' => 'nullable|string',
            'terms' => 'required|in:yes',
        ], [
            'fullname.max' => 'First name must not exceed 12 characters.',
            'lastname.max' => 'Last name must not exceed 12 characters.',
            'telephone.regex' => 'Phone number must start with + followed by numbers only.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors.',
                'errors' => $validator->errors()->messages()
            ]);
        }
        
        // Check for blocked email domains
        $blockedEmails = [
            'ericjonesmyemail@gmail.com',
            'info@professionalseocleanup.com',
            'mike@monkeydigital.co',
            'yawiviseya67@gmail.com',
            'cheeck-tttt@gmail.com',
            'ebojajuje04@gmail.com'
        ];
        
        $email = $request->input('email');
        $emailDomain = explode('@', $email)[1] ?? '';
        
        foreach ($blockedEmails as $blockedEmail) {
            if ($email === $blockedEmail || $emailDomain === $blockedEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email address has been blocked by the administrator.'
                ]);
            }
        }

        try {
            // Create an object to pass to the email template
            $enquiry = new stdClass();
            $enquiry->fullname = ucfirst($request->input('fullname'));
            $enquiry->lastname = ucfirst($request->input('lastname'));
            $enquiry->country = $request->input('country');
            $enquiry->area = $request->input('area');
            $enquiry->email = $request->input('email');
            $enquiry->telephone = $request->input('telephone');
            $enquiry->message = $request->input('message');
            $enquiry->newsletter = $request->has('newsletter') ? 'Yes' : 'No';
            
            // Set marketing email address
            // $oldtoemail = 'inquiries@terezaestates.com';
            $to = 'faizdev007@gmail.com';
            
            // Send email
            
            SendModalQueryEmail::dispatch($enquiry,$to);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your inquiry. We will contact you shortly.'
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Property Inquiry Email Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your inquiry. Please try again later.'
            ]);
        }
    }
    
    /**
     * Get areas based on country selection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreasByCountry(Request $request)
    {
        // // Validate request
        // $validator = Validator::make($request->all(), [
        //     'country' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Country is required'
        //     ], 400);
        // }

        $country = $request->input('country');
        
        if($country){
            // Get areas based on country
            $areas = get_areas('Area', $country);
            
            return response()->json($areas);
        }else{
            return response()->json([''=>'AREA',' '=>'Please Select The Location First']);
        }
    }


    /**
     * Get project based on area selection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getprojectByArea(Request $request)
    {
        if($request->input('area')){
            $area = $request->input('area');
            
            // Get areas based on country
            $projects = get_complexx('Project', $area, $request->ptype);
            
            return response()->json($projects);
        }
        return response()->json([' '=>'PROJECT',''=>'Please select the area first']);
    }
}
