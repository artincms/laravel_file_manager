<?php
namespace ArtinCMS\LFM\Database\Seeds;

use Illuminate\Database\Seeder;

class LFM_CategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('lfm_categories')->delete();
        \DB::table('lfm_categories')->insert(
            array (
                'id' => '0',
                'user_id' => 0,
                'title' => 'Root folder',
                'title_disc' => 'root_folder',
                'description' => 'root',
                'parent_category_id' => '#',
                'created_by' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            )
        );
        \DB::unprepared("UPDATE lfm_categories SET id=0");
        \DB::table('lfm_categories')->insert(array (
            0 =>
                array (
                    'id' => -5,
                    'user_id' => 0,
                    'title' => 'Direct upload',
                    'title_disc' => 'direct_folder',
                    'description' => 'direct upload folder',
                    'parent_category_id' => '#',
                    'created_by' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            1 =>
                array (
                    'id' => -2,
                    'user_id' => 0,
                    'title' => 'Share folder',
                    'title_disc' => 'share_folder',
                    'description' => 'share',
                    'parent_category_id' => '#',
                    'created_by' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
            2 =>
                array (
                    'id' => -1,
                    'user_id' => 0,
                    'title' => 'public folder',
                    'title_disc' => 'public_folder',
                    'description' => 'public',
                    'parent_category_id' => '#',
                    'created_by' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ),
        ));

    }
}