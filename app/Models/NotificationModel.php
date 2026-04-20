<?php
namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

protected $allowedFields = ['user_id','title','message','type','is_read','url','created_at'];

    public function getUnread(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->orderBy('created_at','DESC')
                    ->findAll(10);
    }

public function markAllRead(int $userId): void
    {
        $notifications = $this->where('user_id', $userId)->where('is_read', 0)->findAll();
        if (!empty($notifications)) {
            $this->where('user_id', $userId)->set('is_read', 1)->update();
        }
    }

    public function sendTo(int $userId, string $title, string $message, string $type = 'info'): void
    {
        $this->insert([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}