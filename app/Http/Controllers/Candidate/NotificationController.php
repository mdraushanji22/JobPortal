<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()->latest();

        if ($request->query('unread') === '1') {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(15)->withQueryString();

        return view('candidate.notifications.index', compact('notifications'));
    }

    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        $data = $notification->data;
        if (is_array($data) && isset($data['url'])) {
            return redirect($data['url']);
        }

        return redirect()->route('candidate.notifications.index');
    }

    public function readAll()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('candidate.notifications.index')
            ->with('success', 'All notifications marked as read.');
    }
}
