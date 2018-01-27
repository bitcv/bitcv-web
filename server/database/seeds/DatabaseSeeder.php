<?php

use Illuminate\Database\Seeder;
use App\Models as Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Model\User::class, 1000)->create();
        factory(Model\UserFocus::class, 1000)->create();
        factory(Model\Token::class, 1000)->create();
        factory(Model\Project::class, 1000)->create();
        factory(Model\ProjTag::class, 1000)->create();
        factory(Model\ProjAdvantage::class, 1000)->create();
        factory(Model\ProjMember::class, 1000)->create();
        factory(Model\ProjEvent::class, 1000)->create();
        factory(Model\ProjPartner::class, 1000)->create();
        factory(Model\ProjMedia::class, 1000)->create();
        factory(Model\ProjSocial::class, 1000)->create();
        factory(Model\Social::class, 1000)->create();
        factory(Model\Media::class, 1000)->create();
    }
}
