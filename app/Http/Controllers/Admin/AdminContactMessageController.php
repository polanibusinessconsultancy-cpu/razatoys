<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = ContactMessage::query();

        if (filled($status) && in_array($status, ['new', 'read', 'replied', 'closed'])) {
            $query->where('status', $status);
        }

        $messages = $query->latest('id')->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'statusCounts', 'status'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied,closed',
        ]);

        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', 'Message status updated successfully.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Contact message deleted successfully.');
    }
}
