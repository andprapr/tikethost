<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Models\Ticket;
use App\Models\Gift;

class TicketController extends Controller
{

    /**
     * Display the main ticket page
     *
     * @return View
     */
    public function index(): View
    {
        // Get all gifts for display
        $gifts = \App\Models\Gift::all();
        
        // Get website settings
        $websiteSettings = \App\Models\WebsiteSetting::getInstance();
        
        return view('home', compact('gifts', 'websiteSettings'));
    }

    /**
     * Validate a ticket code
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validateTicket(Request $request): JsonResponse
    {
        $request->validate([
            'kode_tiket' => 'required|string|max:10'
        ]);

        $kodeTicket = $request->input('kode_tiket');

        // Check time constraint - tickets are only valid before 11:59 PM Jakarta Time
        $jakartaTime = now()->setTimezone('Asia/Jakarta');
        $cutoffTime = $jakartaTime->copy()->setTime(23, 59, 0); // 11:59 PM
        
        if ($jakartaTime->greaterThan($cutoffTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu klaim tiket telah berakhir. Tiket hanya dapat diklaim sebelum pukul 23:59 WIB setiap harinya.'
            ], 400);
        }

        // Check if ticket exists in database and is not used
        $ticket = Ticket::where('kode_tiket', $kodeTicket)
                       ->where('is_used', false)
                       ->first();

        if ($ticket) {
            // Store valid ticket in session
            session(['valid_tiket' => $kodeTicket]);
            
            // Get the full gift object data for the assigned gift
            $assignedGift = \App\Models\Gift::where('nama_hadiah', $ticket->hadiah)->first();
            
            // If exact match not found, try case-insensitive match
            if (!$assignedGift) {
                $assignedGift = \App\Models\Gift::whereRaw('LOWER(nama_hadiah) = LOWER(?)', [$ticket->hadiah])->first();
            }
            
            // Log the matching process for debugging
            \Log::info('Gift matching process:', [
                'ticket_code' => $kodeTicket,
                'ticket_hadiah' => $ticket->hadiah,
                'found_gift' => $assignedGift ? $assignedGift->toArray() : null,
                'all_gifts' => \App\Models\Gift::all()->pluck('nama_hadiah')->toArray()
            ]);
            
            $giftData = null;
            if ($assignedGift) {
                $giftData = [
                    'nama_hadiah' => $assignedGift->nama_hadiah,
                    'image_path' => $assignedGift->image_path,
                    'description' => $assignedGift->description ?? ''
                ];
            } else {
                // Fallback if gift not found in gifts table
                $giftData = [
                    'nama_hadiah' => $ticket->hadiah,
                    'image_path' => '',
                    'description' => ''
                ];
                
                // Log when fallback is used
                \Log::warning('Gift not found, using fallback:', [
                    'ticket_hadiah' => $ticket->hadiah,
                    'available_gifts' => \App\Models\Gift::all()->pluck('nama_hadiah')->toArray()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tiket valid! Anda bisa melanjutkan ke event.',
                'ticket_code' => $kodeTicket,
                'hadiah' => $giftData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode yang Anda masukkan salah, harap hubungi Admin untuk mendapatkan kode tiket.'
        ], 400);
    }

    /**
     * Show the event page (for valid tickets)
     *
     * @return View|JsonResponse
     */
    public function showEvent(): View|JsonResponse
    {
        if (!session('valid_tiket')) {
            return response()->json([
                'error' => 'Akses ditolak. Silakan masukkan kode tiket yang valid.'
            ], 403);
        }

        return view('event', ['ticket' => session('valid_tiket')]);
    }

    /**
     * Get all valid tickets (for admin purposes)
     *
     * @return JsonResponse
     */
    public function getValidTickets(): JsonResponse
    {
        $tickets = Ticket::where('is_used', false)->get();
        
        return response()->json([
            'valid_tickets' => $tickets->pluck('kode_tiket')->toArray(),
            'tickets_with_prizes' => $tickets->map(function($ticket) {
                return [
                    'kode_tiket' => $ticket->kode_tiket,
                    'hadiah' => $ticket->hadiah,
                    'created_at' => $ticket->created_at
                ];
            })
        ]);
    }

