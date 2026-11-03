<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Nhật ký admin khớp với dữ liệu đã seed. admin2 cố ý không có dòng nào (xóa được);
 * admin.cu có tác vụ nên không xóa được, chỉ bị vô hiệu hóa.
 */
class AdminLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@cinebook.vn')->value('id');
        $oldAdmin = User::where('email', 'admin.cu@cinebook.vn')->value('id');

        $rows = [];
        $log = function (int $adminId, string $action, string $table, int $id, $at) use (&$rows) {
            $rows[] = ['admin_id' => $adminId, 'action' => $action, 'target_table' => $table, 'target_id' => $id, 'created_at' => Carbon::parse($at)];
        };

        foreach (DB::table('theaters')->get(['id', 'created_at']) as $row) {
            $log($oldAdmin, 'create', 'theaters', $row->id, $row->created_at);
        }
        foreach (DB::table('movies')->get(['id', 'created_at']) as $row) {
            $log($admin, 'create', 'movies', $row->id, $row->created_at);
        }
        foreach (DB::table('promotions')->get(['id', 'created_at']) as $row) {
            $log($admin, 'create', 'promotions', $row->id, $row->created_at);
        }
        foreach (DB::table('reviews')->where('status', 'hidden')->get(['id', 'created_at']) as $row) {
            $log($admin, 'hide', 'reviews', $row->id, Carbon::parse($row->created_at)->addHours(3)->min(now()));
        }
        foreach (DB::table('showtimes')->where('status', 'cancelled')->get(['id']) as $row) {
            $log($admin, 'update', 'showtimes', $row->id, now()->subDay());
        }

        DB::table('admin_logs')->insert($rows);
    }
}
