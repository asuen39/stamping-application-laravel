<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaks extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = ['attendance_id', 'break_duration', 'break_out_duration'];

    /*モデルの日付フィールドの保存形式 */
    protected $dateFormat = 'Y-m-d H:i:s';

    /*モデルのキャスト属性 */
    protected $casts = [
        'break_duration' => 'datetime:H:i:s',
        'break_out_duration' => 'datetime:H:i:s',
    ];

    /*リレーションシップ - Attendancesモデル */
    public function attendance()
    {
        return $this->belongsTo(Attendances::class, 'attendance_id');
    }
}
