namespace App\Services;

use App\Models\Complaint;

class ComplaintService
{
    public function create($data, $userId)
    {
        return Complaint::create([
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'pending'
        ]);
    }

    public function updateStatus($complaint, $status)
    {
        $complaint->status = $status;
        $complaint->save();

        return $complaint;
    }
}