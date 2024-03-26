<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    /*モデルのデフォルトのテーブル名 */
    protected $table = 'attendances';

    /*モデルの「マスアサインメント」の許可 */
    protected $fillable = ['user_id', 'date', 'clock_in_time', 'clock_out_time'];

    /*モデルの日付フィールドの保存形式 */
    protected $dateFormat = 'Y-m-d H:i:s';

    /*モデルのキャスト属性 */
    protected $casts = [
        'clock_in_time' => 'datetime:H:i:s',
        'clock_out_time' => 'datetime:H:i:s',
    ];

    /*リレーションシップ - Userモデル */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        //外部キー指定。デフォルトが間違った値になっていた為。
        return $this->hasMany(Breaks::class, 'attendance_id');
    }
}