    /**
     * Check if a specific ticket is valid
     *
     * @param string $kodeTicket
     * @return JsonResponse
     */
    public function checkTicket(string $kodeTicket): JsonResponse
    {
        $ticket = Ticket::where('kode_tiket', $kodeTicket)
                       ->where('is_used', false)
                       ->first();
        
        $isValid = !is_null($ticket);
        
        $giftData = null;
        if ($isValid) {
            // Log ticket details for debugging
            Log::info('CheckTicket - Ticket found:', [
                'kode_tiket' => $ticket->kode_tiket,
                'hadiah' => $ticket->hadiah,
                'hadiah_type' => gettype($ticket->hadiah),
                'hadiah_length' => strlen($ticket->hadiah)
            ]);

            // Get the full gift object data for the assigned gift using case-insensitive matching
            $assignedGift = Gift::whereRaw('LOWER(nama_hadiah) = LOWER(?)', [$ticket->hadiah])->first();
            
            // Log the matching attempt
            Log::info('CheckTicket - Gift matching attempt:', [
                'searching_for' => $ticket->hadiah,
                'found_gift' => $assignedGift ? $assignedGift->nama_hadiah : 'NOT FOUND',
                'all_gifts' => Gift::pluck('nama_hadiah')->toArray()
            ]);
            
            if ($assignedGift) {
                $giftData = [
                    'nama_hadiah' => $assignedGift->nama_hadiah,
                    'image_path' => $assignedGift->image_path,
                    'description' => $assignedGift->description ?? ''
                ];
                
                Log::info('CheckTicket - Gift data prepared:', $giftData);
            } else {
                // Fallback if gift not found in gifts table
                $giftData = [
                    'nama_hadiah' => $ticket->hadiah,
                    'image_path' => '',
                    'description' => ''
                ];
                
                Log::warning('CheckTicket - Using fallback gift data:', $giftData);
            }
        }

        return response()->json([
            'ticket' => $kodeTicket,
            'is_valid' => $isValid,
            'hadiah' => $giftData,
            'message' => $isValid ? 'Tiket valid' : 'Tiket tidak valid'
        ]);
    }

    /**
     * Clear session (logout)
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        session()->forget('valid_tiket');
        
        return response()->json([
            'success' => true,
            'message' => 'Session berhasil dihapus.'
        ]);
    }

    /**
     * Get current session status
     *
     * @return JsonResponse
     */
    public function getSessionStatus(): JsonResponse
    {
        $validTicket = session('valid_tiket');
        
        return response()->json([
            'logged_in' => !is_null($validTicket),
            'ticket' => $validTicket,
            'message' => $validTicket ? 'Session aktif' : 'Session tidak aktif'
        ]);
    }

    /**
     * Claim a ticket (mark as used and expire it)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function claimTicket(Request $request): JsonResponse
    {
        $request->validate([
            'kode_tiket' => 'required|string|max:10'
        ]);

        $kodeTicket = $request->input('kode_tiket');

        // Check if user has a valid session for this ticket
        $sessionTicket = session('valid_tiket');
        if (!$sessionTicket || $sessionTicket !== $kodeTicket) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Silakan validasi tiket terlebih dahulu.'
            ], 403);
        }

        // Find the ticket
        $ticket = Ticket::where('kode_tiket', $kodeTicket)
                       ->where('is_used', false)
                       ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan atau sudah pernah diklaim.'
            ], 404);
        }

        // Mark ticket as used (claimed)
        $ticket->is_used = true;
        $ticket->save();

        // Clear the session to prevent further use
        session()->forget('valid_tiket');

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diklaim! Tiket telah kadaluarsa dan tidak dapat digunakan lagi.',
            'ticket_code' => $kodeTicket,
            'hadiah' => $ticket->hadiah
        ]);
    }
}