<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // تیکت‌هایی که کاربر فرستاده یا دریافت کرده است
        $tickets = Ticket::with(['sender', 'receiver'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_personnel_code' => 'required|exists:users,personnel_code',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'receiver_personnel_code.required' => 'وارد کردن کد پرسنلی گیرنده الزامی است.',
            'receiver_personnel_code.exists'   => 'کاربری با این کد پرسنلی یافت نشد.',
            'subject.required'                 => 'عنوان تیکت الزامی است.',
            'message.required'                 => 'متن پیام الزامی است.',
        ]);

        $receiver = User::where('personnel_code', $request->receiver_personnel_code)->first();

        if ($receiver->id === auth()->id()) {
            return back()->withInput()->withErrors(['receiver_personnel_code' => 'نمی‌توانید به خودتان تیکت ارسال کنید!']);
        }

        Ticket::create([
            'subject'     => $request->subject,
            'message'     => $request->message,
            'sender_id'   => auth()->id(),
            'receiver_id' => $receiver->id,
            'status'      => 'pending',
        ]);

        return redirect()->route('tickets.index')->with('success', 'تیکت پشتیبانی با موفقیت ارسال شد.');
    }

    public function show(Ticket $ticket)
    {
        // بررسی دسترسی کاربر به تیکت
        if ($ticket->sender_id !== auth()->id() && $ticket->receiver_id !== auth()->id() && auth()->user()->role->name !== 'مدیر ارشد') {
            abort(403, 'شما دسترسی به این تیکت را ندارید.');
        }

        $ticket->load(['sender.role', 'receiver.role']);
        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'وضعیت تیکت بروزرسانی شد.');
    }
}
