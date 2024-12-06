<?php
namespace App\Http\Controllers\Backend;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view notification')->only('index','show');
        $this->middleware('permission:update notification')->only('updmarkNotificationAsReadate','markAllAsRead');
        $this->middleware('permission:delete notification')->only('destroy', 'destroyAll');
    }

    // Show all notifications for the authenticated user
    public function index()
	{
	    // Fetch all notifications for the authenticated user
	    $notifications = auth()->user()->notifications()->paginate(10);
	    return view('notifications.index', compact('notifications'));
	}

    // Mark a specific notification as read
	public function markNotificationAsRead($id)
	{
	    $notification = auth()->user()->notifications()->findOrFail($id);

	    // Mark as read
	    $notification->markAsRead();

	    // Return JSON response for AJAX update
	    return response()->json(['status' => 'success']);
	}

	public function markAsRead($id)
	{
	    $notification = auth()->user()->notifications()->findOrFail($id);
	    
	    // Mark the notification as read
	    $notification->markAsRead();
	    
	    // Return a JSON response
	    return response()->json(['status' => 'success']);
	}
    
    // Mark all notifications as read
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    // Delete a specific notification
    public function destroy($id)
    {
        // Find the notification for the authenticated user
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Delete the notification
        $notification->delete();

        // Redirect back with a success message
        return back()->with('success', 'Notification deleted successfully.');
    }

    // Delete all notifications
    public function destroyAll()
    {
        // Delete all notifications for the authenticated user
        auth()->user()->notifications()->delete();

        // Redirect back with a success message
        return back()->with('success', 'All notifications deleted successfully.');
    }
}
