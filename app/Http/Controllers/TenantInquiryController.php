<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\UnitInquiry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\UnitInquiryNotification;

class TenantInquiryController extends Controller
{
    /**
     * Store a new inquiry for a unit
     */
    public function store(Request $request, Unit $unit)
    {
        // Validate the request
        $request->validate([
            'inquirer_name' => 'required|string|max:255',
            'inquirer_email' => 'required|email|max:255',
            'inquirer_phone' => 'nullable|string|max:20',
            'inquiry_type' => 'required|in:general_inquiry,booking_request,viewing_request',
            'message' => 'required_if:inquiry_type,general_inquiry|string|max:1000',
            'preferred_viewing_date' => 'required_if:inquiry_type,viewing_request|date|after:today',
            'preferred_viewing_time' => 'required_if:inquiry_type,viewing_request|date_format:H:i',
        ]);

        // Ensure the unit is available for inquiries
        if (!$unit->is_published || $unit->status !== 'vacant') {
            return redirect()->back()->with('error', 'This unit is no longer available for inquiries.');
        }

        // Create the inquiry
        $inquiry = UnitInquiry::create([
            'unit_id' => $unit->id,
            'inquirer_name' => $request->inquirer_name,
            'inquirer_email' => $request->inquirer_email,
            'inquirer_phone' => $request->inquirer_phone,
            'inquiry_type' => $request->inquiry_type,
            'message' => $request->message,
            'preferred_viewing_date' => $request->preferred_viewing_date,
            'preferred_viewing_time' => $request->preferred_viewing_time,
            'status' => 'pending',
        ]);

        // Send notification to property owner
        try {
            $unit->property->owner->notify(new UnitInquiryNotification($inquiry));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send inquiry notification: ' . $e->getMessage());
        }

        // Send confirmation email to inquirer
        try {
            Mail::raw(
                "Thank you for your inquiry about {$unit->unit_number} at {$unit->property->name}.\n\n" .
                "We have received your inquiry and will get back to you within 24 hours.\n\n" .
                "Inquiry Details:\n" .
                "- Type: " . ucfirst(str_replace('_', ' ', $request->inquiry_type)) . "\n" .
                ($request->message ? "- Message: {$request->message}\n" : "") .
                ($request->preferred_viewing_date ? "- Preferred Date: {$request->preferred_viewing_date}\n" : "") .
                ($request->preferred_viewing_time ? "- Preferred Time: {$request->preferred_viewing_time}\n" : "") .
                "\nBest regards,\n" .
                config('app.name') . " Team",
                function ($message) use ($request) {
                    $message->to($request->inquirer_email)
                            ->subject('Inquiry Confirmation - ' . config('app.name'));
                }
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send inquiry confirmation email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success',
            'Your inquiry has been sent successfully! We will get back to you within 24 hours.');
    }

    /**
     * Show inquiry form for a specific unit
     */
    public function create(Unit $unit)
    {
        // Ensure the unit is available for inquiries
        if (!$unit->is_published || $unit->status !== 'vacant') {
            abort(404, 'Unit not available for inquiries.');
        }

        return view('public_pages.rentals.inquiry', compact('unit'));
    }

    /**
     * Display inquiries for landlord (for their units)
     */
    public function index(Request $request)
    {
        $query = UnitInquiry::with(['unit.property'])
            ->whereHas('unit.property', function($q) {
                $q->where('owner_id', auth()->id());
            });

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('inquiry_type', $request->type);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('inquirer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('inquirer_email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('landlord.inquiries.index', compact('inquiries'));
    }

    /**
     * Show a specific inquiry
     */
    public function show(UnitInquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated user's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to inquiry.');
        }

        $inquiry->load(['unit.property']);

        return view('landlord.inquiries.show', compact('inquiry'));
    }

    /**
     * Respond to an inquiry
     */
    public function respond(Request $request, UnitInquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated user's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to inquiry.');
        }

        $request->validate([
            'response' => 'required|string|max:2000',
        ]);

        $inquiry->markAsResponded($request->response);

        // Send response email to inquirer
        try {
            Mail::raw(
                "Response to your inquiry about {$inquiry->unit->unit_number}:\n\n" .
                "{$request->response}\n\n" .
                "Best regards,\n" .
                "{$inquiry->unit->property->owner->name}",
                function ($message) use ($inquiry) {
                    $message->to($inquiry->inquirer_email)
                            ->subject('Response to Your Rental Inquiry');
                }
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send inquiry response email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Response sent successfully!');
    }

    /**
     * Close an inquiry
     */
    public function close(UnitInquiry $inquiry)
    {
        // Ensure the inquiry belongs to the authenticated user's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to inquiry.');
        }

        $inquiry->markAsClosed();

        return redirect()->back()->with('success', 'Inquiry closed successfully.');
    }
}
